<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Client;

use Core\System;
use C\Auth_base;
use M\Auth as Model;
/**
 * Description of auth
 *
 * @author admin
 */
class Auth extends Auth_base {
    
    public function action_login()
    {
        $mAuth = Model::instance();
        
        if(count($_POST) > 0){
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);

            $status = $mAuth->login($login, $password);
            $role = $mAuth->getRole();

            if($status && $role === 'user'){
                header("Location: /articles");
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
