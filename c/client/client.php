<?php

namespace C\Client;

use C\Base;
use Core\System;
use M\Auth;

abstract class Client extends Base{
	protected $auth;
    protected $title;
    protected $content;
    protected $params;
	protected $menu;
	protected $status;
    
    public function __construct(){
		$this->auth = Auth::instance()->isAuth();
		
		if(!$this->auth) {
			
		}
        else{
			
		}
		
		$this->title = 'Наш сайт - ';
        $this->content = '';
    }

	
    public function render(){
        $html = System::template('client/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
			'menu' => $this->menu,
			'status' => $this->status
         ]);
         
        return $html;
    } 
}
