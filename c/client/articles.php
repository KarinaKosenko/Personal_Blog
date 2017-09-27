<?php

namespace C\Client;

use M\Articles as Model;
use Core\System;
use Core\Exceptions;

/**
 * Class Articles - controller to work with articles on the client side.
 */
class Articles extends Client
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
	
		if (isset($_SESSION['msg'])) {
			$msg = $_SESSION['msg'] . '<hr>';
			unset($_SESSION['msg']);
		}
		else{
			$msg = '';
		}
		
        $this->title .= 'главная';
        $this->content = System::template('client/v_articles.php', [
		   'start' => $all_data['start'],
		   'data' => $all_data['data'],
		   'rows' => $all_data['rows'],
		   'num_pages' => $all_data['num_pages'],
		   'cur_page' => $this->params[2],
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
        $mArticles = Model::instance($this->params[2]);
        // Get current article.
        $article = $mArticles->one($this->params[2]);
		
        if ($article === null) {
            throw new Exceptions\E404("article with id {$this->params[2]} is not found");
        }
        else {
            $this->title .= 'просмотр сообщения';
            $this->content = System::template('admin/v_one.php', [
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'date' => $article['date'],
                    'author' => $article['author']
            ]);
        }  
    }
    
}	
	
