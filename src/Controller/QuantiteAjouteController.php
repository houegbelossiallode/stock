<?php

namespace App\Controller;

use App\Entity\QuantiteAjoute;
use App\Form\QuantiteAjouteType;
use App\Repository\QuantiteAjouteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quantite/ajoute')]
class QuantiteAjouteController extends AbstractController
{
    #[Route('/', name: 'app_quantite_ajoute_index', methods: ['GET'])]
    public function index(QuantiteAjouteRepository $quantiteAjouteRepository): Response
    {
        return $this->render('quantite_ajoute/index.html.twig', [
            'quantite_ajoutes' => $quantiteAjouteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quantite_ajoute_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantiteAjoute = new QuantiteAjoute();
        $form = $this->createForm(QuantiteAjouteType::class, $quantiteAjoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quantiteAjoute);
            $entityManager->flush();

            return $this->redirectToRoute('app_quantite_ajoute_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantite_ajoute/new.html.twig', [
            'quantite_ajoute' => $quantiteAjoute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quantite_ajoute_show', methods: ['GET'])]
    public function show(QuantiteAjoute $quantiteAjoute): Response
    {
        return $this->render('quantite_ajoute/show.html.twig', [
            'quantite_ajoute' => $quantiteAjoute,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quantite_ajoute_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuantiteAjoute $quantiteAjoute, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuantiteAjouteType::class, $quantiteAjoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quantite_ajoute_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantite_ajoute/edit.html.twig', [
            'quantite_ajoute' => $quantiteAjoute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quantite_ajoute_delete', methods: ['POST'])]
    public function delete(Request $request, QuantiteAjoute $quantiteAjoute, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantiteAjoute->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quantiteAjoute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quantite_ajoute_index', [], Response::HTTP_SEE_OTHER);
    }
}
