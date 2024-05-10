<?php

namespace App\Controller;

use App\Form\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;



class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(Request $request,MailerInterface $mailer,SluggerInterface $slugger): Response
    {
        


        
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $image = $form->get('image')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move(
                    $this->getParameter('article_directory'),
                    $newFilename
                );
            } catch (FileException  $e) {
                // ... handle exception if something happens during file upload
            }
            
            $email = (new Email())
            ->from(new Address('houegbelossiallodegoldfroy@gmail.com', 'Isgostage'))
            ->to('houegbelossi@gmail.com')
            ->subject('Demande D\'information')
            ->html('<p>Bonjour</p>')
            ->addPart( new DataPart(new File($this->getParameter('article_directory'). '/' . $newFilename)));
            $mailer->send($email);

            $this->addFlash('notice','Votre message a bien été envoyer'); 

        }
        
        return $this->render('email/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}