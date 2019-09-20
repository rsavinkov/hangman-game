<?php

namespace rsavinkov\HangmanGame\tests\Controller\CreateGame;

use PHPUnit\Framework\TestCase;
use rsavinkov\HangmanGame\Controller\AbstractResponse;
use rsavinkov\HangmanGame\Controller\CreateGame\Controller;
use rsavinkov\HangmanGame\Controller\CreateGame\Input\RequestConverter;
use rsavinkov\HangmanGame\Service\Hangman\Factory;
use rsavinkov\HangmanGame\Service\Hangman\Storage;
use rsavinkov\HangmanGame\Service\Hangman\StorageInterface;

class ControllerTest extends TestCase
{
    public function testHappyPath()
    {
        $gameKey = uniqid();
        $hangmanFactory = new Factory();

        $hangmanStorageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->setMethods(['saveHangman'])
            ->getMock();
        $hangmanStorageMock->expects($this->any())->method('saveHangman')
            ->willReturn($gameKey);

        $requestConverter = new RequestConverter();
        /** @var StorageInterface $hangmanStorageMock */
        $controller = new Controller($hangmanFactory, $hangmanStorageMock, $requestConverter);

        $response = $controller->createGameAction('POST', ['word' => 'test', 'missesMax' => 6]);
        $this->assertEquals(AbstractResponse::RESPONSE_CODE_OK, $response->getCode());
        $this->assertEquals(['gameKey' => $gameKey], $response->toArray());
    }
}
