<?php

namespace rsavinkov\HangmanGame\Controller\CheckGuess\Input;

final class Request
{
    private $gameKey;
    private $guess;

    public function __construct($gameKey, $guess)
    {
        $this->gameKey = $gameKey;
        $this->guess = $guess;
    }

    public function getGameKey()
    {
        return $this->gameKey;
    }

    public function getGuess()
    {
        return $this->guess;
    }
}
