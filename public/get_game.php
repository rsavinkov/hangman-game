<?php

use Predis\Client;
use rsavinkov\HangmanGame\Controller\GetGame\Controller;
use rsavinkov\HangmanGame\Controller\GetGame\Input\RequestConverter;
use rsavinkov\HangmanGame\Service\Hangman\Mapper;
use rsavinkov\HangmanGame\Service\Hangman\Storage;

require __DIR__ . '/../vendor/autoload.php';

$config = include('../config/config.php');
$redisClient = new Client($config['REDIS_DSN']);
$hangmanMapper = new Mapper();
$hangmanStorage = new Storage($redisClient, $hangmanMapper);
$requestConverter = new RequestConverter();

$controller = new Controller($hangmanStorage, $requestConverter);

$response = $controller->getGameAction($_SERVER['REQUEST_METHOD'], $_GET);

http_response_code($response->getCode());
header('Content-Type:application/json');
die(json_encode($response->toArray()));
