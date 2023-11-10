<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Session;
use App\Entity\State;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Service\TimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/session', name: 'session')]
class SessionController extends AbstractController
{

    #[Route('/list', name: '_list')]
    public function list(SessionRepository $sessionRepository):Response{
        $sessions = $sessionRepository->findAll();
        return $this->render('session/list.html.twig', [
            'sessions' => $sessions
        ]);
    }

    #[Route('/add', name:'_add')]
    public function add(Request $request,
                        EntityManagerInterface $entityManager,
                        TimeService $service): Response
    {
        $session = new Session();
        $session->setCreationDate(new \DateTime());
        $sessionForm = $this->createForm(SessionType::class, $session);
        $sessionForm->handleRequest($request);

        if($sessionForm->isSubmitted() && $sessionForm->isValid()){
            $timeSession = $service->convertStringTimetoInt($sessionForm['sessionTimeText']->getData());
            $session->setSessionTime($timeSession);
            $gameRepository = $entityManager->getRepository(Game::class);
            $game = $gameRepository->find($session->getGame()->getId());
            dump($game);
            if($game != null){
                $game?->setGameTime($game->getGameTime() + $session->getSessionTime());
                if($game->getState()->getId() != 2) {
                    $state = $entityManager->getRepository(State::class)->findOneBy(['name' => 'En cours']);
                    $game->setState($state);
                }
                if($game->getStartDate() == null){
                    $game->setStartDate(new \DateTime());
                }
                $game->setUpdateDate(new \DateTime());
            }
            $entityManager->persist($session);
            $entityManager->persist($game);
            $entityManager->flush();
            dump($session);
            $this->addFlash('success', 'La session a bien été ajouté');
            $this->redirectToRoute('session_list');
        }
        return $this->render('session/add.html.twig', [
            'sessionForm' => $sessionForm->createView(),
        ]);
    }


}
