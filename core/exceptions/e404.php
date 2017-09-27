<?php
 
namespace Core\Exceptions;

use Exception;

/**
 * Class E404 - class to log 404 errors.
 */
class E404 extends Base
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $this->dest .= '/e404';
        parent::__construct($message, $code, $previous);
    }
    
}

