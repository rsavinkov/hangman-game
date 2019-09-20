<?php

namespace rsavinkov\HangmanGame\Model;

final class Hangman
{
    private $word;

    private $row;

    private $misses;

    private $missesMax;

    public function __construct(string $word, string $row, array $misses, int $missesMax)
    {
        $this->word = $word;
        $this->row = $row;
        $this->misses = $misses;
        $this->missesMax = $missesMax;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getRow(): string
    {
        return $this->row;
    }

    public function setRow(string $row): void
    {
        $this->row = $row;
    }

    public function getMisses(): array
    {
        return $this->misses;
    }

    public function addMisses(string $string)
    {
        if (!$this->isOver() && !in_array($string, $this->misses)) {
            $this->misses[] = $string;
        }
    }

    public function getMissesMax(): int
    {
        return $this->missesMax;
    }

    public function isLost(): bool
    {
        return count($this->misses) >= $this->missesMax;
    }

    public function isWon(): bool
    {
        return $this->word === $this->row;
    }

    public function isOver()
    {
        return $this->isLost() || $this->isWon();
    }
}
