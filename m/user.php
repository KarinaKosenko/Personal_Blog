<?php

namespace M;

use Core\Model;
use Core\Validation;

/**
 * Class User - a model to work with users.
 */
class User extends Model
{
    use \Core\Traits\Singleton;
    
    protected function __construct()
    {
        // Determine database table.
        $this->table = 'users';
        // Determine primary key.
        $this->pk = 'id_user';  
        parent::__construct();
    }

    /**
     * Method returns validation rules.
     *
     * @return array
     */
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
                'password' => 6,
            ],
            'unique' => ['login'],
            'html_allowed' => [],
        ];
    }

    /**
     * Method to hash user password.
     *
     * @param $array
     * @return mixed
     */
    public function hash_password($array)
    {
        $array['password'] = password_hash($array['password'], PASSWORD_DEFAULT);
        return $array;
    }
}


