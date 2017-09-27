<?php
 
namespace Core;    
        
use Core\Exceptions;

/**
 * Class Model - parent class for other models.
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $pk;
    protected $settings;

    /**
     * Abstract method to determine validation rules.
     */
    public abstract function validationMap();
    
    protected function __construct()
    {
        // Include database configuration.
        $this->settings = include "settings.php";
        $this->db = Sql::instance();
    }

    /**
     * Method returns all records from defined table.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->select("SELECT * FROM {$this->table}");
    }

    /**
     * Method returns one record (by primary key) from defined table or null.
     *
     * @param $pk
     * @return null
     */
    public function one($pk)
    {
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE {$this->pk}=:pk",
                                   ['pk' => $pk]);

        return $res[0] ?? null;
    }

    /**
     * Method deletes one record (by primary key) from defined table.
     *
     * @param $pk
     * @return mixed
     */
    public function delete($pk)
    {
        return $this->db->delete($this->table, "{$this->pk}=:pk", ['pk' => $pk]);
    }

    /**
     * Method insert a record to defined table.
     *
     * @param $obj
     * @return mixed
     * @throws Exceptions\Fatal
     */
    public function add($obj)
    {
        // Get validation rules.
        $map = $this->validationMap();
        // Check column existence.
        foreach($obj as $k => $v) {
            if (!in_array($k, $map['fields'])) {
                throw new Exceptions\Fatal("Column $k is not exists.");
            }
        }
        
        return $this->db->insert($this->table, $obj);
    }

    /**
     * Method updates a record from (by primary key) from defined table.
     *
     * @param $pk
     * @param $obj
     * @return mixed
     * @throws Exceptions\Fatal
     */
    public function edit($pk, $obj)
    {
        // Get validation rules.
        $map = $this->validationMap();
        // Check column existense.
        foreach ($obj as $k => $v) {
            if (!in_array($k, $map['fields'])) {
                throw new Exceptions\Fatal("Column $k is not exists.");
            }
        }
        
		return $this->db->update($this->table, $obj, "{$this->pk}=:pk", ['pk' => $pk]);
    }

}

