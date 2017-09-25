<?php
 
namespace Core;  
 
class System{
    public static function template($path, $vars = []){
        extract($vars);
        ob_start();
        include("v/$path");
        return ob_get_clean();
    }
}
