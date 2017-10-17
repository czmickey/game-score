<?php

namespace App\model\repository;

use App\model\entity\PlayerScore;
use App\model\ScoreSaveErrorException;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class ScoreRepository
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * ScoreRepository constructor.
     * @param array $configParams
     */
    public function __construct($configParams)
    {
        $this->redis = new \Redis();
        $this->redis->connect($configParams['host'], $configParams['port']);
    }

    /**
     * @param PlayerScore $score
     * @throws ScoreSaveErrorException
     */
    public function saveUserScore(PlayerScore $score)
    {
        $this->redis->zAdd('game:' . $score->getGameId(), $score->getValue(), $score->getUserId());
        $this->redis->zAdd('scorerank:' . $score->getGameId(), $score->getValue(), $score->getValue());
        $this->redis->hSet('playerscore:' . $score->getGameId(), $score->getUserId(), $score->getValue());
    }

    /**
     * @param int $gameId
     * @return array
     */
    public function getTopTen($gameId)
    {
        $players = $this->redis->zRevRange('game:' . $gameId, 0, 10);

        $playersWithRank = [];
        foreach ($players as $player) {
            $playerScore = $this->redis->hGet('playerscore:' . $gameId, $player);
            $playerRank = $this->redis->zRevRank('scorerank:' . $gameId, $playerScore);
            $playersWithRank[] = ['player' => $player, 'rank' => $playerRank, 'score' => $playerScore];
        }

        return $playersWithRank;
    }
}
