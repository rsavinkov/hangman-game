<?php

namespace rsavinkov\HangmanGame\Service\Hangman;

use Predis\ClientInterface;
use rsavinkov\HangmanGame\Model\Hangman;

class Storage
{
    private $redisClient;
    private $hangmanMapper;

    public function __construct(ClientInterface $redisClient, Mapper $hangmanMapper)
    {
        $this->redisClient = $redisClient;
        $this->hangmanMapper = $hangmanMapper;
    }

    public function saveHangman(Hangman $hangman, ?string $key = null, ?int $gameTTL = null): string
    {
        if (empty($key)) {
            $key = $this->generateKey();
        }
        $this->redisClient->set($key, $this->hangmanMapper->mapToJson($hangman));
        if ($gameTTL) {
            $this->redisClient->expire($key, $gameTTL);
        }

        return $key;
    }

    public function getHangman(string $key): ?Hangman
    {
        if ($this->redisClient->exists($key)) {
            return $this->hangmanMapper->mapFromJson($this->redisClient->get($key));
        }

        return null;
    }

    private function generateKey(): string
    {
        return uniqid();
    }
}
