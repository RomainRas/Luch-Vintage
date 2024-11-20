<?php
//? Controleur qui gere la route de la connexion et de la deconnnexion
//? https://symfony.com/doc/current/security.html#form-login
//! Formulaire qui gere la connexion
    //! symfony console:make controller
        //! Name of the form class : LoginController
            //! Nom de l'entity lié : User
// Ce lien mène à la documentation officielle de Symfony concernant la gestion de l'authentification via un formulaire de login.

/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
    /*
        - Déclare le namespace pour organiser la classe LoginController dans l'espace App\Controller, qui contient tous les contrôleurs de l'application.
        - Importe la classe AbstractController de Symfony, qui fournit des méthodes utiles comme render() pour afficher les vues.
        - Importe la classe Response, qui encapsule la réponse HTTP que le serveur envoie au navigateur (comme le HTML d'une page).
        - Importe l'attribut Route, qui est utilisé pour définir des routes dans l'application Symfony.
        - Importe la classe AuthenticationUtils, qui permet de récupérer des informations sur les tentatives de connexion, comme les erreurs d'authentification et le dernier nom d'utilisateur saisi.
    */

/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> La classe LoginController gère les actions liées à la connexion et à la déconnexion des utilisateurs.
class LoginController extends AbstractController
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Définit la route de connexion, accessible à l'URL `/login`.
    #[Route('/login', name: 'app_login')]
            /*
                - Route associée à l'URL '/login'. Cette route correspond à la page de connexion.
                - 'name: app_login' permet de nommer cette route, facilitant la création de liens vers celle-ci dans le code.
            */
    //! ** Méthode pour afficher le formulaire de connexion et gérer les erreurs éventuelles. ** !//
    public function index(AuthenticationUtils $authenticationUtils): Response
        /*
            - La méthode index gère l'affichage du formulaire de connexion et les erreurs d'authentification.
            - Elle prend en paramètre un objet AuthenticationUtils qui permet de récupérer les erreurs de connexion et le dernier nom d'utilisateur utilisé.    
        */
    {
        //* -> Récupère la dernière erreur d'authentification, s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
            /*
                - getLastAuthenticationError() renvoie l'erreur d'authentification, s'il y en a une (ex: mauvais mot de passe).
                - Si aucune erreur n'a eu lieu, cette variable sera `null`.
            */
        //* -> Récupérer le dernier nom d'utilisateur (email) saisi
        $lastUsername = $authenticationUtils->getLastUsername();
            /* 
                - getLastUsername() permet de récupérer le dernier nom d'utilisateur (email) saisi dans le formulaire de connexion.
            */
        return $this->render('login/index.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
            /*
                - Cette méthode render() affiche le template Twig 'login/index.html.twig', qui représente la page de connexion.
                - Elle passe deux variables au template :
                - 'error' => $error : Pour afficher un message d'erreur si la connexion échoue.
                - 'last_username' => $lastUsername : Pour pré-remplir le champ email avec le dernier nom d'utilisateur saisi.
            */
    }

    //* -> Définit la route de déconnexion, accessible via l'URL `/deconnexion`.
    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
        /*
            - Route associée à l'URL '/deconnexion'. Cette route permet de gérer la déconnexion de l'utilisateur.
            - Le nom 'app_logout' est donné à cette route pour la référencer dans le code.
            - La méthode est uniquement accessible en GET, ce qui signifie que l'URL ne peut être atteinte qu'en accédant directement à l'URL, sans soumettre de formulaire.
        */
        /**
         * @Route("/logout",name="logout")
         */
    //! ** La méthode `logout()` est définie pour gérer la déconnexion de l'utilisateur. ** /
    public function logout(): never
        /*
            - Méthode responsable de la déconnexion. Elle ne retourne jamais de valeur (type `never`).
            - En Symfony, cette méthode ne doit pas avoir de logique particulière car la déconnexion est gérée automatiquement par le système de sécurité défini dans le fichier `security.yaml`.
        */
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
            /*
                - Cette méthode ne sera jamais exécutée, mais Symfony nécessite sa définition pour savoir que cette route existe.
                - Un message d'erreur est jeté ici pour rappeler d'activer la déconnexion dans le fichier de configuration `security.yaml`.
            */
    }
}
/*
!Explications supplémentaires :
* Route /login et méthode index() :
    - La route #[Route('/login', name: 'app_login')] déclenche la méthode index() pour afficher le formulaire de connexion.
    - AuthenticationUtils est utilisé pour :
        - getLastAuthenticationError() : obtenir l'erreur de connexion, si présente.
        - getLastUsername() : obtenir le dernier nom d'utilisateur saisi pour pré-remplir le champ de connexion.
    - Le formulaire est rendu par login/index.html.twig, auquel sont passées les informations d'erreur et le dernier nom d'utilisateur saisi.

* Route /deconnexion et méthode logout() :
    - La route #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])] permet à l’utilisateur de se déconnecter.
    - La méthode logout() ne contient aucune logique, car la déconnexion est configurée dans security.yaml.
    - Un message d’erreur est jeté pour rappeler l'activation de la déconnexion dans security.yaml, indiquant qu'il s'agit d'une configuration nécessaire.

* Résumé
    - Ce contrôleur gère la connexion et la déconnexion des utilisateurs :
        - La méthode index() affiche le formulaire de connexion et gère les erreurs.
        - La méthode logout() permet la déconnexion, gérée automatiquement par Symfony via security.yaml.
*/