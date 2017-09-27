<?php

namespace C\Client;

use Core\System;
use C\Auth_base;
use M\Auth as Model;

/**
 * Class Auth - controller to work with client authentication and authorization.
 */
class Auth extends Auth_base
{
    /**
     * Method to get authentication and authorization statuses.
     */
    public function action_login()
    {
        // Get model to work with authentication and authorization.
        $mAuth = Model::instance();
        
        if (count($_POST) > 0) {
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            // Get auth status.
            $status = $mAuth->login($login, $password);
            // Get user role.
            $role = $mAuth->getRole();

            if ($status && $role === 'user') {
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

