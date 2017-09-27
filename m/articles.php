<?php
 
namespace M;

use Core\Model;

/**
 * Class Articles - a model to work with articles.
 */
class Articles extends Model
{
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
        parent::__construct();
        // Set database table.
        $this->table = 'articles';
        // Set a primary key for table.
        $this->pk = 'id_article';
        // Set articles count for one page.
        $this->per_page = 2;
        // Set current page.
        $this->cur_page = $cur_page;
    }

    /**
     * Method determines validation rules.
     *
     * @return array
     */
    public function validationMap()
    {
        return
        [
            'table' => 'articles',
            'pk' => 'id_article',
            'fields' => ['id_article', 'title', 'content', 'date', 'author', 'image_link'],
            'not_empty' => ['title', 'content'],
            'min_length' => [
                'title' => 5,
                'content' => 10,
            ],
            'unique' => ['title'],
            'html_allowed' => ['content', 'image_link'],
        ];
    }

    /**
     * Method returns all articles information.
     *
     * @return array
     */
    public function getData()
    {
        $start = ($this->cur_page - 1) * $this->per_page;
        $data = $this->db->select("SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} ORDER BY date DESC LIMIT $start, $this->per_page");
        $get_rows = $this->db->select("SELECT FOUND_ROWS()");
        $rows = $get_rows[0]["FOUND_ROWS()"];
        $num_pages = ceil($rows / $this->per_page);

        $obj = compact("data", "rows", "start", "num_pages");
        return $obj;
    }

    /**
     * Method calculates comments count for articles.
     *
     * @return mixed
     */
    public function commentsCounter()
    {
        $articles = $this->db->select("SELECT id_article AS article, "
                . "(SELECT count(id_comment) FROM comments "
                . "WHERE id_article=article) AS cnt_comments FROM articles "
                . "ORDER BY cnt_comments DESC LIMIT 0, 3");
        
        return $articles;
    }
	
}

