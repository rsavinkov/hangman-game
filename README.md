# hangman-game

Hangman game. It's just example of my code.

###Structure:
- `/config` - some environment variables, for example: redis config
- `/public` - entry-points
- `/src/Controller` - controllers, individual folder for each endpoint
- `/src/Controller/*/Input` - Request (DTO) and RequestConverter (Request Factory with validation)
- `/src/Controller/*/Output` - Response (DTO), individual for each endpoint just for flexibility
- `/src/Model/Hangman` - Hangman game model
- `/src/Service` - common services
- `/src/Service/GameManager` - main business-logic of game
- `/tests/Controller` - smoke-tests for controllers
- `/tests/Service` - tests for business-logic


## Dev-environment

### Installation

1) `docker-compose up -d`
2) `docker-compose exec php-fpm composer install`
3) `docker-compose exec php-fpm cp /application/config/config.dist.php /application/config/config.php`

### Tests
`docker-compose exec php-fpm composer test`

## Available Endpoints

####Create Game
`POST http://localhost:4201/create_game.php`
```
"word":"test" // word for game
"missesMax":6 // misses amount
```
Response
```
{
    "gameKey": "5d846a1999cd4"
}
```

####Get Game
`GET http://localhost:4201/get_game.php?gameKey=5d846a1999cd4`

Response
```
{
    "row": "____", // each '_' means letter to guess
    "misses": [], // your misses array
    "result": null // can be null, 'win' or 'lose'
}
```

#### Check guess
`POST http://localhost:4201/check_guess.php`
```
"gameKey": "5d846a1999cd4"
"guess": "o" // your guess, just a letter or whole word
```
Response

```
{
    "row": "____", // each '_' means letter to guess
    "misses": ['o'], // your misses array
    "result": null // can be null, 'win' or 'lose'
}
```

