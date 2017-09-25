<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Admin;

use M\About as Model;
use Core\System;
use Core\Validation;
use Core\Exceptions;

/**
 * Description of about
 *
 * @author admin
 */
class About extends Admin
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
            
        $this->content = System::template('admin/v_about.php', [
                'title' => $about['title'],
			'content' => $about['content'],
         ]);
    }
    
    
    public function action_edit()
    {
        $mModel = Model::instance();
        $about = $mModel->one($this->params[2]);

        if($about === null) {
            throw new Exceptions\E404("information with id {$this->params[2]} is not found");
        }
        else {
            $this->title = 'Редактировать страницу';
            $msg = "Пожалуйста, отредактируйте страницу.";

            $this->content = System::template('admin/v_add.php', [
                                            'title' => $about['title'],
                                            'content' => $about['content'],
                                            'msg' => $msg
                                    ]);


            if(count($_POST) > 0) {
                $title = $_POST['title'];
                $content = $_POST['content'];

                $obj = compact("title", "content");

                $valid = new Validation($obj, $mModel->validationMap());
                $valid->execute('edit');

                if($valid->good()){   
                    $mModel->edit($this->params[2], $valid->cleanObj());
                    $_SESSION['msg'] = 'Страница успешно отредактирована.';
                    header("Location: /admin/about");
                    exit();
                }
                else{
                    $errors = $valid->errors();
                    $msg = implode('<br>', $errors);
                }

                $this->content = System::template('admin/v_add.php', [
                                'title' => $title,
                                'content' => $content,
                                'msg' => $msg
                        ]);		
            }	
        }
    }	
}
