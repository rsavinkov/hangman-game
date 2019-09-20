<?php

namespace rsavinkov\HangmanGame\Controller\CreateGame;

use rsavinkov\HangmanGame\Controller\CreateGame\Input\RequestConverter;
use rsavinkov\HangmanGame\Controller\CreateGame\Output\Response;
use rsavinkov\HangmanGame\Error\InvalidParameterError;
use rsavinkov\HangmanGame\Service\Hangman\Factory;
use rsavinkov\HangmanGame\Service\Hangman\Storage;
use Throwable;

final class Controller
{
    private $hangmanFactory;
    private $hangmanStorage;
    private $requestConverter;

    public function __construct(
        Factory $hangmanFactory,
        Storage $hangmanStorage,
        RequestConverter $requestConverter
    ) {
        $this->hangmanFactory = $hangmanFactory;
        $this->hangmanStorage = $hangmanStorage;
        $this->requestConverter = $requestConverter;
    }

    public function createGameAction(string $method, array $params): Response
    {
        if (!$this->requestConverter->methodIsSupported($method)) {
            return Response::methodNotAllowed();
        }

        try {
            $request = $this->requestConverter->getRequest($params);
            $hangman = $this->hangmanFactory->createHangman($request->getWord(), $request->getMissesMax());
            $gameKey = $this->hangmanStorage->saveHangman($hangman);

            return Response::ok()->setGameKey($gameKey);
        } catch (InvalidParameterError $error) {
            return Response::badRequest($error->getMessage(), $error->getParameterName());
        } catch (Throwable $exception) {
            error_log(date('Y-m-d h:i:s') . ' | ' . $exception->getMessage());
            return Response::internalServerError('Something went wrong');
        }
    }
}
