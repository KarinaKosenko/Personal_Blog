<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Model;
use Core\Sql;
/**
 * Description of tags
 *
 * @author admin
 */
class Tags extends Model
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    protected function __construct()
    {
        $this->db = Sql::instance();
        $this->table = 'tags';
        $this->pk = 'name';
        parent::__construct();
    }
    
    
    public function validationMap()
    {
        return [
            'table' => 'tags',
            'pk' => 'id_tag',
            'fields' => ['id', 'id_tag', 'name'],
        ];
    }
    
    
    public function searchTags($id_article)
    {
        $res = $this->db->select("SELECT * FROM articles "
                . "LEFT JOIN articles2tags USING(id_article)"
                . "LEFT JOIN {$this->table} USING (id_tag) "
                . "WHERE id_article = :id_article", 
                ['id_article' => $id_article]);
                
        return $res;            
    }
    
    
    public function searchTagsNames($id_article)
    {
        $arr = [];
        $res = $this->searchTags($id_article);
        
        if(count($res) !== 0){
            foreach($res as $one){
               $arr[] = $one['name'];
            }
        }
        
        return $arr;
    }
    
    
    public function searchArticles($id_tag)
    {
        $arr = [];
        $res = $this->db->select("SELECT * FROM {$this->table} "
                . "LEFT JOIN articles2tags USING(id_tag)"
                . "LEFT JOIN articles USING (id_article) "
                . "WHERE id_tag = :id_tag", 
                ['id_tag' => $id_tag]);
                
        if(count($res) !== 0){
            foreach($res as $one){
               $arr[] = $one;
            }
        }
        
        return $arr;
    }
    
    
    public function getTagId($name)
    {
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE name=:name", 
                ['name' => $name]);
        
        if(count($res) === 0){
            return $this->add(['name' => $name]);
        }
       
        return $res[0]['id_tag'];
    }
    
    
    public function checkTag($name, $id_article)
    {
        $id_tag = $this->getTagId($name);
        
        $res = $this->db->select("SELECT * FROM articles2tags WHERE id_tag = :id_tag", 
                ['id_tag' => $id_tag]);
        
        foreach($res as $one){
            if($one['id_article'] == $id_article){
                return false;
            }
        }
        
        return $id_tag;
    }


    public function addTag($name, $id_article)
    {
        $id_tag = $this->checkTag($name, $id_article);
        
        if($id_tag !== false){
            $this->db->insert('articles2tags', 
                    ['id_article' => $id_article, 'id_tag' => $id_tag]);
        }
        
    }
    
    private function tagValidation($one)
    {
        $one = trim($one);
            
        if($one === ''){
            return 'Тег не может быть пустой строкой.';
        }
        elseif(strlen($one) < 2){
            return 'Длина тега не может быть менее двух символов';
        }
        elseif(strlen($one) > 50){
            return 'Длина тега не может быть больше 20 символов';
        }
        elseif(preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $one)){
            return 'Имя тега может содержать только русс. / лат. символы, пробел, цифры и знак _';
        }
        else{
            return true;
        }
    }
    
    
    public function getTagsFromUser(string $tags, $id_article)
    {
        $arr = explode(',', $tags);
        
        foreach($arr as $one){
            if($this->tagValidation($one) !== true){
                return $this->tagValidation($one);
            }    
        }
         
        foreach($arr as $one){
            $this->addTag($one, $id_article);
        }
        
        return $arr; 
    }
    
    
    public function removeTag($name, $id_article)
    {
        $id_tag = $this->getTagId($name);
        
        return $this->db->delete('articles2tags', "id_article=:id_article AND id_tag=:id_tag",
                    ['id_article' => $id_article, 'id_tag' => $id_tag]);
        
    }
    
}
