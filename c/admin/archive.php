<?php

namespace C\Admin;

use Core\System;
use M\Archive as Model;
use M\Smiles;

/**
 * Class Archive - controller to work with archive articles.
 */
class Archive extends Admin
{
    /**
     * Method to search archive articles (by month and year).
     */
    public function action_search()
    {
        // Get model to work with archive articles.
        $mArchive = Model::instance();
        // Check existence of period parameters.
        if (isset($_GET['month']) && isset($_GET['year'])) {
            // Get articles for a certain period.
            $articles = $mArchive->getArticles($_GET['month'], $_GET['year']);
            
            if (count($articles) > 0) {
                $this->content = System::template('admin/v_archive_result.php', [
                    'data' => $articles,
                    'rows' => count($articles),
                    'smile' => Smiles::instance()
                ]);
            }
            else {
                $this->content = System::template('admin/v_archive_search.php', [
                    'msg' => "По Вашему запросу ничего не найдено. "
                            . "Пожалуйста, выберите другой период времени."
                ]);
            }
        }
        else {
            $this->content = System::template('admin/v_archive_search.php', [
                'msg' => "Пожалуйста, выберите период времени."
            ]);
        }
    }
}

