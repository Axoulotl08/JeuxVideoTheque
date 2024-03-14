<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name:'home')]
    public function home(SessionRepository $sessionRepository): Response
    {
        $sessionsTime = $sessionRepository->getSessionTimeForLastSevenDays();
        $tmp = $sessionsTime[0];
        $time = $tmp['time'];

        if($time == null){
            $time = 0;
        }

        $lastSessionTime = $sessionRepository->getSessionTimeForSevenDaysBefore();
        $temp = $lastSessionTime[0];
        $timeLastWeek = $temp['time'];

        if($timeLastWeek == null){
            $timeLastWeek = 0;
        }

        //dd($time, $timeLastWeek);
        $diffrenceTime = $time <=> $timeLastWeek; 

        $sessions = $sessionRepository->getSessionForLastSevenDays();
        dump($sessions);
        $nbSessions = count($sessions);

        return $this->render('main/index.html.twig', [
            'playTime' => $time,
            'playTimeBefore' => $timeLastWeek,
            'difference' => $diffrenceTime,
            'sessions' => $sessions,
            'nbSessions' => $nbSessions
        ]);
    }
}
 