<?php

namespace C;

use Core\Exceptions;

abstract class Base{
    public abstract function render();
    
    public function load($params){
        $this->params = $params;
    }
    
    public function __call($name, $params){
        throw new Exceptions\E404("undefined action $name");
    }
}
