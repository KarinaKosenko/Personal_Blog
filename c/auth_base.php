<?php

namespace C;

use Core\System;
use Core\Sql;
use M\Auth as Model;

abstract class Auth_base extends Base
{
    protected $title;
    protected $content;
    protected $params;
    protected $menu;
    protected $msg;
    protected $db;
	
	
    public function __construct()
    {
	$this->db = Sql::instance();
        $this->title = 'Наш сайт - ';
        $this->content = '';
	$this->msg = 'Введите логин и пароль:';	
    }
	
    public function render()
    {
        $html = System::template('client/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
         ]);

        return $html;
    } 
	
    public abstract function action_login();

    public function action_logout()
    {
        Model::instance()->logout();

        header("Location: /articles");
        exit();
    }
}