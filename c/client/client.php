<?php

namespace C\Client;

use C\Base;
use Core\System;
use M\Auth;

/**
 * Class Client - parent controller for other controllers of the client side.
 */
abstract class Client extends Base
{
	protected $auth;
    protected $title;
    protected $content;
    protected $params;
	protected $menu;
	protected $status;
    
    public function __construct()
    {
		$this->title = 'Наш сайт - ';
        $this->content = '';
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
			'menu' => $this->menu,
			'status' => $this->status
        ]);
         
        return $html;
    } 
}
