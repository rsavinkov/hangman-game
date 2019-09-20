<?php

namespace rsavinkov\HangmanGame\Service\Hangman;

use rsavinkov\HangmanGame\Model\Hangman;

final class Factory
{
    public function createHangman(string $word, int $missesNum): Hangman
    {
        return new Hangman(
            $word,
            str_repeat('_', mb_strlen($word)),
            [],
            $missesNum
        );
    }
}
