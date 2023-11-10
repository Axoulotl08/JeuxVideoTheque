<?php

namespace App\Data;

use App\Entity\Console;
use App\Entity\State;
use Doctrine\DBAL\Types\DateType;

class SearchDataGames
{
    /**
     * @var string
     */
    public $titlesearch;

    /**
     * @var Console
     */
    public $console;

    /**
     * @var null|DateType
     */
    public $startSaleDate;

    /**
     * @var null|DateType
     */
    public $endSaleDate;

    /**
     * @var State
     */
    public $state;

    public function __toString()
    {
        return "tata";
    }
}