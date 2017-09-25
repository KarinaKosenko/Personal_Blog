<?php

namespace C\Client;

use Core\System;

class Pages extends Client{
   public function show404(){
        header("HTTP/1.1 404 Not Found");
        $this->title .= 'ошибка 404'; 
        $this->content = System::template('client/v_404.php');
    }
    
    public function show503(){
        header("HTTP/1.1 503 Server Error");
        $this->title .= 'ошибка 503'; 
        $this->content = System::template('client/v_503.php');
    }
}
