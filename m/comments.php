<?php

namespace M;

use Core\Model;

/**
 * Class Comments - a model to work with comments.
 */
class Comments extends Model
{
    use \Core\Traits\Singleton;
	
    protected function __construct()
    {
        parent::__construct();
        // Set table from database.
        $this->table = 'comments';
        // Set primary key of defined table.
        $this->pk = 'id_comment';
    }

    /**
     * Method determines validation rules.
     *
     * @return array
     */
    public function validationMap()
    {
        return
        [
			'table' => 'comments',
			'pk' => 'id_comment',
            'fields' => ['id_comment', 'id_parent', 'id_article', 'date', 'author', 'text'],
            'not_empty' => ['text', 'author'],
            'min_length' => [
                'text' => 10,
            ],
			'unique' => [],
			'html_allowed' => []
        ];
    }

    /**
     * Method returns all comments of the article.
     *
     * @param $id_article
     * @return mixed
     */
    protected function articleComments($id_article)
    {
        return $this->db->select("SELECT * FROM comments WHERE id_article = :id_article", [
            'id_article' => $id_article
        ]);
    }

    /**
     * Method converts article comments to array.
     *
     * @param $id_article
     * @return array
     */
    public function getCommentsArray($id_article)
    {
        $_comments = [];
        $result = $this->articleComments($id_article);

        foreach ($result as $one) {
            $_comments[$one['id_comment']] = $one;
        }
        
        return $_comments;
    }

    /**
     * Method builds a comment tree for one article.
     *
     * @param $id_article
     * @return array|bool
     */
    public function buildTree($id_article)
    {
        $data = $this->getCommentsArray($id_article);
        
        if (count($data) === 0) {
            return false;
        }
        else {
            $tree = [];
            foreach ($data as $id => &$row) {
                if (empty($row['id_parent'])) {
                    $tree[$id] = &$row;
                }
                else {
                    $data[$row['id_parent']]['childs'][$id] = &$row;
                }
            }

            return $tree;
        }
    }

    /**
     * Method builds template for comments.
     *
     * @param $comments
     * @return bool|string
     */
    public function getCommentsTemplate($comments)
    {
        if (!$comments) {
            return false;
        }
        else {
            $html = '';
            foreach ($comments as $comment) {
                ob_start();
                include 'v/admin/v_comments.php';         
                $html .= ob_get_clean();
            }

            return $html;
        }
    }
  
}


