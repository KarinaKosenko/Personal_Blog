<?php

namespace M;

use Core\Sql;

/**
 * Class Auth - a model to work with authentication and authorization.
 */
class Auth
{
    use \Core\Traits\Singleton;
    // Current session identifier.
    private $token;
    // Current user identifier.
    private $uid;
    // Users online map.
    private $onlineMap;
    // Sessions model.
    private $mSessions;
    private $table;
    private $privs_cahce;
    // Users model.
    private $mUsers;
    private $db;
	
	
    protected function __construct()
    {
        $this->table = 'users';
        $this->token = null;
        $this->uid = null;
        $this->onlineMap = null;
        $this->privs_cahce = array();   
        $this->db = Sql::instance();
        $this->mUsers = User::Instance();
        $this->mSessions = Sessions::Instance();
    }

    /**
     * Method for user authentication.
     *
     * @param $login
     * @param $password
     * @param bool $remember
     * @return bool
     */
    public function Login($login, $password, $remember = true)
    {
        // Get user from database by login.
        $user = $this->GetByLogin($login);

        if ($user === null) {
            return false;
        }

        $id_user = $user['id_user'];
        // Chech password.
        if($user['password'] != password_verify($password, $user['password'])){
            return false;
        }		
        // Remember login and password.
        if ($remember)
        {
            $expire = time() + 3600 * 24 * 100;
            setcookie('login', $login, $expire, '/');
            setcookie('password', password_hash($password, PASSWORD_DEFAULT), $expire, '/');
        }		

        // Open user session and remember SID.
        $this->token = $this->OpenSession($id_user);

        return true;
    }

    /**
     * Method to log out.
     */
    public function Logout()
    {
        setcookie('login', '', time() - 1, '/');
        setcookie('password', '', time() - 1, '/');
        unset($_COOKIE['login']);
        unset($_COOKIE['password']);
        unset($_SESSION['token']);		
        $this->token = null;
        $this->uid = null;
    }

    /**
     * Method to get user object.
     *
     * @return null
     */
    public function Get()
    {	
        $id_user = $this->GetUid();

        if ($id_user == null) {
            return null;
        }

        // Return user by id.
        return $this->mUsers->one($id_user);
    }

    // Chech authentication status.
    public function isAuth()
    {
        return ($this->Get() === null) ? false : true;
    }

    /**
     * Method to get user by login.
     *
     * @param $login
     * @return null
     */
    public function GetByLogin($login)
    {	
        $query = "SELECT * FROM {$this->table} WHERE login = :login";
        $result = $this->db->select($query, ['login' => $login]);
        return $result[0] ?? null;
    }

    /**
     * Check user privilege.
     *
     * @param $priv
     * @param null $id_user
     * @return bool
     */
    public function Can($priv, $id_user = null)
    {		
        if ($id_user == null) {
            $id_user = $this->GetUid();
            if ($id_user == null) {
                    return false;
            }
        }   

        $set_var = "SET @var = 0;"; 
        $var = $this->db->select($set_var);

        $query = "SELECT name, 
                                @var := (SELECT count(*)
                                        FROM users
                                        JOIN priv2role USING (id_role)
                                        JOIN privilegies USING (id_priv)
                                        WHERE id_user = :id_user AND privilegies.name = :priv) as var,
                                IF(@var > 0, 'true', 'false') as res
                        FROM users WHERE id_user = :id_user;";

        $result = $this->db->select($query, ['id_user' => $id_user, 'priv' => $priv]);
        return $result[0]['res'];
    }

    /**
     * Check user current activity.
     *
     * @param $id_user
     * @return bool
     */
    public function IsOnline($id_user)
    {		
        if ($this->onlineMap == null)
        {	    
            $t = "SELECT DISTINCT id_user FROM  " . SQL_PREFIX . "_sessions";		
            $query  = sprintf($t, $id_user);
            $result = $this->db->Select($query);

            foreach ($result as $item)
                $this->onlineMap[$item['id_user']] = true;		    
        }

        return ($this->onlineMap[$id_user] != null);
    }

    /**
     * Get current user id.
     *
     * @return null
     */
    public function GetUid()
    {	
        // Check cache.
        if ($this->uid != null)
            return $this->uid;

        // Get by current session.
        $token = $this->GetToken();
        if ($token == null) {
            return null;
        }

        $result = $this->mSessions->get($token);
        if ($result == null) {
            return null;
        }

        // Remember user id if exists.
        $this->uid = $result['id_user'];
        return $this->uid;
    }

    /**
     * Get current session identifier.
     *
     * @return null|string
     */
    private function GetToken()
    {
        // Check cache.
        if ($this->token != null) {
            return $this->token;
        }

        // Search SID in session.
        $token = $_SESSION['token'] ?? null;

        // Update last activity time in the database.
        if ($token != null)
        {			
            $affected_rows = $this->mSessions->edit_token($token, ['last_activity' => date('Y-m-d H:i:s')]);
            if (($affected_rows == 0) && ($this->mSessions->get($token) == null)) {
                $token = null;
            }
        }		

        // Check cookies.
        if ($token == null && isset($_COOKIE['login']))
        {
            $user = $this->GetByLogin($_COOKIE['login']);
            if ($user != null && $user['password'] == $_COOKIE['password']) {
                $token = $this->OpenSession($user['id_user']);
            }
        }

        // Remember to cache.
        if ($token != null) {
            $this->token = $token;
        }

        return $token;		
    }

    /**
     * Open new session.
     *
     * @param $id_user
     * @return string
     */
    private function OpenSession($id_user)
    {
        // Generate SID.
        $token = $this->GenerateStr(10);

        // Insert SID to database.
        $now = date('Y-m-d H:i:s'); 
        $session = array();
        $session['id_user'] = $id_user;
        $session['token'] = $token;
        $session['time_start'] = $now;
        $session['last_activity'] = $now;				
        $this->mSessions->add($session);

        // Register current session in PHP-session.
        $_SESSION['token'] = $token;				

        return $token;	
    }

    /**
     * Generate hash-code.
     *
     * @param int $length
     * @return string
     */
    private function GenerateStr($length = 10) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;  

        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];  
        }
        return $code;
    }

    /**
     * Get user privilege.
     *
     * @param null $id_user
     * @return array
     */
    public function GetPrivs($id_user = null)
    {
        if ($id_user == null) {
            $id_user = $this->GetUid();
        }
        if ($id_user == null) {
            return array();
        }
        $user = $this->Get($id_user);
        $arr = $this->db
            ->Select("SELECT " . SQL_PREFIX . "_privs.name as name FROM  " . SQL_PREFIX . "_privs2roles LEFT JOIN " .
                    SQL_PREFIX . "_privs USING(id_priv) WHERE id_role = '{$user['id_role']}'");

        $privs = [];
        foreach ($arr as $elem) {
            $privs[] = $elem['name'];
        }

        return $privs;
    }

    /**
     * Get user roles.
     *
     * @return null
     */
    public function GetRole()
    {
        $pk = $this->getUid();
        $res = $this->db
            ->select("SELECT * FROM {$this->table} LEFT JOIN roles using (id_role) WHERE id_user = :pk",
                                   ['pk' => $pk]);

        return $res[0]['name'] ?? null;
    }

    /**
     * Get user name.
     *
     * @return null
     */
    public function getUserName()
    {
        $pk = $this->getUid();
        $res = $this->db
            ->select("SELECT * FROM {$this->table} WHERE id_user = :pk",
                                   ['pk' => $pk]);

        return $res[0]['name'] ?? null;
    }	
    
}


