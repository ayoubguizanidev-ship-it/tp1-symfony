<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TacheController extends AbstractController
{
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $tacheRepository): Response
    {
        $taches = $tacheRepository->findAll();

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $entityManager): Response
    {
        $tache = new Tache();

        $tache->setTitre('Apprendre Symfony');
        $tache->setDescription('Terminer le mini-projet To-Do List');
        $tache->setTerminee(false);
        $tache->setDateCreation(new \DateTimeImmutable());

        $entityManager->persist($tache);
        $entityManager->flush();

        return $this->redirectToRoute('app_tache_detail', [
            'id' => $tache->getId(),
        ]);
    }

    #[Route('/taches/{id}', name: 'app_tache_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, TacheRepository $tacheRepository): Response
    {
        $tache = $tacheRepository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche introuvable.');
        }

        return $this->render('tache/detail.html.twig', [
            'tache' => $tache,
        ]);
    }
}
