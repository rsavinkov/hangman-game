<?php

namespace rsavinkov\HangmanGame\Controller\GetGame\Input;

use rsavinkov\HangmanGame\Controller\AbstractRequestConverter;
use rsavinkov\HangmanGame\Error\InvalidParameterError;

final class RequestConverter extends AbstractRequestConverter
{
    private const SUPPORTED_METHOD = 'GET';

    private const PARAM_GAME_KEY = 'gameKey';

    public function methodIsSupported(string $method): bool
    {
        return $method === self::SUPPORTED_METHOD;
    }

    public function getRequest(array $params): Request
    {
        return new Request(
            $this->getGameKey($params)
        );
    }

    private function getGameKey(array $params): string
    {
        $this->checkRequered(self::PARAM_GAME_KEY, $params);

        $gameKey = mb_strtolower($params[self::PARAM_GAME_KEY]);
        if (!preg_match('/^[a-z0-9]+$/', $gameKey)) {
            throw new InvalidParameterError(
                self::PARAM_GAME_KEY,
                'Incorrect game key'
            );
        }

        return $gameKey;
    }
}
