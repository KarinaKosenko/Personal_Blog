<?php
 
namespace Core; 

use Core\Sql;   

class Validation{ 
	protected $pk;
    protected $obj;
    protected $rules;
    protected $errors;
    protected $clean_obj;
    
    public function __construct($obj, $rules){
		$this->db = Sql::instance();
        $this->obj = $obj;
        $this->rules = $rules;
        $this->errors = [];
    }
	
	
	public function one_add($table, $column, $value){
       $res = $this->db->select("SELECT * FROM {$table} WHERE {$column}=:column",
                                   ['column' => $value]);

        return $res[0] ?? false;
    }
	
	public function one_edit($table, $column, $value, $pk, $pk_value){
       $res = $this->db->select("SELECT * FROM {$table} WHERE {$column}=:column AND {$pk} != :pk",
                                   [
								   'column' => $value,
								   'pk' => $pk_value
								   ]);

        return $res[0] ?? false;
    }
	
    
    public function execute($action_name)
    {
        foreach($this->obj as $k => $v){
            $value = trim($v);
        
            if(in_array($k, $this->rules['not_empty']) && $value == ''){
                $this->errors[] = "Поле $k не может быть пустым.";
            }
            elseif(isset($this->rules['min_length'][$k]) && 
                strlen($value) < $this->rules['min_length'][$k]){
                $div = ceil($this->rules['min_length'][$k]/2);
                $this->errors[] = "Поле $k не может быть меньше {$this->rules['min_length'][$k]} латинских символов и не меньше $div русских символов.";
            }
            elseif($action_name === 'add' && in_array($k, $this->rules['unique']) && $this->one_add($this->rules['table'], $k, $value) != false){
                    $this->errors[] = "Такое значение поля $k уже существует.";
            }
            elseif($action_name === 'edit' && in_array($k, $this->rules['unique']) && $this->one_edit($this->rules['table'], $k, $value, $this->rules['pk'], $this->obj[$this->rules['pk']]) != false){
                    $this->errors[] = "Такое значение поля $k уже существует.";
            }
            else{
                if(!in_array($k, $this->rules['html_allowed'])){
                        $value = htmlspecialchars($value);
                }

                $this->clean_obj[$k] = $value;
            }
        }
    }
    
    public function good(){
        return count($this->errors) == 0;
    }
    
    public function cleanObj(){
        return $this->clean_obj;
    }
    
    public function errors(){
        return $this->errors;
    }
}