<?php

namespace M;

use Core\Model;
use Core\Sql;

/**
 * Class Tags - a model to work with tags.
 */
class Tags extends Model
{
    use \Core\Traits\Singleton;
    
    protected $db;
    
    protected function __construct()
    {
        $this->db = Sql::instance();
        // Determine database table.
        $this->table = 'tags';
        // Determine primary key.
        $this->pk = 'name';
        parent::__construct();
    }

    /**
     * Method returns validation rules.
     *
     * @return array
     */
    public function validationMap()
    {
        return [
            'table' => 'tags',
            'pk' => 'id_tag',
            'fields' => ['id', 'id_tag', 'name'],
        ];
    }

    /**
     * Method to search all tags of one article (by id article).
     *
     * @param $id_article
     * @return mixed
     */
    public function searchTags($id_article)
    {
        $res = $this->db->select("SELECT * FROM articles "
                . "LEFT JOIN articles2tags USING(id_article)"
                . "LEFT JOIN {$this->table} USING (id_tag) "
                . "WHERE id_article = :id_article", 
                ['id_article' => $id_article]);
                
        return $res;            
    }

    /**
     * Method to generate tags array for one article.
     *
     * @param $id_article
     * @return array
     */
    public function searchTagsNames($id_article)
    {
        $arr = [];
        $res = $this->searchTags($id_article);
        
        if (count($res) !== 0) {
            foreach ($res as $one) {
               $arr[] = $one['name'];
            }
        }
        
        return $arr;
    }

    /**
     * Method to search articles containing defined tag.
     *
     * @param $id_tag
     * @return array
     */
    public function searchArticles($id_tag)
    {
        $arr = [];
        $res = $this->db->select("SELECT * FROM {$this->table} "
                . "LEFT JOIN articles2tags USING(id_tag)"
                . "LEFT JOIN articles USING (id_article) "
                . "WHERE id_tag = :id_tag", 
                ['id_tag' => $id_tag]);
                
        if (count($res) !== 0) {
            foreach ($res as $one)
            {
               $arr[] = $one;
            }
        }
        
        return $arr;
    }

    /**
     * Method to work with user added tags.
     *
     * @param string $tags
     * @param $id_article
     * @return array|bool|string
     */
    public function getTagsFromUser(string $tags, $id_article)
    {
        // Convert tag line to array.
        $arr = explode(',', $tags);
        // Validate each tag in array.
        foreach ($arr as $one) {
            // Return validation errors.
            if ($this->tagValidation($one) !== true) {
                return $this->tagValidation($one);
            }    
        }
        // Add tags to database.
        foreach ($arr as $one) {
            $this->addTag($one, $id_article);
        }
        
        return $arr; 
    }

    /**
     * Method to delete tags from database.
     *
     * @param $name
     * @param $id_article
     * @return mixed
     */
    public function removeTag($name, $id_article)
    {
        $id_tag = $this->getTagId($name);
        
        return $this->db->delete('articles2tags', "id_article=:id_article AND id_tag=:id_tag",
                    ['id_article' => $id_article, 'id_tag' => $id_tag]);
    }

    /**
     * Get tad id by its name.
     *
     * @param $name
     * @return mixed
     */
    protected function getTagId($name)
    {
        $res = $this->db->select("SELECT * FROM {$this->table} WHERE name=:name",
            ['name' => $name]);

        if (count($res) === 0) {
            // Insert tag to database.
            return $this->add(['name' => $name]);
        }

        return $res[0]['id_tag'];
    }

    /**
     * Method checks if the article has this tag.
     *
     * @param $name
     * @param $id_article
     * @return bool|mixed
     */
    protected function checkTag($name, $id_article)
    {
        $id_tag = $this->getTagId($name);
        $res = $this->db->select("SELECT * FROM articles2tags WHERE id_tag = :id_tag",
            ['id_tag' => $id_tag]);

        foreach ($res as $one) {
            if ($one['id_article'] === $id_article) {
                return false;
            }
        }

        return $id_tag;
    }

    /**
     * Method to bind tag to the article.
     *
     * @param $name
     * @param $id_article
     */
    protected function addTag($name, $id_article)
    {
        $id_tag = $this->checkTag($name, $id_article);

        if ($id_tag !== false) {
            $this->db->insert('articles2tags',
                ['id_article' => $id_article, 'id_tag' => $id_tag]);
        }

    }

    /**
     * Method to validate tag name.
     *
     * @param $one
     * @return bool|string
     */
    protected function tagValidation($one)
    {
        $one = trim($one);
        // Check tag is not empty.
        if ($one === '') {
            return 'Тег не может быть пустой строкой.';
        }
        // Check tag minimum length.
        elseif (strlen($one) < 2) {
            return 'Длина тега не может быть менее двух символов';
        }
        // Check tag maximum length.
        elseif (strlen($one) > 40) {
            return 'Длина тега не может быть больше 20 символов';
        }
        // Check forbidden characters.
        elseif (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $one)) {
            return 'Имя тега может содержать только русс. / лат. символы, пробел, цифры и знак _';
        }
        else {
            return true;
        }
    }


}


