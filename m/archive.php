<?php

namespace M;

/**
 * Description of archive
 *
 * @author admin
 */
use Core\Sql;

class Archive {
    use \Core\Traits\Singleton;
    
    protected $db;
    
    public function __construct() {
        $this->db = Sql::instance();
    }
    
    
    public function getArticles($month, $year)
    {
        $res = $this->db->select("SELECT * FROM articles WHERE `date` BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31'");
        
        return $res;
    }
}
