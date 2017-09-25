<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Client;

use M\About as Model;
use Core\System;

/**
 * Description of about
 *
 * @author admin
 */
class About extends Client 
{
    public function action_index(){
        $mModel = Model::instance();
        $about = $mModel->one(1);
		
		if(isset($_SESSION['msg'])){
			$msg = $_SESSION['msg'] . '<hr>';
			unset($_SESSION['msg']);
		}
		else{
			$msg = '';
		}
		
	    $this->title = 'главная';
            
        $this->content = System::template('client/v_about.php', [
                'title' => $about['title'],
			'content' => $about['content'],
         ]);
    }
}
