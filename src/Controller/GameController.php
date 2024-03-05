<?php

namespace App\Controller;

use App\Data\SearchDataGames;
use App\Entity\Game;
use App\Form\GameType;
use App\Form\GameUpdateType;
use App\Form\ImportGameType;
use App\Form\SearchGamesType;
use App\Repository\ConsoleRepository;
use App\Repository\GameRepository;
use App\Repository\SessionRepository;
use App\Repository\StateRepository;
use App\Service\ImportGameListCSV;
use App\Service\TimeService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/game', name: 'game')]
class GameController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function list(GameRepository $gameRepository,
                         Request $request): Response
    {
//        $games = $gameRepository->findAll();
        $data = new SearchDataGames();
        $formFilter = $this->createForm(SearchGamesType::class, $data);
        $formFilter->handleRequest($request);
        $games = $gameRepository->searchFilter($data);
        $nbGame = count($games);
        return $this->render('game/list.html.twig', [
            'games' => $games,
            'formFilter' => $formFilter->createView(),
            'nbGame' => $nbGame
        ]);
    }

    #[Route('/add', name: '_add')]
    public function add(Request $request,
                        EntityManagerInterface $entityManager,
                        StateRepository $stateRepository)
    {
        $game = new Game();
        $status = $stateRepository->findAll();
        $game->setCreationDate(new DateTime());
        $game->setState($status[0]);
        $gameForm = $this->createForm(GameType::class, $game);
        $gameForm->handleRequest($request);

        if ($gameForm->isSubmitted() && $gameForm->isValid()) {
            dump($game);
            $entityManager->persist($game);
            $entityManager->flush();
            $this->addFlash('success', 'Le jeu à été ajouté à votre collection');
            $this->redirectToRoute('game_list');
        }

        return $this->render('game/add.html.twig', [
           'gameForm' => $gameForm->createView()
        ]);
    }

    #[Route('/{id}', name:'_id', requirements:['id'=>'\d+'])]
    public function modifyGame(Request $request,
                               GameRepository $gameRepository,
                               int $id,
                               EntityManagerInterface $entityManager,
                               TimeService $service){
        $game = $entityManager->getRepository(Game::class)->find($id);
        if(!$game){
            $this->addFlash('error', 'Le jeu n\'existe pas');
            $this->redirectToRoute('game_list');
        }
        $gameForm = $this->createForm(GameUpdateType::class, $game);
        $gameForm->handleRequest($request);
        if($gameForm->isSubmitted() && $gameForm->isValid()){
            $stringTime = $gameForm['gameTimeText']->getData();
            if($stringTime != null) {
                $intTime = $service->convertStringTimetoInt($gameForm['gameTimeText']->getData());
                $game->setGameTime($intTime);
            }
            $game->setUpdateDate(new \DateTime());
            $entityManager->persist($game);
            $entityManager->flush();
            $this->addFlash('success', 'Le jeu a bien été modifié');
            return $this->redirectToRoute('game_list');
        }
        return $this->render("game/modify.html.twig", [
            'gameModify' => $gameForm->createView()
        ]);
    }

    #[Route('/detail/{id}', name:'_detail_id', requirements:['id'=>'\d+'])]
    public function gameDetail(Request $request, 
                            GameRepository $gameRepository, 
                            int $id, 
                            EntityManagerInterface $entityManager, 
                            TimeService $timeService, 
                            SessionRepository $sessionRepository) {

        $game = $entityManager->getRepository(Game::class)->find($id);
        $sessions = $sessionRepository->getLastSessionOfAGame($id);
        if(count($sessions) != 0)
            $sessoinTime = $sessions[0]->getSessionTime();
        else
            $sessoinTime = 0;
        //dd($sessions[0]);        
        return $this->render("game/detail.html.twig", [
            'game' => $game,
            'lastSessionTime' => $sessoinTime
        ]);
    }

    #[Route('/import', name: '_import')]
    public function import(Request $request,
                           EntityManagerInterface $entityManager,
                           GameRepository $gameRepository,
                           ConsoleRepository $consoleRepository,
                           StateRepository $stateRepository,
                           SluggerInterface $slugger):Response{
        $import = new ImportGameListCSV();
        $form = $this->createForm(ImportGameType::class, $import);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $fileName = $form->get('fileName')->getData();
            if($fileName){
                $originalName = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalName);
                $newFileName = $safeFileName.'-'.uniqid().'.cvs';
            }
            try {
                $fileName->move(
                    $this->getParameter('csv_directory'),
                    $newFileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $import->setFileName($newFileName);
            $games = $import->importGameListCSV($consoleRepository, $stateRepository);
            dd($games);
        }

        return $this->render('game/import.html.twig', [
            'formCSV' => $form->createView()
        ]);
    }
}
