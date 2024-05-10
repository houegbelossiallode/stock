<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    
    
    
    public function __construct(EmailVerifier $emailVerifier,private VerifyEmailHelperInterface $verifyEmailHelper,
    private MailerInterface $mailer,private EntityManagerInterface $entityManager
    )
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $rand = chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). chr(mt_rand(ord('A'), ord('Z'))). sprintf('%04d', mt_rand(0,999));
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $code = $rand;
            $user->setMatricule($code);
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('houegbelossiallodegoldfroy@gmail.com', 'Goldfroy'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    
            );
            // do anything else you need here, like send an email
            $this->addFlash('success', "Un message de confirmation a été envoyé à l'adresse  " . $user->getEmail() . " et vôtre matricule est " . $code);
            return $this->redirectToRoute('app_login');
            
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator,UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('email-verified', 'Vôtre adresse email a été vérifié .');

        return $this->redirectToRoute('app_dashboard');
    }


    #[Route('/verify/resent', name: 'resend_verif')]
    public function RenvoiUserEmail(UserInterface $user): Response
    {
        
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
            ->from(new Address('houegbelossiallodegoldfroy@gmail.com', 'Goldfroy'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig'));
            $this->addFlash('success', "Un message de confirmation a été envoyé à l'adresse  " . $user->getEmail());
            return $this->redirectToRoute('app_dashboard');
    }


 
    #[Route('/verify/request', name: 'app_request_verification_email')]
    public function requestVerificationEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        if ($user->isVerified() && $user->hasRole("ROLE_MEMBER")) {
            $this->addFlash('already_verified', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('info@anteriority.ch', 'Account Confirmation'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );

        $this->addFlash('success', 'New verification link has been sent');

        return $this->redirectToRoute('app_register');
    }








    
}