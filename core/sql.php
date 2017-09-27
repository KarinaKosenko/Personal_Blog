<?php
 
namespace Core; 
 
use PDO;
use Core\Exceptions;

/**
 * Class Sql - class to work with database.
 */
class Sql
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    protected function __construct()
    {
        // Include database settings.
        $settings = include_once "settings.php";
        // Connect to database.
        $this->db = new PDO("$driver:host=$host;dbname=$name", "$user", "$pass", [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        $this->db->exec("SET NAMES UTF8"); 
    }

    /**
     * Select-query wrapper.
     *
     * @param $sql
     * @param array $params
     * @return array
     */
    public function select($sql, $params = [])
    {
        $query = $this->db->prepare($sql);
        $query->execute($params);
        $this->check_query($query);
        return $query->fetchAll();
    }

    /**
     * Insert-query wrapper.
     *
     * @param $table
     * @param $obj
     * @return string
     */
    public function insert($table, $obj)
    {
        $keys = [];
        $masks = [];
        
        foreach($obj as $k => $v){
            $keys[] = $k;
            $masks[] = ':' . $k;
        }
        
        $fields = implode(', ', $keys);
        $values = implode(', ', $masks);
        
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";

        $query = $this->db->prepare($sql);
        $query->execute($obj);
        $this->check_query($query);
        
        return $this->db->lastInsertId();
    }

    /**
     * Update-query wrapper.
     *
     * @param $table
     * @param $obj
     * @param $where
     * @param array $params
     * @return int
     */
	public function update($table, $obj, $where, $params = [])
    {
        $pairs = [];
        
        foreach ($obj as $k => $v) {
            $pairs[] = "$k=:$k";
        }
        
        $pairs_str = implode(',', $pairs);
        $sql = "UPDATE $table SET $pairs_str WHERE $where";
        
        $merge = array_merge($obj, $params);
        
        $query = $this->db->prepare($sql);
        $query->execute($merge);
        $this->check_query($query);
        
        return $query->rowCount();
    }

    /**
     * Delete-query wrapper.
     *
     * @param $table
     * @param $where
     * @param array $params
     * @return int
     */
    public function delete($table, $where, $params = [])
    {
        $sql = "DELETE FROM $table WHERE $where";
        $query = $this->db->prepare($sql);
        $query->execute($params);
        $this->check_query($query);
        return $query->rowCount();
    }

    /**
     *Convert PDO errors to Exceptions Fatal.
     *
     * @param $query
     * @throws Exceptions\Fatal
     */
    protected function check_query($query)
    {
        if ($query->errorCode() != PDO::ERR_NONE) {
			throw new Exceptions\Fatal($query->errorInfo()[2]);
        }
    }
}

