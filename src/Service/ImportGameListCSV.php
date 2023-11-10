<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\ConsoleRepository;
use App\Repository\StateRepository;

class ImportGameListCSV
{
    private $fileName;

    public function __construct(){

    }

    public function importGameListCSV(ConsoleRepository $consoleRepository,
                                      StateRepository $stateRepository): array{

        $lecture = fopen('file/'.$this->fileName, 'r');
        $line = fgets($lecture);
        while(!feof($lecture)){
            $line = fgets($lecture);
            if($line != false){
                $separation = explode(';', $line);
                $game = new Game();
                if(isset($separation[0])){
                    $game->setName($separation[0]);
                }
                if(isset($separation[1])){
                  $console = $consoleRepository->findOneBy(['name' => $separation[1]]);
                  $game->setConsole($console);
                }
                if(isset($separation[2])){
                    if(substr($separation[1], 0, 3) == "Oui"){
                        $game->setBoxGame(1);
                    }
                    else{
                        $game->setBoxGame(0);
                    }
                }
                if(isset($separation[3])){
                    $state = $stateRepository->findOneBy(['name' => $separation[3]]);
                    $game->setState($state);
                }
                if(isset($separation[5])){
                    $game->setTrophyPourcentage(intval($separation[5]));
                }
                if(isset($separation[6])){
                    $splitDateBegin = explode(":", $separation[6]);
                    try {
                        $game->setStartDate(new \DateTime($separation[6]));
                    } catch (\Exception $e) {
                    }
                }
                if(isset($separation[7])){
                    $splitDateEnd = explode(":", $separation[7]);
                    try {
                        $game->setFinishDate(new \DateTime($separation[7]));
                    } catch (\Exception $e) {
                    }
                }
                if(isset($separation[8]) && $separation[8] != ""){

                    $splitTime = explode(":", $separation[8]);
                    $game->setGameTime($splitTime[0].'.'.$splitTime[1]);
                }
                $game->setCreationDate(new \DateTime());
                $games[] = $game;
            }
        }
        return $games;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }


}