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

/**
 * Class Articles - controller to work with articles in the admin panel.
 */
class Articles extends Admin
{
    /**
     * Method returns a list of all articles.
     */
    public function action_page()
    {
        // Get a model to work with articles.
        $mArticles = Model::instance($this->params[2]);
        // Get all articles.
        $all_data = $mArticles->getData();
        // Get a model to work with smiles.
        $smiles = Smiles::instance();
		
		if (isset($_SESSION['msg'])) {
			$msg = $_SESSION['msg'];
			unset($_SESSION['msg']);
		}
		else {
			$msg = '';
		}
		
        $this->title .= 'главная';
        $this->content = System::template('admin/v_articles.php', [
		   'start' => $all_data['start'],
		   'data' => $all_data['data'],
		   'rows' => $all_data['rows'],
		   'num_pages' => $all_data['num_pages'],
		   'cur_page' => $this->params[2],
		   'smile' => $smiles,
            'msg' => $msg,
         ]);
    }

    /**
     * One article page.
     *
     * @throws Exceptions\E404
     */
    public function action_one()
    {
        // Get a model to work with articles.
        $mArticles = Model::instance();
        // Get current article.
        $article = $mArticles->one($this->params[2]);
        // Search article's tags.
        $tags = Tags::instance()->searchTags($this->params[2]);
        // Build a comment tree for current article.
        $tree = Comments::instance()->buildTree($this->params[2]);
        // Get a model to work with smiles.
        $smiles = Smiles::instance();
        // Get all comment for current article using comment tree.
        $all_comments = Comments::instance()->getCommentsTemplate($tree);
         
        // Work with article's tags.
        if (count($tags) === 1 && $tags[0]['id_tag'] == null) {
            $tag_string = "<a href=/admin/tags/add/" . $this->params[2] . ">Добавить теги</a>";
        }
        else {
           $str = '';
            foreach ($tags as $one) {
                $str .= "<a href =/admin/tags/one/" . $one['id_tag'] . ">" . $one['name'] . "</a>" . ', ';
            }
            $tag_string = substr($str, 0, -2) . ".<br><a href=/admin/tags/edit/" . $this->params[2] . ">Редактировать теги</a>";
        }
        
        if ($article === null) {
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else {
            // Work with article's comments.
            if (!$all_comments) {
                $comments = '';
            }
            else {
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

    /**
     * Method for article adding.
     */
    public function action_add()
    {
        // Get a model to work with articles.
        $mArticles = Model::instance();
        // Get a model to work with images.
        $mManager = Images::instance();
		
        if (isset($_FILES['imgfile']) && !empty($_FILES['imgfile']['name'])) {
            // Image uploading.
            $result = $mManager->upload_file($_FILES['imgfile'], $_FILES['imgfile']['name']);
            // Get result of image uploading (error or success).
            if (isset($result['error'])) {
                echo $result['error'];
            }
            else {
               echo $result;
            }
        }
        
        if (count($_POST) > 0) {
            // Get parameters for validation.
            $title = $_POST['title'];
            $content = $_POST['content'];
            $image_link = $_POST['image_link'];
            $author = Auth::instance()->getUserName();
            $date = date('Y-m-d');
            $obj = compact("title", "content", "image_link", "author", "date");
            // Data validation.
            $valid = new Validation($obj, $mArticles->validationMap());
            $valid->execute('add');
			// Check validation status.
            if ($valid->good()) {
                // Add article to database.
                $mArticles->add($valid->cleanObj());
                $_SESSION['msg'] = 'Статья успешно добавлена.';
                header("Location: /admin/articles");
                exit();
            }
            else {
                // Get validation errors.
                $errors = $valid->errors();
                $msg = implode('<br>', $errors);
            }
        }
        else {
            $title = '';
            $content = '';
            $image_link = '';
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

    /**
     * Method for articles deleting.
     *
     * @throws Exceptions\E404
     */
    public function action_delete()
    {
        // Get a model to work with articles.
        $mArticles = Model::instance();
        // Get chosen article.
        $article = $mArticles->one($this->params[2]);

        if ($article === null) {
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else {
            $mArticles->delete($this->params[2]);
            $_SESSION['msg'] = "Статья успешно удалена.";
            header("Location: /admin/articles");
            exit();	
        }
    }

    /**
     * Method for articles editing.
     *
     * @throws Exceptions\E404
     */
    public function action_edit()
    {
        // Get a model to work with articles.
        $mArticles = Model::instance();
        // Get a model to work with images.
        $mManager = Images::instance();
        // Get chosen article.
        $article = $mArticles->one($this->params[2]);

        if ($article === null) {
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

            if (isset($_FILES['imgfile']) && !empty($_FILES['imgfile']['name'])) {
                // Image uploading.
                $result = $mManager->upload_file($_FILES['imgfile'], $_FILES['imgfile']['name']);
                // Get result of image uploading.
                if (isset($result['error'])) {
                    echo $result['error'];
                }
                else {
                   echo $result;
                }
            }
            
            if (count($_POST) > 0) {
                // Get data for validation.
                $title = $_POST['title'];
                $content = $_POST['content'];
                $image_link = $_POST['image_link'];
                $id_article = $article['id_article'];
                $obj = compact("title", "content", "image_link", "id_article");
                // Data validation.
                $valid = new Validation($obj, $mArticles->validationMap());
                $valid->execute('edit');
                // Check validation results.
                if ($valid->good()) {
                    // Update article in the database.
                    $mArticles->edit($this->params[2], $valid->cleanObj());
                    $_SESSION['msg'] = 'Статья успешно отредактирована.';
                    header("Location: /admin/articles");
                    exit();
                }
                else {
                    // Get validation errors.
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
	
