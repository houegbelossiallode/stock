<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Vente;
use App\Form\VenteType;
use App\Repository\ArticleRepository;
use App\Services\PdfService;
use App\Services\QrService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\BuilderInterface;

class VenteController extends AbstractController
{
    #[Route('/vente', name: 'app_vente')]
    public function index(ManagerRegistry $doctrine,Request $request ): Response
    {
     $qrcode = null;
        $repository = $doctrine->getRepository(Vente::class);
        $ventes = $repository->findAll();
      
       return $this->render('vente/index.html.twig', [
       'ventes' => $ventes,
       'qrcode'=> $qrcode
   
       ]);
            
    }
    

    
    #[Route('/vente/add', name: 'add_vente')]
    public function add(ManagerRegistry $doctrine,Request $request, QrService $qrService): Response
    {
        $image= $this->imageToBase64($this->getParameter('kernel.project_dir') .  '/public/assets/qrcode/B456.png');
        $qrcode = null;
        $vente = new Vente();   
        $form = $this->createForm(VenteType::class,$vente);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            
            $article = new Article(); 
            $id = $vente->getArticle();
           // dd($id = $vente->getArticle());
            $article = $doctrine->getRepository(Article::class)->find($id);
            $mot = $form->getData()->getQuantity();
            
           if ($mot > $article->getQuantite()) {
            $this->addFlash("error", "La quantité  n'est pas suffisante pour honorer la commande" );
            return $this->redirectToRoute('add_vente');
           }
            else{
                $qrcode = $qrService->qrcode();
                $manager = $doctrine->getManager();
                $manager->persist($vente);
                $manager->flush();
               // $article->getQuantite();
                $article->setQuantite($article->getQuantite() - $mot);
                
                $manager = $doctrine->getManager();
                $manager->persist($article);
                $manager->flush();
                $this->addFlash("vente", " La vente a été ajouté avec succès" );
            }
           
            
            return $this->redirectToRoute('app_vente');
            
        }
        
        else{
            
            return $this->render('vente/new.html.twig', [
                'form' => $form->createView(),
                'image'=> $image
            ]);
            
        }
        
    }
    

    #[Route('/vente/delete/{id}', name: 'vente_delete')]
    public function delete(Request $request,ManagerRegistry $doctrine, int $id): Response
    {
        $vente = new Vente();
        $vente = $doctrine->getRepository(Vente::class)->find($id);
    
        if($vente)
        {
            $manager = $doctrine->getManager();
            $manager->remove($vente);
            $manager->flush();
            $this->addFlash("success","Suppression réussi");
            
            return $this->redirectToRoute('app_vente');
           
        }else{
            $this->addFlash('error','Plat inexistant');
        }
    
          return $this->redirectToRoute('app_vente');
    }
    


    #[Route('/vente/edit/{id}', name: 'vente_edit')]
    public function edit(Request $request,ManagerRegistry $doctrine,$id)
    {
        
       $vente = new Vente();
       $vente = $doctrine->getRepository(Vente::class)->find($id);
       $form = $this->createForm(VenteType::class, $vente);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid())
       {
    
           $manager = $doctrine->getManager();
           $manager->persist($vente);
           $manager->flush();
           return $this->redirectToRoute('app_vente');
       }
       return $this->render('vente/edit.html.twig',array(
           'form'=>$form->createView(),
           
       ));
    }
    


    #[Route('/pdf/{id}', name: 'pdf')]
    public function generatePdfVente(ManagerRegistry $doctrine,int $id,QrService $qrService): Response
    {
        
        
       
        $qrcode = $qrService->qrcode();
        $repository = $doctrine->getRepository(Vente::class);
        $ventes = $repository->findBy(['id'=> $id]);
        
        $html = $this->renderView('vente/liste.html.twig',[
            'ventes' => $ventes,
            
            
        ]);
        
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4','portrait');
        
        $dompdf->render();
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
        
    }









    
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function pdf(): Response
    {
       
            // return $this->render('pdf_generator/index.html.twig', [
            //     'controller_name' => 'PdfGeneratorController',
            // ]);
            $data = [
                'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/img/U835.png'),
                'name'         => 'John Doe',
                'address'      => 'USA',
                'mobileNumber' => '000000000',
                'email'        => 'john.doe@email.com'
            ];
        $html =  $this->renderView('vente/pdf.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4','landscape');
        $dompdf->render();
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
       
    }



    





   
 
    


    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


    

    
}