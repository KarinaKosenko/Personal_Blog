<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace C\Admin;

use Core\System;
use M\Archive as Model;
use M\Smiles;

/**
 * Description of archive
 *
 * @author admin
 */
class Archive extends Admin
{
    public function action_search()
    {
        $mArchive = Model::instance();
        
        if(isset($_GET['month']) && isset($_GET['year'])){
            $articles = $mArchive->getArticles($_GET['month'], $_GET['year']);
            
            if(count($articles) > 0){
                $this->content = System::template('admin/v_archive_result.php', 
                     ['data' => $articles,
                      'rows' => count($articles),
                      'smile' => Smiles::instance()]);
            }
            else{
                $this->content = System::template('admin/v_archive_search.php',
                        ['msg' => "По Вашему запросу ничего не найдено. "
                            . "Пожалуйста, выберите другой период времени."]);
            }
        }
        else{
            $this->content = System::template('admin/v_archive_search.php',
                    ['msg' => "<strong>Пожалуйста, выберите период времени.</strong>"]);
        }
    }
}
