<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\FournisseurRepository;
use App\Repository\UserRepository;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ArticleRepository $articleRepository, ClientRepository $clientRepository
    ,FournisseurRepository $fournisseurRepository, VenteRepository $venteRepository, UserRepository $userRepository): Response
    {
        $article = $articleRepository->getNb();
        $client = $clientRepository->getNb();
        $fournisseur = $fournisseurRepository->getNb();
        $vente = $venteRepository->getNb();
      
        return $this->render('dashboard/index.html.twig', [
            'article'=>$article,
            'client'=> $client,
            'fournisseur'=> $fournisseur,
            'vente'=> $vente
        ]);
    }
}