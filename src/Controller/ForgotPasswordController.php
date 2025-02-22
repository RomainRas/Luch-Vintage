<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Form\ForgotPasswordFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private $entityManagerInterface;
    
    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->entityManagerInterface = $entityManagerInterface;
    }
    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        //* -> 1. Formulaire
        $form = $this->createForm(ForgotPasswordFormType::class);

        $form->handleRequest($request);

        //* -> 2. Traitement du formulaire
        if($form->isSubmitted() && $form->isValid()){

            //* -> 3. Verif si l'email renseigne par l'user est en BDD et du message flash vague pour eviter tout recherche d'info
            // dd($form->get('email')->getData());
            $email = $form->get('email')->getData();
            // dd($email);
            $user = $userRepository->findOneByEmail($email);
            // dd($user);

            //* -> 4. Envoyer une notification à l'utilisateur
            $this->addFlash('success', "Si votre adresse email existe, vous recevrez un mail pour réinitialiser votre mot de passe.");

            //* -> 5. Si user existe, on reset le password et on envoie par email le nouveau mot de passe
            if($user){
                //* -> 5.a Créer un token qu'on va stocker en bdd dans notre entité User
                $token = bin2hex(random_bytes(15));
                // dd($token);
                $user->setToken($token);

                $date = new DateTime();
                $date->modify('+10 minutes');
                // dd($date);
                $user->setTokenExpireAt($date);

                $this->entityManagerInterface->flush();
                // dd($user);
                
                //* -> 5.b Envoi du mail
                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl('app_password_reset',['token'=>$token], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
                $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),'Modification de votre mot de passe', "forgotpassword.html", $vars);
            }
        };

        
        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe-oublie/reset/{token}', name: 'app_password_reset')]
    public function reset(Request $request, UserRepository $userRepository ,$token): Response
    {   
        //* -> 1. Verification si le token existe : etant donné que le token passe par l'url il faut que ce soit en token qui a bien été genéré
        if(!$token){
            return $this->redirectToRoute('app_password');
        }

        //* -> Verification si l'user existe et qu'il a bien un token, donc qu'il a bien fait la demande
        $user = $userRepository->findOneByToken($token);

        //* -> Verification si le token n'a pas expiré
        $now = new DateTime();
        // dump($now);
        // dump($user);
        if (!$user || $now > $user->getTokenExpireAt()) {
            return $this->redirectToRoute('app_password');
        }
        // die('Tout est ok');
        
        //* -> 1. Generation du formulaire
        $form = $this->createForm(ResetPasswordFormType::class, data: $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //* -> 2. Traitement du formulaire
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            
            //* -> 2.a Envoi en bdd
            $this->entityManagerInterface->flush();

            //* -> 2.b Message succes
            $this->addFlash(
                'success',
                "Votre mot de passe est correctement mis à jour."
            );

            //* -> 2.c Envoi d'un mail de confirmation avec ( attention si cette procedure n'est pas de vous etc etc)
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password/reset.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
