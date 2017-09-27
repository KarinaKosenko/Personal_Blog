<?php

namespace C\Client;

use Core\System;

/**
 * Class Pages - controller to work with errors.
 */
class Pages extends Client
{
    /**
     * Method generates error 404.
     */
    public function show404()
    {
        header("HTTP/1.1 404 Not Found");
        $this->title .= 'ошибка 404';
        $this->content = System::template('client/v_404.php');
    }

    /**
     * Method generates error 404.
     */
    public function show503()
    {
        header("HTTP/1.1 503 Server Error");
        $this->title .= 'ошибка 503'; 
        $this->content = System::template('client/v_503.php');
    }
}

