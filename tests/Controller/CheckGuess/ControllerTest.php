<?php

namespace rsavinkov\HangmanGame\tests\Controller\CheckGuess;

use PHPUnit\Framework\TestCase;
use rsavinkov\HangmanGame\Controller\AbstractResponse;
use rsavinkov\HangmanGame\Controller\CheckGuess\Controller;
use rsavinkov\HangmanGame\Controller\CheckGuess\Input\RequestConverter;
use rsavinkov\HangmanGame\Service\GameManager;
use rsavinkov\HangmanGame\Service\Hangman\Factory;
use rsavinkov\HangmanGame\Service\Hangman\Storage;
use rsavinkov\HangmanGame\Service\Hangman\StorageInterface;

class ControllerTest extends TestCase
{
    public function testHappyPath()
    {
        $gameKey = uniqid();
        $hangmanFactory = new Factory();
        $hangman = $hangmanFactory->createHangman('test', 6);

        $hangmanStorageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHangman', 'saveHangman'])
            ->getMock();
        $hangmanStorageMock->expects($this->any())->method('getHangman')
            ->with($gameKey)
            ->willReturn($hangman);
        $hangmanStorageMock->expects($this->any())->method('saveHangman')
            ->with($hangman, $gameKey)
            ->willReturn($gameKey);

        $gameManager = new GameManager();
        $requestConverter = new RequestConverter();
        /** @var StorageInterface $hangmanStorageMock */
        $controller = new Controller($hangmanStorageMock, $gameManager, $requestConverter);

        $response = $controller->checkGuessAction('POST', ['gameKey' => $gameKey, 'guess' => 'e']);
        $this->assertEquals(AbstractResponse::RESPONSE_CODE_OK, $response->getCode());
        $this->assertEquals(['row' => '_e__', 'misses' => [], 'result' => null], $response->toArray());
    }

    public function testNotFound()
    {
        $hangmanStorageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHangman'])
            ->getMock();
        $hangmanStorageMock->expects($this->any())->method('getHangman')
            ->willReturn(null);

        $gameManager = new GameManager();
        $requestConverter = new RequestConverter();
        /** @var StorageInterface $hangmanStorageMock */
        $controller = new Controller($hangmanStorageMock, $gameManager, $requestConverter);

        $response = $controller->checkGuessAction('POST', ['gameKey' => uniqid(), 'guess' => 'e']);
        $this->assertEquals(AbstractResponse::RESPONSE_CODE_NOT_FOUND, $response->getCode());
        $this->assertEquals(['error' => ['message' => 'Game not found']], $response->toArray());
    }
}
