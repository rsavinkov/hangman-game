<?php

namespace rsavinkov\HangmanGame\Controller\CreateGame\Input;

use rsavinkov\HangmanGame\Controller\AbstractRequestConverter;
use rsavinkov\HangmanGame\Error\InvalidParameterError;

final class RequestConverter extends AbstractRequestConverter
{
    private const SUPPORTED_METHOD = 'POST';

    private const PARAM_WORD = 'word';
    private const PARAM_MISSES_MAX = 'missesMax';

    private const DEFAULT_MISSES_MAX = 6;

    public function methodIsSupported(string $method): bool
    {
        return $method === self::SUPPORTED_METHOD;
    }

    public function getRequest(array $params): Request
    {
        return new Request(
            $this->getWord($params),
            $this->getMissesMax($params)
        );
    }

    private function getWord(array $params): string
    {
        $this->checkRequered(self::PARAM_WORD, $params);

        $word = mb_strtolower($params[self::PARAM_WORD]);
        if (!preg_match('/^[a-z]+$/', $word)) {
            throw new InvalidParameterError(
                self::PARAM_WORD,
                'Only latin letters are supported.'
            );
        }

        return $word;
    }

    private function getMissesMax(array $params): int
    {
        $missesMax = $params[self::PARAM_MISSES_MAX] ?? self::DEFAULT_MISSES_MAX;
        if (!preg_match('/^[0-9]+$/', $missesMax)) {
            throw new InvalidParameterError(
                self::PARAM_MISSES_MAX,
                'It must be a number.'
            );
        }
        $missesMax = intval($missesMax);
        if ($missesMax <= 0) {
            throw new InvalidParameterError(
                self::PARAM_MISSES_MAX,
                'Must be greater than zero.'
            );
        }

        return $missesMax;
    }
}
