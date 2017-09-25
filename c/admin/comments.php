<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Admin;

use M\Comments as Model;
use Core\System;
use Core\Validation;
use Core\Exceptions;
use M\Auth;

/**
 * Description of comments
 *
 * @author admin
 */
class Comments extends Admin
{
    public function action_add()
    {
        $mComments = Model::instance();
		
        if(count($_POST) > 0) {
            $author = Auth::instance()->getUserName();
            $text = $_POST['text'];
            $id_article = $this->params[2];
            $id_parent = $this->params[3] ?? 0;

            $obj = compact("author", "text", "id_article", "id_parent");

            $valid = new Validation($obj, $mComments->validationMap());
            $valid->execute('add');
			
            if($valid->good()){   
                $mComments->add($valid->cleanObj());
                $_SESSION['msg'] = 'Статья успешно добавлена.';
                header("Location: /admin/articles/one/" . $this->params[2]);
                exit();
            }
            else{
                $errors = $valid->errors();
                $msg = implode('<br>', $errors);
            }
        }
        else{
            $text = '';
            $msg = "Пожалуйста, добавьте комментарий:";
            $errors = [];
        }
    
        $this->title = 'добавление комментария';
			
        $this->content = System::template('admin/v_add_comment.php', [
            'text' => $text,
            'msg' => $msg,
        ]);
    }
    
    
    public function action_delete()
    {
        $mComments = Model::instance();
        $comment = $mComments->one($this->params[3]);

        if($comment === null){
            throw new Exceptions\E404("comment with id {$this->params[3]} is not found");
        }
        else {
            $mComments->edit($this->params[3], ['text' => 'Комментарий удален.']);
            $_SESSION['msg'] = "Статья успешно удалена.";
            header("Location: /admin/articles/one/" . $this->params[2]);
            exit();	
        }
    }
    
    
    public function action_edit()
    {
        $mComments = Model::instance();
        $comment = $mComments->one($this->params[3]);

        if($comment === null) {
            throw new Exceptions\E404("comment with id {$this->params[3]} is not found");
        }
        else {
            $this->title = 'Редактировать комментарий №' . $comment['id_comment'];
            $msg = "Пожалуйста, отредактируйте комментарий.";

            $this->content = System::template('admin/v_add_comment.php', [
                                            'text' => $comment['text'],
                                            'msg' => $msg
                                    ]);
            
            if(count($_POST) > 0) {
                $text = $_POST['text'];

                $obj = compact("text");

                $valid = new Validation($obj, $mComments->validationMap());
                $valid->execute('edit');

                if($valid->good()){   
                    $mComments->edit($this->params[3], $valid->cleanObj());
                    $_SESSION['msg'] = 'Статья успешно отредактирована.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
                else{
                    $errors = $valid->errors();
                    $msg = implode('<br>', $errors);
                }

                $this->content = System::template('admin/v_add_comment.php', [
                                'text' => $text,
                                'msg' => $msg
                        ]);		
            }	
        }
    }	

}
