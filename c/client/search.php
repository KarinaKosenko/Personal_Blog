<?php

namespace C\Client;

use M\Search as Model;
use Core\System;
use Core\Exceptions;

/**
 * Class Search - controller for articles searching on the client side.
 */
class Search extends Client
{
    /**
     * Searching page.
     *
     * @throws Exceptions\E404
     */
    public function action_page()
    {
        // Get a model to work with searching.
        $mSearch = Model::instance($this->params[2]);
        // Check searching-query existence.
        if (!isset($_GET['query'])) {
             throw new Exceptions\E404("search query is not found");
        }
        else {
            $link = $_GET['query'];
            $search = $mSearch->SearchData($_GET['query']);
            $this->title .= 'поиск';
            $this->content = System::template('client/v_search.php', [
               'start' => $search['start'],
               'data' => $search['data'],
               'rows' => $search['rows'],
               'num_pages' => $search['num_pages'],
               'cur_page' => $this->params[2],
               'link' => $link,
             ]);
        }
    }
}


