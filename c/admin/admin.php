<?php

namespace C\Admin;

use C\Base;
use Core\System;
use M\Auth;
use M\Articles;

/**
 * Class Admin - parent controller for other controllers of the admin panel.
 */
abstract class Admin extends Base
{
    protected $auth;
    protected $role;
    protected $title;
    protected $content;
    protected $params;
    protected $popular_articles;
    protected $archive;
    
    public function __construct()
    {
        // Get authentification status.
        $this->auth = Auth::instance()->isAuth();
        // Get authorization status.
        $this->role = Auth::instance()->getRole();
        // Get model to work with articles.
        $mArticles = Articles::instance();
        // Get model to work with comments.
        $comments_cnt = $mArticles->commentsCounter();
        // Get popular articles.
        $pop_articles = []; 
        foreach ($comments_cnt as $one){
            $pop_articles[] = $mArticles->one($one['article']);
        }
        // Check authentification and authorization statuses.
        if (!$this->auth || $this->role !== 'admin') {
            header("Location: /admin/auth/login");
            exit();
        }
        
        $this->title = 'Наш сайт - ';
        $this->content = '';
        $this->archive = System::template('admin/v_archive_side.php');
        $this->popular_articles = System::template('admin/v_popular_articles.php', [
            'articles' => $pop_articles
        ]);
    }

    /**
     * Method to generate error 404.
     */
    public function show404()
    {
        header("HTTP/1.1 404 Not Found");
        $this->title .= 'ошибка 404'; 
        $this->content = System::template('client/v_404.php');
    }

    /**
     * Method to generate HTML-document (view).
     *
     * @return string
     */
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