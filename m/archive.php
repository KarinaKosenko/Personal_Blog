<?php

namespace M;

use Core\Sql;

/**
 * Class Archive - a model to work with archive articles.
 */
class Archive
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    public function __construct()
    {
        $this->db = Sql::instance();
    }

    /**
     * Method returns articles within a specified period.
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getArticles($month, $year)
    {
        $res = $this->db->select("SELECT * FROM articles WHERE `date` BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31'");
        return $res;
    }
}


