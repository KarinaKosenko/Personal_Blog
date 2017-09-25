<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Model;
use Core\Sql;
/**
 * Description of Sessions
 *
 * @author admin
 */
class Sessions extends Model
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    protected function __construct()
    {
        session_start();
        $this->db = Sql::instance();
        $this->table = 'sessions';
        $this->pk = 'id_session';
        parent::__construct();
    }
    
    public function validationMap()
    {
        return [
            'table' => 'sessions',
            'pk' => 'id_session',
            'fields' => ['id_session', 'token', 'id_user', 'time_start', 'last_activity'],
        ];
    }
	
    public function clearOld()
    {
        $min = date('Y-m-d H:i:s', time() - 60 * 20); 			
        $where = "last_activity < '$min'";
        $this->db->delete($this->table, $where);
    }
	
    public function get($token)
    {
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE token = :token",
                                   ['token' => $token]);

        return $res[0] ?? null;
    }
	
    public function edit_token($token, $obj)
    {
        return $this->db->update($this->table, $obj, "token=:token", ['token' => $token]);
    }
}
