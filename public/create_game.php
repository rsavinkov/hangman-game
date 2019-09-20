<?php

use Predis\Client;
use rsavinkov\HangmanGame\Controller\CreateGame\Controller;
use rsavinkov\HangmanGame\Controller\CreateGame\Input\RequestConverter;
use rsavinkov\HangmanGame\Service\Hangman\Factory;
use rsavinkov\HangmanGame\Service\Hangman\Mapper;
use rsavinkov\HangmanGame\Service\Hangman\Storage;

require __DIR__ . '/../vendor/autoload.php';

$config = include('../config/config.php');
$redisClient = new Client($config['REDIS_DSN']);
$hangmanMapper = new Mapper();
$hangmanStorage = new Storage($redisClient, $hangmanMapper);
$hangmanFactory = new Factory();
$requestConverter = new RequestConverter();

$controller = new Controller($hangmanFactory, $hangmanStorage, $requestConverter);

$response = $controller->createGameAction($_SERVER['REQUEST_METHOD'], $_POST);

http_response_code($response->getCode());
header('Content-Type:application/json');
die(json_encode($response->toArray()));
