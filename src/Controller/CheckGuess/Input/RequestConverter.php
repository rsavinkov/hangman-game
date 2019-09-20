<?php

namespace rsavinkov\HangmanGame\Controller\CheckGuess\Input;

use rsavinkov\HangmanGame\Controller\AbstractRequestConverter;
use rsavinkov\HangmanGame\Error\InvalidParameterError;

final class RequestConverter extends AbstractRequestConverter
{
    private const SUPPORTED_METHOD = 'POST';

    private const PARAM_GAME_KEY = 'gameKey';
    private const PARAM_GUESS = 'guess';

    public function methodIsSupported(string $method): bool
    {
        return $method === self::SUPPORTED_METHOD;
    }

    public function getRequest(array $params): Request
    {
        return new Request(
            $this->getGameKey($params),
            $this->getGuess($params)
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

    private function getGuess(array $params): string
    {
        $this->checkRequered(self::PARAM_GUESS, $params);

        $guess = mb_strtolower($params[self::PARAM_GUESS]);
        if (!preg_match('/^[a-z]+$/', $guess)) {
            throw new InvalidParameterError(
                self::PARAM_GUESS,
                'Only latin letters are supported.'
            );
        }

        return $guess;
    }
}
