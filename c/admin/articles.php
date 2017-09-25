<?php

namespace C\Admin;

use M\Articles as Model;
use M\Tags;
use M\Auth;
use M\Images;
use M\Comments;
use M\Smiles;
use Core\System;
use Core\Validation;
use Core\Exceptions;

class Articles extends Admin{
    
    public function action_page(){
        $mMessages = Model::instance($this->params[2]);
        $all_data = $mMessages->getData();
        $smiles = Smiles::instance();
		
		if(isset($_SESSION['msg'])){
			$msg = $_SESSION['msg'] . '<hr>';
			unset($_SESSION['msg']);
		}
		else{
			$msg = '';
		}
		
        $this->title .= 'главная';
            
        $this->content = System::template('admin/v_articles.php', [
		   'start' => $all_data['start'],
		   'data' => $all_data['data'],
		   'rows' => $all_data['rows'],
		   'num_pages' => $all_data['num_pages'],
		   'cur_page' => $this->params[2],
		   'smile' => $smiles
         ]);
    }    
    
	
    public function action_one(){
        $mMessages = Model::instance();
        $article = $mMessages->one($this->params[2]);
        $tags = Tags::instance()->searchTags($this->params[2]);
        $tree = Comments::instance()->buildTree($this->params[2]);
        $smiles = Smiles::instance();
        
        $all_comments = Comments::instance()->getCommentsTemplate($tree);
         
         if(count($tags) === 1 && $tags[0]['id_tag'] == null){
            $tag_string = "<a href=/admin/tags/add/" . $this->params[2] . ">Добавить теги</a>";
        }
        else{
           $str = '';
            foreach($tags as $one){
                $str .= "<a href =/admin/tags/one/" . $one['id_tag'] . ">" . $one['name'] . "</a>" . ', ';
            }
            $tag_string = substr($str, 0, -2) . ".<br><a href=/admin/tags/edit/" . $this->params[2] . ">Редактировать теги</a>";
        }
        
        if($article === null) {
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else{
            if(!$all_comments){
                $comments = '';
            }
            else{
                $comments = System::template('admin/v_comments_wrap.php' , [
                    'comments' => $all_comments,
                    'id_article' => $this->params[2],
                ]);
            }
            $this->title .= 'просмотр сообщения';

            $this->content = System::template('admin/v_one.php', [
                    'title' => $article['title'],
                    'content' => $smiles->smile($article['content']),
                    'image_link' => $article['image_link'],
                    'date' => $article['date'],
                    'author' => $article['author'],
                    'id_article' => $article['id_article'],
                    'str' => $tag_string,
                    'comments' => $comments,
            ]);
            
        }
    }
    
   
   public function action_add()
    {
        $mMessages = Model::instance();
        $mTags = Tags::instance();
        $mManager = Images::instance();
		
        if(isset($_FILES['imgfile']) && !empty($_FILES['imgfile']['name'])){
                $result = $mManager->upload_file($_FILES['imgfile'], $_FILES['imgfile']['name']);

                if(isset($result['error'])){
                    echo $result['error'];
                }
                else{
                   echo $result;
                }
            }
        
        if(count($_POST) > 0) {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image_link = $_POST['image_link'];
            $author = Auth::instance()->getUserName();
            $date = date('Y-m-d');

            $obj = compact("title", "content", "image_link", "author", "date");

            $valid = new Validation($obj, $mMessages->validationMap());
            $valid->execute('add');
			
            if($valid->good()){   
                $mMessages->add($valid->cleanObj());
                $_SESSION['msg'] = 'Статья успешно добавлена.';
                header("Location: /admin/articles");
                exit();
            }
            else{
                $errors = $valid->errors();
                $msg = implode('<br>', $errors);
            }
        }
        else{
            $title = '';
            $content = '';
            $image_link = '';
            $tags = '';
            $msg = "Пожалуйста, добавьте статью:";
            $errors = [];
        }
    
        $this->title = 'добавление статьи';
			
        $this->content = System::template('admin/v_add.php', [
            'title' => $title,
            'content' => $content,
            'image_link' => $image_link,
            'msg' => $msg,
        ]);
    }
	
	
    public function action_delete()
    {
        $mMessages = Model::instance();
        $article = $mMessages->one($this->params[2]);

        if($article === null){
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else {
            $mMessages->delete($this->params[2]);
            $_SESSION['msg'] = "Статья успешно удалена.";
            header("Location: /admin/articles");
            exit();	
        }
    }
	
	
    public function action_edit()
    {
        $mMessages = Model::instance();
        $mManager = Images::instance();
        $article = $mMessages->one($this->params[2]);

        if($article === null) {
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else {
            $this->title = 'Редактировать новость №' . $article['id_article'];
            $msg = "Пожалуйста, отредактируйте новость.";

            $this->content = System::template('admin/v_add.php', [
                                            'title' => $article['title'],
                                            'content' => $article['content'],
                                            'image_link' => $article['image_link'],
                                            'msg' => $msg
                                    ]);

            $old_title = $article['title'];			


             if(isset($_FILES['imgfile']) && !empty($_FILES['imgfile']['name'])){
                $result = $mManager->upload_file($_FILES['imgfile'], $_FILES['imgfile']['name']);

                if(isset($result['error'])){
                    echo $result['error'];
                }
                else{
                   echo $result;
                }
            }
            
            if(count($_POST) > 0) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $image_link = $_POST['image_link'];
                $id_article = $article['id_article'];

                $obj = compact("title", "content", "image_link", "id_article");

                $valid = new Validation($obj, $mMessages->validationMap());
                $valid->execute('edit');

                if($valid->good()){   
                    $mMessages->edit($this->params[2], $valid->cleanObj());
                    $_SESSION['msg'] = 'Статья успешно отредактирована.';
                    header("Location: /admin/articles");
                    exit();
                }
                else{
                    $errors = $valid->errors();
                    $msg = implode('<br>', $errors);
                }

                $this->content = System::template('admin/v_add.php', [
                                'title' => $title,
                                'content' => $content,
                                'image_link' => $image_link,
                                'msg' => $msg
                        ]);		
            }	
        }
    }	
}	
	
