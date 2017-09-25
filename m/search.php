<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Sql;

/**
 * Description of search
 *
 * @author admin
 */
class Search 
{
    protected $db;
    public $per_page;
    public $cur_page;
    protected static $instances = [];
    
    public static function instance($path = 1)
    {
        if(!isset(self::$instances[$path])){
            self::$instances[$path] = new self($path);
        }
       
        return self::$instances[$path];
    }
	
    protected function __construct($cur_page)
    {
            $this->db = Sql::instance();
            $this->table = 'articles';
            $this->pk = 'id_article';
            $this->per_page = 5;
            $this->cur_page = $cur_page;
    }
    
    
    public function getSearchData(string $search){
        $search = substr($search, 0, 100);
        $search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
        
        $array = explode(' ', $search);
        $valid_array = [];
        
        if(count($array) > 0){
            foreach($array as $one){
                $one = trim($one);
                if(strlen($one) > 2){
					if(strlen($one) > 12){
						$one = substr($one, 0, (strlen($one) - 4));
					}
                   
				   $valid_array[] = $one;
                }
				else{
					return;
				}
            }
            
            if(count($valid_array) === 0){
                return;
            }
            else{
                $search = implode('* ', $valid_array);
                return $search;
            }
        }
        else{
            return;
        }
    }
    
    public function searchData($string){
        $search = $this->getSearchData($string);
		$start = ($this->cur_page - 1) * $this->per_page;
		
		if($search == null){
			$data = [];
			$rows = 0;
		}
		else{
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
}
