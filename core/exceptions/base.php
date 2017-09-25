<?php
 
namespace Core\Exceptions; 
 
use Exception;

class Base extends Exception{
    
    public $dest = 'logs';
    
    public function __construct($message = null, $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
        
        $msg = "\n" . date("H:i:m") . "\n\n" . $this . "\n------------------------------------------------";
        
        file_put_contents($this->dest . '/' . date("Y-m-d"), $msg, FILE_APPEND);
    }
    
}