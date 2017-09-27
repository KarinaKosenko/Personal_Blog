<?php

namespace C;

use Core\System;
use Core\Sql;
use M\Auth as Model;

/**
 * Class Auth_base - parent class to work with authentification.
 */
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

    /**
     * Method to generate HTML-document (view).
     *
     * @return string
     */
    public function render()
    {
        $html = System::template('client/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
         ]);

        return $html;
    }

    /**
     * Abstract method to work with authentication and authorization statuses.
     */
    public abstract function action_login();

    /**
     * Method to log out.
     */
    public function action_logout()
    {
        Model::instance()->logout();
        header("Location: /articles");
        exit();
    }
}