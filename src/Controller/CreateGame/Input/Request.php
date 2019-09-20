<?php

namespace rsavinkov\HangmanGame\Controller\CreateGame\Input;

final class Request
{
    private $word;
    private $missesMax;

    public function __construct(string $word, int $missesMax)
    {
        $this->word = $word;
        $this->missesMax = $missesMax;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getMissesMax(): int
    {
        return $this->missesMax;
    }
}
