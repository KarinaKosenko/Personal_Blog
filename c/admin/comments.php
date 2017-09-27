<?php

namespace C\Admin;

use M\Comments as Model;
use Core\System;
use Core\Validation;
use Core\Exceptions;
use M\Auth;

/**
 * Class Comments - controller to work with comment in the admin panel.
 */
class Comments extends Admin
{
    /**
     * Method for comments adding.
     */
    public function action_add()
    {
        // Get a model to work with comments.
        $mComments = Model::instance();
		
        if (count($_POST) > 0) {
            // Get data for validation.
            $author = Auth::instance()->getUserName();
            $text = $_POST['text'];
            $id_article = $this->params[2];
            $id_parent = $this->params[3] ?? 0;
            $obj = compact("author", "text", "id_article", "id_parent");
            // Data validation.
            $valid = new Validation($obj, $mComments->validationMap());
            $valid->execute('add');
			// Check validation status.
            if ($valid->good()) {
                // Insert record into a database.
                $mComments->add($valid->cleanObj());
                $_SESSION['msg'] = 'Статья успешно добавлена.';
                header("Location: /admin/articles/one/" . $this->params[2]);
                exit();
            }
            else {
                // Get validation errors.
                $errors = $valid->errors();
                $msg = implode('<br>', $errors);
            }
        }
        else {
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

    /**
     * Method for comments deleting.
     *
     * @throws Exceptions\E404
     */
    public function action_delete()
    {
        // Get a model to work with comments.
        $mComments = Model::instance();
        // Get chosen comment.
        $comment = $mComments->one($this->params[3]);

        if ($comment === null) {
            throw new Exceptions\E404("comment with id {$this->params[3]} is not found");
        }
        else {
            // Update comment in the database.
            $mComments->edit($this->params[3], [
                'text' => 'Комментарий удален.'
            ]);
            $_SESSION['msg'] = "Статья успешно удалена.";
            header("Location: /admin/articles/one/" . $this->params[2]);
            exit();	
        }
    }

    /**
     * Method for comments edition.
     *
     * @throws Exceptions\E404
     */
    public function action_edit()
    {
        // Get a model to work with comments.
        $mComments = Model::instance();
        // Get chosen comment.
        $comment = $mComments->one($this->params[3]);

        if ($comment === null) {
            throw new Exceptions\E404("comment with id {$this->params[3]} is not found");
        }
        else {
            $this->title = 'Редактировать комментарий №' . $comment['id_comment'];
            $msg = "Пожалуйста, отредактируйте комментарий.";
            $this->content = System::template('admin/v_add_comment.php', [
                                            'text' => $comment['text'],
                                            'msg' => $msg
                                    ]);
            
            if (count($_POST) > 0) {
                // Get data for validation.
                $text = $_POST['text'];
                $obj = compact("text");
                // Data validation.
                $valid = new Validation($obj, $mComments->validationMap());
                $valid->execute('edit');
                // Check validation status.
                if ($valid->good()) {
                    // Update record in the database.
                    $mComments->edit($this->params[3], $valid->cleanObj());
                    $_SESSION['msg'] = 'Статья успешно отредактирована.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
                else {
                    // Get validation errors.
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


