<?php

namespace App\Controller;

use App\Repository\GameRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/statistics', name: 'stats')]
class StatisticsController extends AbstractController
{

    #[Route('/gamestats', name:'_gamestats')]
    public function test(GameRepository $gameRepository,
                         ChartBuilderInterface $chartBuilder): Response
    {
        $nbGamePerConsole = $gameRepository->countGameByConsole();

        return $this->render('statistics/index.html.twig', [
            'nbGames' => $nbGamePerConsole
        ]);
    }
}
