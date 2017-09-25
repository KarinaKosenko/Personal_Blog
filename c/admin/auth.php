<?php

namespace C\Admin;

use Core\System;
use C\Auth_base;
use M\Auth as Model;

class Auth extends Auth_base
{
    public function action_login()
    {
        $mAuth = Model::instance();
        
        if(count($_POST) > 0){
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);

            $status = $mAuth->login($login, $password);
            $role = $mAuth->getRole();

            if($status && $role === 'admin'){
                header("Location: /admin/articles");
                exit();
            }
            else {
                $this->msg = 'Неверный логин или пароль!';
            }
        }

        $this->title .= 'авторизация';

        $this->content = System::template('client/v_login.php', [
                        'msg' => $this->msg,
         ]);
    }
	
}