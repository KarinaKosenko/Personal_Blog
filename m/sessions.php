<?php

namespace M;

use Core\Model;
use Core\Sql;

/**
 * Class Sessions - a model to work with sessions.
 */
class Sessions extends Model
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    protected function __construct()
    {
        // Open session.
        session_start();
        $this->db = Sql::instance();
        // Determine database table.
        $this->table = 'sessions';
        // Determine primary key.
        $this->pk = 'id_session';
        parent::__construct();
    }

    /**
     * Method determines validation rules.
     *
     * @return array
     */
    public function validationMap()
    {
        return [
            'table' => 'sessions',
            'pk' => 'id_session',
            'fields' => [
                'id_session', 'token', 'id_user', 'time_start', 'last_activity'
            ],
        ];
    }

    /**
     * Method to clear session for inactive users.
     */
    public function clearOld()
    {
        $min = date('Y-m-d H:i:s', time() - 60 * 20); 			
        $where = "last_activity < '$min'";
        $this->db->delete($this->table, $where);
    }

    /**
     * Method returns user token from database.
     *
     * @param $token
     * @return null
     */
    public function get($token)
    {
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE token = :token",
                                   ['token' => $token]);

        return $res[0] ?? null;
    }

    /**
     * Method updates user token in the database.
     *
     * @param $token
     * @param $obj
     * @return mixed
     */
    public function edit_token($token, $obj)
    {
        return $this->db->update($this->table, $obj, "token=:token", ['token' => $token]);
    }
}


