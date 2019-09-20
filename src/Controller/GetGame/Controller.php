<?php

namespace rsavinkov\HangmanGame\Controller\GetGame;

use rsavinkov\HangmanGame\Controller\GetGame\Input\RequestConverter;
use rsavinkov\HangmanGame\Controller\GetGame\Output\Response;
use rsavinkov\HangmanGame\Error\InvalidParameterError;
use rsavinkov\HangmanGame\Service\Hangman\Storage;
use Throwable;

final class Controller
{
    private $hangmanStorage;
    private $requestConverter;

    public function __construct(
        Storage $hangmanStorage,
        RequestConverter $requestConverter
    ) {
        $this->hangmanStorage = $hangmanStorage;
        $this->requestConverter = $requestConverter;
    }

    public function getGameAction(string $method, array $params): Response
    {
        if (!$this->requestConverter->methodIsSupported($method)) {
            return Response::methodNotAllowed();
        }

        try {
            $request = $this->requestConverter->getRequest($params);
            $hangman = $this->hangmanStorage->getHangman($request->getGameKey());
            if (empty($hangman)) {
                return Response::notFound('Game not found');
            }

            return Response::ok()->setHangman($hangman);
        } catch (InvalidParameterError $error) {
            return Response::badRequest($error->getMessage(), $error->getParameterName());
        } catch (Throwable $exception) {
            error_log(date('Y-m-d h:i:s') . ' | ' . $exception->getMessage());
            return Response::internalServerError('Something went wrong');
        }
    }
}
