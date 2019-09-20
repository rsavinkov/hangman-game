<?php


namespace rsavinkov\HangmanGame\Controller;


use rsavinkov\HangmanGame\Error\InvalidParameterError;

abstract class AbstractRequestConverter
{
    abstract public function methodIsSupported(string $method): bool;

    abstract public function getRequest(array $params);

    protected function checkRequered(string $paramName, array $params)
    {
        if (!key_exists($paramName, $params)) {
            throw new InvalidParameterError(
                $paramName,
                'Parameter is required'
            );
        }
    }
}
