<?php
 
namespace Core\Exceptions;

use Exception;

/**
 * Class Fatal - class to log fatal server errors.
 */
class Fatal extends Base
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $this->dest .= '/fatal';
        parent::__construct($message, $code, $previous);
    }
    
}

