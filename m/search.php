<?php

namespace M;

use Core\Sql;

/**
 * Class Search - a model to work with searching.
 */
class Search 
{
    protected $db;
    public $per_page;
    public $cur_page;
    protected static $instances = [];
    
    public static function instance($path = 1)
    {
        if (!isset(self::$instances[$path])) {
            self::$instances[$path] = new self($path);
        }
       
        return self::$instances[$path];
    }
	
    protected function __construct($cur_page)
    {
        $this->db = Sql::instance();
        // Determine database table.
        $this->table = 'articles';
        // Determine primary key.
        $this->pk = 'id_article';
        // Number for articles to one page.
        $this->per_page = 5;
        // Set current page.
        $this->cur_page = $cur_page;
    }

    /**
     * Method for data searching in the database.
     *
     * @param $string
     * @return array
     */
    public function searchData($string)
    {
        $search = $this->getSearchData($string);
        $start = ($this->cur_page - 1) * $this->per_page;

        if ($search == null) {
            $data = [];
            $rows = 0;
        }
        else {
            $data = $this->db->select("SELECT SQL_CALC_FOUND_ROWS *,
									  MATCH `title` AGAINST ('$search*' IN BOOLEAN MODE) + "
                . "MATCH `content` AGAINST ('$search*' IN BOOLEAN MODE) as relev "
                . "FROM `articles` "
                . "WHERE MATCH `title` AGAINST ('$search*' IN BOOLEAN MODE) + "
                . "MATCH `content` AGAINST ('$search*' IN BOOLEAN MODE) > 0 "
                . "ORDER BY relev DESC LIMIT $start, $this->per_page");


            $get_rows = $this->db->select("SELECT FOUND_ROWS()");
            $rows = $get_rows[0]["FOUND_ROWS()"];
        }

        $num_pages = ceil($rows / $this->per_page);
        $obj = compact("data", "rows", "start", "num_pages");

        return $obj;
    }

    /**
     * Method for valid search data generation.
     *
     * @param string $search
     * @return bool|mixed|string|void
     */
    protected function getSearchData(string $search)
    {
        // Crop extra characters.
        $search = substr($search, 0, 100);
        // Replace forbidden characters.
        $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
        // Get keywords array.
        $array = explode(' ', $search);
        $valid_array = [];
        // Array validation.
        if (count($array) > 0) {
            foreach ($array as $one) {
                $one = trim($one);
                if (strlen($one) > 2) {
					if (strlen($one) > 12) {
						$one = substr($one, 0, (strlen($one) - 4));
					}
				   $valid_array[] = $one;
                }
				else {
					return;
				}
            }
            if (count($valid_array) === 0) {
                return;
            }
            else {
                $search = implode('* ', $valid_array);
                return $search;
            }
        }
        else {
            return;
        }
    }
}


