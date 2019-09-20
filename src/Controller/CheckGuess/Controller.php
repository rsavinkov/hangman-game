<?php

namespace rsavinkov\HangmanGame\Controller\CheckGuess;

use rsavinkov\HangmanGame\Controller\CheckGuess\Input\RequestConverter;
use rsavinkov\HangmanGame\Controller\CheckGuess\Output\Response;
use rsavinkov\HangmanGame\Error\InvalidParameterError;
use rsavinkov\HangmanGame\Service\GameManager;
use rsavinkov\HangmanGame\Service\Hangman\Storage;
use Throwable;

final class Controller
{
    private $hangmanStorage;
    private $gameManager;
    private $requestConverter;

    public function __construct(
        Storage $hangmanStorage,
        GameManager $gameManager,
        RequestConverter $requestConverter
    ) {
        $this->hangmanStorage = $hangmanStorage;
        $this->gameManager = $gameManager;
        $this->requestConverter = $requestConverter;
    }

    public function checkGuessAction(string $method, array $params): Response
    {
        if (!$this->requestConverter->methodIsSupported($method)) {
            return Response::methodNotAllowed();
        }

        try {
            $request = $this->requestConverter->getRequest($params);
            $hangman = $this->hangmanStorage->getHangman($request->getGameKey());
            if (!$hangman) {
                return Response::notFound('Game not found');
            }
            $this->gameManager->checkGuess($hangman, $request->getGuess());
            $this->hangmanStorage->saveHangman($hangman, $request->getGameKey());

            return Response::ok()->setHangman($hangman);
        } catch (InvalidParameterError $error) {
            return Response::badRequest($error->getMessage(), $error->getParameterName());
        } catch (Throwable $exception) {
            error_log(date('Y-m-d h:i:s') . ' | ' . $exception->getMessage());
            return Response::internalServerError('Something went wrong');
        }
    }
}
