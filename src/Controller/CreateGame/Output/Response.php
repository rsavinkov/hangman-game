<?php

namespace rsavinkov\HangmanGame\Controller\CreateGame\Output;

use rsavinkov\HangmanGame\Controller\AbstractResponse;

final class Response extends AbstractResponse
{
    private $gameKey;

    public function setGameKey($gameKey): self
    {
        $this->gameKey = $gameKey;

        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if ($this->gameKey) {
            $array['gameKey'] = $this->gameKey;
        }

        return $array;
    }
}
