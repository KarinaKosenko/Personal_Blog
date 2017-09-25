<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Sql;
/**
 * Description of auth
 *
 * @author admin
 */
class Auth
{
    use \Core\Traits\Singleton;
    
    private $token;				// идентификатор текущей сессии
    private $uid;				// идентификатор текущего пользователя
    private $onlineMap;			// карта пользователей online
    private $mSessions;			// модель для работы с сессиями
    private $table;
    private $privs_cahce;
    private $mUsers;
    private $db;
	
	
    protected function __construct(){
        $this->table = 'users';
        $this->token = null;
        $this->uid = null;
        $this->onlineMap = null;
        $this->privs_cahce = array();   
        $this->db = Sql::instance();
        $this->mUsers = User::Instance();
        $this->mSessions = Sessions::Instance();
    }

    //

    // Авторизация
    // $login 		- логин
    // $password 	- пароль
    // $remember 	- нужно ли запомнить в куках
    // результат	- true или false
    //
    public function Login($login, $password, $remember = true)
    {
        // вытаскиваем пользователя из БД 
        $user = $this->GetByLogin($login);

        if ($user === null){
            return false;
        }

        $id_user = $user['id_user'];

        // проверяем пароль
        if($user['password'] != password_verify($password, $user['password'])){
            return false;
        }		
        // запоминаем имя и пароль с password_hash():
        if($remember)
        {
            $expire = time() + 3600 * 24 * 100;
            setcookie('login', $login, $expire, '/');
            setcookie('password', password_hash($password, PASSWORD_DEFAULT), $expire, '/');
        }		

        // открываем сессию и запоминаем SID
        $this->token = $this->OpenSession($id_user);

        return true;
    }

    //
    // Выход
    //
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
						
    //
    // Получение пользователя
    // $id_user		- если не указан, брать текущего
    // результат	- объект пользователя
    //
    public function Get()
    {	
        $id_user = $this->GetUid();

        if($id_user == null)
                return null;

        // А теперь просто возвращаем пользователя по id_user.
        return $this->mUsers->one($id_user);
    }


    public function isAuth(){
        return ($this->Get() === null) ? false : true;
    }
	
    //
    // Получает пользователя по логину
    //
    public function GetByLogin($login)
    {	
        $query = "SELECT * FROM {$this->table} WHERE login = :login";
        $result = $this->db->select($query, ['login' => $login]);
        return $result[0] ?? null;
    }

    //
    // Проверка наличия привилегии
    // $priv 		- имя привилегии
    // $id_user		- если не указан, значит, для текущего
    // результат	- true или false
    //
    public function Can($priv, $id_user = null)
    {		
        if ($id_user == null){
            $id_user = $this->GetUid();

                if($id_user == null){
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

    //
    // Проверка активности пользователя
    // $id_user		- идентификатор
    // результат	- true если online
    //
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
	
    //
    // Получение id текущего пользователя
    // результат	- UID
    //
    public function GetUid()
    {	
        // Проверка кеша.
        if ($this->uid != null)
                return $this->uid;	

        // Берем по текущей сессии.
        $token = $this->GetToken();

        if ($token == null)
                return null;

        $result = $this->mSessions->get($token);

        // Если сессию не нашли - значит пользователь не авторизован.
        if ($result == null)
                return null;

        // Если нашли - запоминм ее.
        $this->uid = $result['id_user'];
        return $this->uid;
    }

    //
    // Функция возвращает идентификатор текущей сессии
    // результат	- SID
    //
    private function GetToken()
    {
        // Проверка кеша.
        if ($this->token != null)
                return $this->token;

        // Ищем SID в сессии.
        $token = $_SESSION['token'] ?? null;

        // Если нашли, попробуем обновить time_last в базе. 
        // Заодно и проверим, есть ли сессия там.
        if ($token != null)
        {			
                $affected_rows = $this->mSessions->edit_token($token, ['last_activity' => date('Y-m-d H:i:s')]);

                if (($affected_rows == 0) && ($this->mSessions->get($token) == null))
                        $token = null;					
        }		

        // Нет сессии? Ищем логин и md5(пароль) в куках.
        // Т.е. пробуем переподключиться.
        if ($token == null && isset($_COOKIE['login']))
        {
                $user = $this->GetByLogin($_COOKIE['login']);

                if ($user != null && $user['password'] == $_COOKIE['password'])
                        $token = $this->OpenSession($user['id_user']);
        }

        // Запоминаем в кеш.
        if ($token != null)
                $this->token = $token;

        // Возвращаем, наконец, SID.
        return $token;		
}

    //
    // Открытие новой сессии
    // результат	- SID
    //
    private function OpenSession($id_user)
    {
        // генерируем SID
        $token = $this->GenerateStr(10);

        // вставляем SID в БД
        $now = date('Y-m-d H:i:s'); 
        $session = array();
        $session['id_user'] = $id_user;
        $session['token'] = $token;
        $session['time_start'] = $now;
        $session['last_activity'] = $now;				
        $this->mSessions->add($session);

        // регистрируем сессию в PHP сессии
        $_SESSION['token'] = $token;				

        // возвращаем SID
        return $token;	
    }

    //
    // Генерация случайной последовательности
    // $length 		- ее длина
    // результат	- случайная строка
    //
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
	
    /*public function get_hash($str){
            $i = 0;
            while($i++ < 4)
                    $str = md5(md5(md5(HASH_KEY . $str)) . $str);
            return $str;
    }*/
	
    public function GetPrivs($id_user = null)
    {
        if ($id_user == null){
            $id_user = $this->GetUid();
        }
        if ($id_user == null){
            return array();
        }
        $user = $this->Get($id_user);
        $arr = $this->db->Select("SELECT " . SQL_PREFIX . "_privs.name as name FROM  " . SQL_PREFIX . "_privs2roles LEFT JOIN " . 
                                                        SQL_PREFIX . "_privs USING(id_priv) WHERE id_role = '{$user['id_role']}'");

        $privs = array();
        foreach($arr as $elem){
                $privs[] = $elem['name'];
        }
        return $privs;
    }	
	
    public function GetRole()
    {
        $pk = $this->getUid();
        $res = $this->db->select("SELECT * FROM {$this->table} LEFT JOIN roles using (id_role) WHERE id_user = :pk",
                                   ['pk' => $pk]
                                   );

        return $res[0]['name'] ?? null;
    }	
    
    public function getUserName()
    {
        $pk = $this->getUid();
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE id_user = :pk",
                                   ['pk' => $pk]
                                   );

        return $res[0]['name'] ?? null;
    }	
    
}
