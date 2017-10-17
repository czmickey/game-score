<?php

namespace App\model\entity;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class PlayerScore
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $gameId;

    /**
     * @var int
     */
    private $value;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setPlayerId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     */
    public function setGameId(int $gameId)
    {
        $this->gameId = $gameId;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value)
    {
        $this->value = $value;
    }
}
