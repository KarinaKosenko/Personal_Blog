<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Model;
use Core\Validation;
/**
 * Description of User
 *
 * @author admin
 */
class User extends Model
{
    use \Core\Traits\Singleton;
    
    protected function __construct(){
        $this->table = 'users'; 
        $this->pk = 'id_user';  
        parent::__construct();
    }
	
    public function validationMap()
    {
        return [
            'table' => 'users',
            'pk' => 'id_user',
            'fields' => ['id_user', 'name', 'login', 'password'],
            'not_empty' => ['name', 'login', 'password'],
            'min_length' => [
                'name' => 2,  
                'login' => 6,
                'password' => 6
            ],
            'unique' => ['login'],
            'html_allowed' => []
        ];
    }

	
    public function hash_password($array)
    {
        $array['password'] = password_hash($array['password'], PASSWORD_DEFAULT);
        return $array;
    }
}
