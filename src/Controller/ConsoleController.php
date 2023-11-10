<?php

namespace App\Controller;

use App\Entity\Console;
use App\Form\ConsoleType;
use App\Form\ImportGameType;
use App\Repository\ConsoleRepository;
use App\Repository\GameRepository;
use App\Service\ImportGameListCSV;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/console', name: 'console')]
class ConsoleController extends AbstractController
{

    #[Route('/list', name: '_list')]
    public function list(ConsoleRepository $consoleRepository): Response
    {
        $consoles = $consoleRepository->findAll();

        return $this->render('console/list.html.twig', [
            "consoles" => $consoles
        ]);
    }

    #[Route('/add', name: '_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $console = new Console();

        $consoleForm = $this->createForm(ConsoleType::class, $console);
        $consoleForm->handleRequest($request);

        if($consoleForm->isSubmitted() && $consoleForm->isValid()){
            dump($console);
            $entityManager->persist($console);
            $entityManager->flush();

            $this->addFlash('success', 'La console à été insérer avec succès');
            //return $this->redirectToRoute('')
        }

        return $this->render('console/add.html.twig', [
            'consoleForm' => $consoleForm->createView()
        ]);
    }

//    /**
//     * @Route("/{id}", name="_detail", requirements={"id"="\d+"})
//     */
//    public function detail($id, Request $request, ConsoleRepository $consoleRepository){
//        $console = $consoleRepository->find($id);
//        return $this->render('console/detail.html.twig', [
//            'console' => $console
//        ]);
//    }
}
