<?php

namespace C\Admin;

use M\About as Model;
use Core\System;
use Core\Validation;
use Core\Exceptions;

/**
 * Class About - controller to work with About page in the admin panel.
 */
class About extends Admin
{
    /**
     * Method reurns About page.
     */
    public function action_index()
    {
        // Get About model.
        $mModel = Model::instance();
        // Get About information.
        $about = $mModel->one(1);
		
	    $this->title = 'главная';
        $this->content = System::template('admin/v_about.php', [
            'title' => $about['title'],
			'content' => $about['content'],
         ]);
    }

    /**
     * Method for About page edition.
     *
     * @throws Exceptions\E404
     */
    public function action_edit()
    {
        // Get About model.
        $mModel = Model::instance();
        // Get About information.
        $about = $mModel->one($this->params[2]);

        if ($about === null) {
            throw new Exceptions\E404("information is not found");
        }
        else {
            $this->title = 'Редактировать страницу';
            $msg = "Пожалуйста, отредактируйте страницу.";

            $this->content = System::template('admin/v_add.php', [
                                            'title' => $about['title'],
                                            'content' => $about['content'],
                                            'msg' => $msg
                                    ]);

            if (count($_POST) > 0) {
                $title = $_POST['title'];
                $content = $_POST['content'];

                $obj = compact("title", "content");

                // POST-request data validation.
                $valid = new Validation($obj, $mModel->validationMap());
                $valid->execute('edit');

                if ($valid->good()) {
                    // Update data in the database.
                    $mModel->edit($this->params[2], $valid->cleanObj());
                    $_SESSION['msg'] = 'Страница успешно отредактирована.';
                    header("Location: /admin/about");
                    exit();
                }
                else {
                    // Get validation errors.
                    $errors = $valid->errors();
                    $msg = implode('<br>', $errors);
                }

                $this->content = System::template('admin/v_add.php', [
                                'title' => $title,
                                'content' => $content,
                                'msg' => $msg
                        ]);		
            }	
        }
    }	
}

