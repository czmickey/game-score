Game score
=================

API endpoints to save player`s score and get top 10 players in requested game.

API uses [JSON-RPC v2.0](http://www.jsonrpc.org/specification).

Data are stored in Redis.    


Requirements
------------

* PHP 7.1 or higher (with [php Redis extension](https://github.com/nicolasff/phpredis/) 3.1.1)
* [Nette Framework](https://github.com/nette/nette) 2.4
* [Redis database](http://redis.io)  3.2


Request examples 
------------

**Save player`s score**

    {
        "jsonrpc": "2.0",
        "method": "save",
        "params": {
            "gameId": 1,
            "userId": 7,
            "score": 340
        },
        "id": 1001
    }

**Get top 10 players**

    {
        "jsonrpc": "2.0",
        "method": "getTopTen",
        "params": {
            "gameId": 7
        },
        "id": 1001
    }

