<?php

namespace rsavinkov\HangmanGame\tests\Service;

use PHPUnit\Framework\TestCase;
use rsavinkov\HangmanGame\Service\GameManager;
use rsavinkov\HangmanGame\Service\Hangman\Factory;

class GameManagerTest extends TestCase
{
    public function testHappyPath()
    {
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman('test', 6);
        $gameManager = new GameManager();

        $gameManager->checkGuess($hangman, 't');
        $this->assertEquals('t__t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());

        $gameManager->checkGuess($hangman, 'e');
        $this->assertEquals('te_t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());

        $gameManager->checkGuess($hangman, 's');
        $this->assertEquals('test', $hangman->getRow());
        $this->assertTrue($hangman->isOver());
        $this->assertFalse($hangman->isLost());
        $this->assertTrue($hangman->isWon());
    }

    public function testRepeatedPositiveGuesses()
    {
        $missesNum = 6;
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman('test', $missesNum);
        $gameManager = new GameManager();

        $gameManager->checkGuess($hangman, 't');
        $this->assertEquals('t__t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEmpty($hangman->getMisses());

        $gameManager->checkGuess($hangman, 't');
        $this->assertEquals('t__t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEmpty($hangman->getMisses());

        $gameManager->checkGuess($hangman, 'q');
        $this->assertEquals('t__t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEquals(['q'], $hangman->getMisses());

        $gameManager->checkGuess($hangman, 'q');
        $this->assertEquals('t__t', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEquals(['q'], $hangman->getMisses());

    }

    public function testRepeatedNegativeGuesses()
    {
        $missesNum = 6;
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman('test', $missesNum);
        $gameManager = new GameManager();

        $gameManager->checkGuess($hangman, 'q');
        $this->assertEquals('____', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEquals(['q'], $hangman->getMisses());

        $gameManager->checkGuess($hangman, 'q');
        $this->assertEquals('____', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEquals(['q'], $hangman->getMisses());
    }

    public function testLosing()
    {
        $missesNum = 6;
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman('test', $missesNum);
        $gameManager = new GameManager();

        $guesses = ['q', 'w', 'r', 'y', 'u', 'i'];

        foreach ($guesses as $guess) {
            $gameManager->checkGuess($hangman, $guess);
        }

        $this->assertEquals('____', $hangman->getRow());
        $this->assertTrue($hangman->isOver());
        $this->assertTrue($hangman->isLost());
        $this->assertFalse($hangman->isWon());
        $this->assertEquals($guesses, $hangman->getMisses());
    }

    public function checkWordPositiveGuess()
    {
        $missesNum = 6;
        $word = 'test';
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman($word, $missesNum);
        $gameManager = new GameManager();

        $gameManager->checkGuess($hangman, $word);
        $this->assertEquals($word, $hangman->getRow());
        $this->assertTrue($hangman->isOver());
        $this->assertTrue($hangman->isWon());
        $this->assertFalse($hangman->isLost());
        $this->assertEquals([], $hangman->getMisses());
    }

    public function checkWordNegativeGuess()
    {
        $missesNum = 6;
        $word = 'foo';
        $guess = 'bar';
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman($word, $missesNum);
        $gameManager = new GameManager();

        $gameManager->checkGuess($hangman, $guess);
        $this->assertEquals('___', $hangman->getRow());
        $this->assertFalse($hangman->isOver());
        $this->assertEquals([$guess], $hangman->getMisses());
    }
}
