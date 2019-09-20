<?php

namespace rsavinkov\HangmanGame\Controller\CheckGuess\Output;

use rsavinkov\HangmanGame\Controller\AbstractResponse;
use rsavinkov\HangmanGame\Model\Hangman;

final class Response extends AbstractResponse
{
    /**
     * @var Hangman
     */
    private $hangman;

    public function setHangman(Hangman $hangman): self
    {
        $this->hangman = $hangman;

        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if ($this->hangman) {
            $array['row'] = $this->hangman->getRow();
            $array['misses'] = $this->hangman->getMisses();
            $array['result'] = $this->hangman->isOver()
                ? ($this->hangman->isWon() ? 'win' : 'lose')
                : null;
        }

        return $array;
    }
}
