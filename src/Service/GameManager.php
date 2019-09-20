<?php

namespace rsavinkov\HangmanGame\Service;

use rsavinkov\HangmanGame\Model\Hangman;

class GameManager
{
    public function checkGuess(Hangman $hangman, string $guessString): void
    {
        if ($hangman->isOver()) {
            return;
        }

        $result = mb_strlen($guessString) === 1
            ? $this->checkLetter($hangman, $guessString)
            : $this->checkWord($hangman, $guessString);

        if (!$result) {
            $hangman->addMisses($guessString);
        }
    }

    private function checkLetter(Hangman $hangman, string $guessString): bool
    {
        $success = false;
        $offset = 0;

        while (($lastPos = mb_strpos($hangman->getWord(), $guessString, $offset)) !== false) {
            $hangman->setRow(
                mb_substr($hangman->getRow(), 0, $lastPos) .
                $guessString .
                mb_substr($hangman->getRow(), $lastPos + mb_strlen($guessString), mb_strlen($hangman->getRow()))
            );
            $success = true;
            $offset = $lastPos + mb_strlen($guessString);
        }

        return $success;
    }

    private function checkWord(Hangman $hangman, string $guessString): bool
    {
        if ($hangman->getWord() === $guessString) {
            $hangman->setRow($guessString);
            return true;
        }

        return false;
    }
}
