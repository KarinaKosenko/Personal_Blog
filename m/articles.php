<?php
 
namespace M;

use Core\Model;  
         
class Articles extends Model
{
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
            parent::__construct();
            $this->table = 'articles';
            $this->pk = 'id_article';
            $this->per_page = 2;
            $this->cur_page = $cur_page;
    }
	
	
	
    public function validationMap()
    {
    return [
                    'table' => 'articles',
                    'pk' => 'id_article',
        'fields' => ['id_article', 'title', 'content', 'date', 'author', 'image_link'],
        'not_empty' => ['title', 'content'],
        'min_length' => [
            'title' => 5,
            'content' => 10
        ],
                    'unique' => ['title'],
                    'html_allowed' => ['content', 'image_link']
    ];
    }
    
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
    
    
    public function commentsCounter()
    {
        $articles = $this->db->select("SELECT id_article AS article, "
                . "(SELECT count(id_comment) FROM comments "
                . "WHERE id_article=article) AS cnt_comments FROM articles "
                . "ORDER BY cnt_comments DESC LIMIT 0, 3");
        
        return $articles;
    }
	
}