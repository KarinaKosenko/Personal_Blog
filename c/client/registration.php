<?php

namespace C\Client;

use M\User as Model;
use Core\System;
use Core\Validation;

class Registration extends Client{
    
    public function action_index()
    {
        $mReg = Model::instance();
        
        if(count($_POST) > 0) {
            $name = $_POST['name'];
            $login = $_POST['login'];
            $password = $_POST['password'];
            $repeat_password = $_POST['repeat_password'];

            $obj = compact("name", "login", "password");

            $valid = new Validation($obj, $mReg->validationMap());
            $valid->execute('add');

            if($valid->good()){
                if($password !== $repeat_password){
                    $msg = 'Пожалуйста, проверьте правильность ввода пароля: пароли не совпадают.';
                }
                else{
                    $arr = $mReg->hash_password($valid->cleanObj());
                    $mReg->add($arr);
                    $_SESSION['msg'] = 'Пользователь успешно зарегистрирован. Теперь вы можете авторизоваться на сайте.';
                    header("Location: /articles");
                    exit();
                }
            }
            else{
                $errors = $valid->errors();
                $msg = implode('<br>', $errors);
            }
        }
        else{
            $name = '';
            $login = '';
            $password = '';
            $repeat_password = '';
            $msg = "Пожалуйста, заполните все поля.";
            $errors = [];
        }
    
        $this->title .= 'регистрация нового пользователя';
		
		
        $this->content = System::template('client/v_reg.php', [
            'name' => $name,
            'login' => $login,
            'password' => $password,
            'repeat_password' => $repeat_password,
            'msg' => $msg
        ]);
    }    
     
}	
	
