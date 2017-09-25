<?php

namespace C\Admin;

use C\Base;
use Core\System;
use M\Auth;
use M\Articles;

abstract class Admin extends Base{
    protected $auth;
    protected $role;
    protected $title;
    protected $content;
    protected $params;
    protected $popular_articles;
    protected $archive;
    
    public function __construct()
    {
        $this->auth = Auth::instance()->isAuth();
        $this->role = Auth::instance()->getRole();
        $mArticles = Articles::instance();
        $comments_cnt = $mArticles->commentsCounter();
       
        $pop_articles = []; 
        foreach ($comments_cnt as $one){
            $pop_articles[] = $mArticles->one($one['article']);
        }
        
        if(!$this->auth || $this->role !== 'admin'){
            header("Location: /admin/auth/login");
            exit();
        }
        
        $this->title = 'Наш сайт - ';
        $this->content = '';
        $this->archive = System::template('admin/v_archive_side.php');
        $this->popular_articles = System::template('admin/v_popular_articles.php',
                ['articles' => $pop_articles]);
    }
	
	
    public function show404()
    {
        header("HTTP/1.1 404 Not Found");
        $this->title .= 'ошибка 404'; 
        $this->content = System::template('client/v_404.php');
    }
	
	
    public function render()
    {
        $html = System::template('admin/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
            'popular_articles' => $this->popular_articles,
            'archive' => $this->archive,
            
         ]);
         
        return $html;
    } 
}