<?php

namespace M;

use Core\Model;

/**
 * Class About - a model to work with About information.
 */
class About extends Model
{
    use \Core\Traits\Singleton;
	
    protected function __construct()
    {
        // Set table and primary key to work with database.
        parent::__construct();
        $this->table = 'about';
        $this->pk = 'id_info';
    }

    // Determine validation rules.
    public function validationMap()
    {
        return [
			'table' => 'about',
			'pk' => 'id_info',
            'fields' => ['id_info', 'title', 'content', 'date', 'image_link'],
            'not_empty' => ['title', 'content'],
            'min_length' => [
                'title' => 5,
                'content' => 10,
            ],
			'unique' => ['title'],
			'html_allowed' => ['content'],
        ];
    }
    
}

