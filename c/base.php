<?php

namespace C;

use Core\Exceptions;

/**
 * Class Base - parent abstract class for other controllers.
 */
abstract class Base
{
    /**
     * Method to generate HTML-document (view).
     */
    public abstract function render();

    /**
     * Method to load parameters from URL-address.
     *
     * @param $params
     */
    public function load($params)
    {
        $this->params = $params;
    }

    /**
     * Method to disable to call undefined action.
     *
     * @param $name
     * @param $params
     * @throws Exceptions\E404
     */
    public function __call($name, $params)
    {
        throw new Exceptions\E404("undefined action $name");
    }
}

