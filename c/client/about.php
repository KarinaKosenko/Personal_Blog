<?php

namespace C\Client;

use M\About as Model;
use Core\System;

/**
 * Class About - controller to work with About page in the client side.
 */
class About extends Client 
{
    public function action_index()
    {
        // Get About model.
        $mModel = Model::instance();
        // Get About information.
        $about = $mModel->one(1);

        $this->title = 'главная';
        $this->content = System::template('client/v_about.php', [
            'title' => $about['title'],
			'content' => $about['content'],
         ]);
    }
}
