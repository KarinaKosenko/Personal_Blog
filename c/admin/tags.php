<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Admin;

use M\Tags as Model;
use Core\System;

/**
 * Description of tags
 *
 * @author admin
 */
class Tags extends Admin
{
    public function action_one()
    {
        $mTags = Model::instance();
        $articles = $mTags->searchArticles($this->params[2]);
        
        $this->content = System::template('admin/v_tags_one.php', [
            'name' => $articles[0]['name'],
            'articles' => $articles,
            'rows' => count($articles),
        ]);
    }
    
    public function action_add()
    {
        $mTags = Model::instance();
		
        if(count($_POST) > 0) {
            $tags = $_POST['tags'];

            if($tags !== ''){
                $addTag = $mTags->getTagsFromUser($tags, $this->params[2]);

                if(is_array($addTag)){
                    $_SESSION['msg'] = 'Теги успешно добавлены.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
                else{
                    $msg = $addTag;
                }
            }
        }
        else{
            $tags = '';
            $msg = "Пожалуйста, добавьте теги:";
        }
    
        $this->title = 'добавление тегов';
			
        $this->content = System::template('admin/v_add_tags.php', [
            'tags' => $tags,
            'msg' => $msg,
        ]);
    }
    
    
    public function action_edit() 
    {
       $mTags = Model::instance();
       $tags = $mTags->searchTagsNames($this->params[2]);
       
       
       if(count($_POST) > 0) {
            $tags_new = $_POST['tags'];
            if($tags_new !== ''){
                $tags_new = $mTags->getTagsFromUser($tags_new, $this->params[2]);
                if(!is_array($tags_new)){
                    $msg = $tags_new;
                }
                else{
                    $arr = array_diff($tags, $tags_new);
                    foreach($arr as $one){
                        $mTags->removeTag($one, $this->params[2]);
                    }
                    
                    $_SESSION['msg'] = 'Теги успешно отредактированы.';
                    header("Location: /admin/articles/one/" . $this->params[2]);
                    exit();
                }
            }
            else{
                foreach($tags as $one){
                    $mTags->removeTag($one, $this->params[2]);
                }
               
                $_SESSION['msg'] = 'Теги успешно удалены.';
                header("Location: /admin/articles/one/" . $this->params[2]);
                exit();
            }
        }
        else{
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
