<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

use Core\Model;

/**
 * Description of about
 *
 * @author admin
 */
class About extends Model{
    use \Core\Traits\Singleton;
	
    protected function __construct(){
            parent::__construct();
            $this->table = 'about';
            $this->pk = 'id_info';
    }
    
    public function validationMap(){
        return [
			'table' => 'about',
			'pk' => 'id_info',
            'fields' => ['id_info', 'title', 'content', 'date', 'image_link'],
            'not_empty' => ['title', 'content'],
            'min_length' => [
                'title' => 5,
                'content' => 10
            ],
			'unique' => ['title'],
			'html_allowed' => ['content']
        ];
    }
    
}
