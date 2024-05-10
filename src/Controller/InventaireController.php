<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Inventaire;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireController extends AbstractController
{
    #[Route('/inventaire', name: 'app_inventaire')]
    public function index(): Response
    {
        return $this->render('inventaire/index.html.twig', [
            
        ]);
    }




    #[Route('/inventaire/add', name: 'add_inventaire')]
    public function add(ArticleRepository $articleRepository,ManagerRegistry $doctrine)
    {
        $inventaire = new Inventaire();
        $article = $articleRepository->findAll();
        $date = new \DateTimeImmutable('now');
       // dd($date);
       if ($date) {
            $manager = $doctrine->getManager();
            $inventaire->setCommentaire('Oui enregistre');
            $inventaire->setStockFinal('25');
            $inventaire->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($inventaire);
            $manager->flush();
            
       }

       
        return $this->render('inventaire/index.html.twig', [
            
        ]);
    }




    

}