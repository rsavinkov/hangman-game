<?php

namespace rsavinkov\HangmanGame\tests\Controller\GetGame;

use PHPUnit\Framework\TestCase;
use rsavinkov\HangmanGame\Controller\AbstractResponse;
use rsavinkov\HangmanGame\Controller\GetGame\Controller;
use rsavinkov\HangmanGame\Controller\GetGame\Input\RequestConverter;
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
            ->setMethods(['getHangman'])
            ->getMock();
        $hangmanStorageMock->expects($this->any())->method('getHangman')
            ->with($gameKey)
            ->willReturn($hangman);

        $requestConverter = new RequestConverter();
        /** @var StorageInterface $hangmanStorageMock */
        $controller = new Controller($hangmanStorageMock, $requestConverter);

        $response = $controller->getGameAction('GET', ['gameKey' => $gameKey]);
        $this->assertEquals(AbstractResponse::RESPONSE_CODE_OK, $response->getCode());
        $this->assertEquals(['row' => '____', 'misses' => [], 'result' => null], $response->toArray());
    }

    public function testNotFound()
    {
        $hangmanStorageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHangman'])
            ->getMock();
        $hangmanStorageMock->expects($this->any())->method('getHangman')
            ->willReturn(null);

        $requestConverter = new RequestConverter();
        /** @var StorageInterface $hangmanStorageMock */
        $controller = new Controller($hangmanStorageMock, $requestConverter);

        $response = $controller->getGameAction('GET', ['gameKey' => uniqid()]);
        $this->assertEquals(AbstractResponse::RESPONSE_CODE_NOT_FOUND, $response->getCode());
        $this->assertEquals(['error' => ['message' => 'Game not found']], $response->toArray());
    }
}
