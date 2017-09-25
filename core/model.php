<?php
 
namespace Core;    
        
use Core\Exceptions;       
        
abstract class Model{ 
    
    protected $db;
    protected $table;
    protected $pk;
    protected $settings;
    
    public abstract function validationMap();
    
    protected function __construct(){
        $this->settings = include "settings.php";
        $this->db = Sql::instance();
    }

    public function all(){
        return $this->db->select("SELECT * FROM {$this->table}");
    }
    
    public function one($pk){
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE {$this->pk}=:pk",
                                   ['pk' => $pk]);

        return $res[0] ?? null;
    }
    
    public function delete($pk){
        return $this->db->delete($this->table, "{$this->pk}=:pk", ['pk' => $pk]);
    }
    
    public function add($obj){
        /* в будущем может появится валидация по массива */
        
        $map = $this->validationMap();
        
        foreach($obj as $k => $v){
            if(!in_array($k, $map['fields'])){
                throw new Exceptions\Fatal("Column $k is not exists.");
            }
        }
        
        return $this->db->insert($this->table, $obj);
    }
    
    public function edit($pk, $obj){
        /* в будущем может появится валидация по массива */
        $map = $this->validationMap();
        
        foreach($obj as $k => $v){
            if(!in_array($k, $map['fields'])){
                throw new Exceptions\Fatal("Column $k is not exists.");
            }
        }
        
		return $this->db->update($this->table, $obj, "{$this->pk}=:pk", ['pk' => $pk]);
    }

}