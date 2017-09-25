<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Model; 

/**
 * Description of comments
 *
 * @author admin
 */
class Comments extends Model{
    use \Core\Traits\Singleton;
	
    protected function __construct(){
            parent::__construct();
            $this->table = 'comments';
            $this->pk = 'id_comment';
    }
    
    public function validationMap(){
        return [
			'table' => 'comments',
			'pk' => 'id_comment',
            'fields' => ['id_comment', 'id_parent', 'id_article', 'date', 'author', 'text'],
            'not_empty' => ['text', 'author'],
            'min_length' => [
                'text' => 10
            ],
			'unique' => [],
			'html_allowed' => []
        ];
    }
    
    
    public function articleComments($id_article){
        return $this->db->select("SELECT * FROM comments WHERE id_article = :id_article", 
                ['id_article' => $id_article]);
    }
    
    
    public function getCommentsArray($id_article)
    {
        $_comments = [];
        $result = $this->articleComments($id_article);

        foreach($result as $one){
            $_comments[$one['id_comment']] = $one;
        }
        
        return $_comments;
    }
    
    
    public function buildTree($id_article)
    {
        $data = $this->getCommentsArray($id_article);
        
        if(count($data) === 0){
            return false;
        }
        else{
            $tree = [];

            foreach($data as $id => &$row){
                if(empty($row['id_parent'])){
                    $tree[$id] = &$row;
                }
                else{
                    $data[$row['id_parent']]['childs'][$id] = &$row;
                }
            }

            return $tree;
        }
    }
    
    
    public function getCommentsTemplate($comments)
    {
        if(!$comments){
            return false;
        }
        else{
            $html = '';
            foreach($comments as $comment){
                ob_start();
                include 'v/admin/v_comments.php';         
                $html .= ob_get_clean();
            }

            return $html;
        }
    }
    
    
    
  
}
