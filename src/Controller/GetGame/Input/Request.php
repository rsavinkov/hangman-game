<?php

namespace rsavinkov\HangmanGame\Controller\GetGame\Input;

final class Request
{
    private $gameKey;

    public function __construct($gameKey)
    {
        $this->gameKey = $gameKey;
    }

    public function getGameKey()
    {
        return $this->gameKey;
    }
}
