<?php
//? Ce contrôleur AccountController gère la page du compte utilisateur
//! Controller qui va gérer la gestion du compte des utilisateurs
    //! symfony console make:controller
        //! Nom du controller : AccountController

/*
************************************************************
!           namespace et import des classes                *
************************************************************
*/
namespace App\Controller\Account;
    /*
        - namespace : Definit l'espace de la classe, ici App\Controller indique que cette classe appartient au dossier Controller\Account de l’application Symfony.
    */

use Symfony\Component\HttpFoundation\Response;
    /*
        - Rôle : Fournit un objet représentant la réponse HTTP envoyée au client.
        - Utilité : Retourner des pages HTML, des JSON, ou d’autres types de contenu au client.
    */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    /*
        - Rôle : Classe de base pour tous les contrôleurs Symfony.
        - Utilité : Fournit des méthodes pratiques comme :
            - render() : Pour afficher des vues Twig.
            - getUser() : Pour récupérer l’utilisateur actuellement connecté.
    */

use Symfony\Component\Routing\Attribute\Route; 
    /*
        - Rôle : Déclare une route Symfony, associant une URL spécifique à une méthode de contrôleur.
        - Utilité :
            - Définir des chemins d’accès pour les fonctionnalités du site.
            - Ajouter des noms aux routes pour les référencer facilement dans le code.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class HomeController extends AbstractController 
    // La classe AccountController gère les actions relatives au compte utilisateur
{

/*
************************************************************
!                     Route /compte                        *
************************************************************
*/
    //* Définit la route `/compte`, qui gère la page principale du compte utilisateur.
    #[Route('/compte', name: 'app_account')]
        /* 
            - #[Route(...)] : Définit une route Symfony.
            - '/compte' : URL de la page. Accéder à /compte exécute cette méthode.
            - name: 'app_account' : Nom unique de la route. Utile pour créer des liens (path('app_account') dans Twig).
        */

    //! ** Methode pour la vue twig account ** !//
    //* Affiche la page principale du compte utilisateur.
    public function index(): Response 
    {
        //* Retourne la vue Twig accout/index.html.twig
        return $this->render('account/index.html.twig'); 
    }
        /*
            - index : Nom de la méthode. Représente l'action exécutée pour cette route.
            - : Response : Indique que la méthode retourne un objet Response (page HTML ou JSON à afficher au client).
            - $this->render() est une méthode de AbstractController qui renvoie une vue Twig. 
            - 'account/index.html.twig' : Fichier Twig utilisé pour afficher cette vue.
            - Ici, elle affiche le template 'account/index.html.twig' (la page de compte utilisateur).
        */
}
/*
!Explications supplémentaires :
    *Namespace et Imports :
        - namespace : Organise la classe dans la structure de l'application, évitant ainsi les conflits de noms.
        - use : Importe les classes nécessaires :

    *Routes :
        - La route #[Route('/compte', name: 'app_account')] pointe vers la méthode index() pour afficher la page de compte utilisateur.
        - La route #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')] pointe vers la méthode password() pour gérer le changement de mot de passe.
*/