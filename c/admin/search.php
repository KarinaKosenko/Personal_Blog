<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Admin;

use M\Search as Model;
use Core\System;
use Core\Exceptions;

/**
 * Description of search
 *
 * @author admin
 */
class Search extends Admin
{
    public function action_page()
    {
        $mSearch = Model::instance($this->params[2]);
        
        if(!isset($_GET['query'])){
             throw new Exceptions\E404("search query is not found");
        }
        else{
            $link = $_GET['query'];
            $search = $mSearch->SearchData($_GET['query']);
            $this->title .= 'поиск';

            $this->content = System::template('client/v_search.php', [
                       'start' => $search['start'],
                       'data' => $search['data'],
                       'rows' => $search['rows'],
                       'num_pages' => $search['num_pages'],
                       'cur_page' => $this->params[2],
                       'link' => $link
                       //'smile' => $smiles
             ]);
        }
    }
}
