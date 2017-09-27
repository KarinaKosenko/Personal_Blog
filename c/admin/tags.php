<?php

namespace C\Admin;

use M\Tags as Model;
use Core\System;

/**
 * Class Tags - controller to work with tags in the admin-panel.
 */
class Tags extends Admin
{
    /**
     * Method returns a list of the articles by tag.
     */
    public function action_one()
    {
        // Get a model to work with tags.
        $mTags = Model::instance();
        $articles = $mTags->searchArticles($this->params[2]);
        
        $this->content = System::template('admin/v_tags_one.php', [
            'name' => $articles[0]['name'],
            'articles' => $articles,
            'rows' => count($articles),
        ]);
    }

    /**
     * Method for tags adding.
     */
    public function action_add()
    {
        // Get a model to work with tags.
        $mTags = Model::instance();
		
        if (count($_POST) > 0) {
            $tags = $_POST['tags'];
            // Check tagline is not empty.
            if ($tags !== '') {
                $addTag = $mTags->getTagsFromUser($tags, $this->params[2]);
                if (is_array($addTag)) {
                    $_SESSION['msg'] = 'Теги успешно добавлены.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
                else {
                    $msg = $addTag;
                }
            }
        }
        else {
            $tags = '';
            $msg = "Пожалуйста, добавьте теги:";
        }
    
        $this->title = 'добавление тегов';
        $this->content = System::template('admin/v_add_tags.php', [
            'tags' => $tags,
            'msg' => $msg,
        ]);
    }

    /**
     * Method for tags editing.
     */
    public function action_edit() 
    {
        // Get a model to work with tags.
        $mTags = Model::instance();
        $tags = $mTags->searchTagsNames($this->params[2]);
       
        if (count($_POST) > 0) {
            $tags_new = $_POST['tags'];
            // Check tagline is not empty.
            if ($tags_new !== '') {
                $tags_new = $mTags->getTagsFromUser($tags_new, $this->params[2]);
                if (!is_array($tags_new)) {
                    $msg = $tags_new;
                }
                else {
                    // Check a difference between old and new tags.
                    $arr = array_diff($tags, $tags_new);
                    // Remove unnecessary old tags.
                    foreach($arr as $one) {
                        $mTags->removeTag($one, $this->params[2]);
                    }
                    $_SESSION['msg'] = 'Теги успешно отредактированы.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
            }
            else {
                foreach($tags as $one){
                    $mTags->removeTag($one, $this->params[2]);
                }
               
                $_SESSION['msg'] = 'Теги успешно удалены.';
                header("Location: /admin/articles/one/" . $this->params[2]);
                exit();
            }
        }
        else {
            $msg = "Пожалуйста, добавьте/удалите теги (через запятую):";
        }
     
        $tags = implode(',', $tags);
        $this->title = 'редактирование тегов';
        $this->content = System::template('admin/v_add_tags.php', [
            'tags' => $tags,
            'msg' => $msg,
        ]);  
    }
    
}

