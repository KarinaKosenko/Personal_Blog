<?php
 
namespace Core\Exceptions; 

class Fatal extends Base{
    
    public function __construct($message = null, $code = 0, Exception $previous = null){
        $this->dest .= '/fatal';
        parent::__construct($message, $code, $previous);
        // хорошо бы уведомить владельца сайта
    }
    
}