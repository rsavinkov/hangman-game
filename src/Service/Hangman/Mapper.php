<?php


namespace rsavinkov\HangmanGame\Service\Hangman;


use rsavinkov\HangmanGame\Model\Hangman;

class Mapper
{
    public function mapToJson(Hangman $hangman): string
    {
        return json_encode([
            'word' => $hangman->getWord(),
            'row' => $hangman->getRow(),
            'misses' => $hangman->getMisses(),
            'missesMax' => $hangman->getMissesMax()
        ]);
    }

    public function mapFromJson(string $jsonData): Hangman
    {
        $data = json_decode($jsonData, true);
        return new Hangman(
            $data['word'],
            $data['row'],
            $data['misses'],
            $data['missesMax']
        );
    }
}
