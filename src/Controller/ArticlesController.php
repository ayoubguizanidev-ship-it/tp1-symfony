<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;

class ArticlesController extends AbstractController
{
    #[Route('/articles/nouveau', name: 'app_article_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        // Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);

        // Traitement de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            // Message de confirmation
            $this->addFlash('success', 'Article créé avec succès !');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
        
    }
