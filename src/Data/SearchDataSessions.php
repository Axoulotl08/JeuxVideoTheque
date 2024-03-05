<?php

namespace App\Data;

use App\Entity\Console;
use App\Entity\Game;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\IntegerType;

class SearchDataSessions
{
    /**
     * @var Game
     */
    public $game;

    /**
     * @var null|DateType
     */
    public $sessionDateBetweenStart;

    /**
     * @var null|DateType
     */
    public $sessionDateBetweenEnd;

    /**
     * @var null|IntegerType
     */
    public $durationMin;

    /**
     * @var null|IntegerType
     */
    public $durationMax;

    public function __toString(){
        return "toto";
    }
}