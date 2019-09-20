<?php

namespace rsavinkov\HangmanGame\Error;

use LogicException;

class InvalidParameterError extends LogicException
{
    private $parameterName;

    public function __construct($parameterName, $message)
    {
        $this->parameterName = $parameterName;
        parent::__construct($message);
    }

    public function getParameterName()
    {
        return $this->parameterName;
    }
}
