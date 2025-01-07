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

use App\Repository\OrderRepository;
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
    public function index(OrderRepository $orderRepository): Response 
    {
        /*
            - public : La méthode est accessible depuis l'extérieur de la classe (par exemple, lorsqu'une route appelle cette méthode).
            - function : Déclare une fonction ou une méthode.
            - index : Nom de la méthode. Généralement utilisée pour représenter la page principale d'une section.
            - (OrderRepository $orderRepository) : Injection de dépendance. Le repository OrderRepository est automatiquement injecté par Symfony.
            - : Response : Indique que cette méthode retourne un objet Response, qui contient le contenu de la réponse HTTP envoyée au client (par exemple, une page HTML).
        */
        $orders = $orderRepository->findBy([
            'user' => $this->getUser(),
            'state' => [2,3]
        ]);
            /*
                - $orderRepository : Instance du repository OrderRepository, utilisée pour interagir avec la table order de la base de données
                - findBy([...]) : Methode Doctrine permettant de recuperer une liste d'entité correspondant à certains criteres
                    Criteres de recherches =
                        - 'user' => $this->getUser()
                            - user : Champ de la table order.
                            - $this->getUser() : Récupère l'utilisateur actuellement connecté.
                        Cela signifie que la requête va chercher uniquement les commandes de l'utilisateur connecté.
                        - 'state' => [2, 3]
                            - state : Champ de la table order, qui représente l'état de la commande.
                            - [2, 3] : Liste des états à inclure dans les résultats.
                                - 2 : Commande payée.
                                - 3 : Commande expédiée.
                        Cela signifie que seules les commandes ayant un état "payé" ou "expédié" seront récupérées.
            */

        // dd($orders);
        //* Retourne la vue Twig accout/index.html.twig
        return $this->render('account/index.html.twig',[
            'orders' => $orders    
        ]); 
            /*
                - $this->render() est une méthode de AbstractController qui renvoie une vue Twig. 
                - 'account/index.html.twig' : Fichier Twig utilisé pour afficher cette vue.
                - Ici, elle affiche le template 'account/index.html.twig' (la page de compte utilisateur).
            */
    }

}
/*
!Explications supplémentaires :
    *Namespace et Imports :
        - namespace : Organise la classe dans la structure de l'application, évitant ainsi les conflits de noms.
        - use : Importe les classes nécessaires :

    *Routes :
        - La route #[Route('/compte', name: 'app_account')] pointe vers la méthode index() pour afficher la page de compte utilisateur.
        - La route #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')] pointe vers la méthode password() pour gérer le changement de mot de passe.
    
    *Methode : 
        - La méthode index() :
            - Récupère l'utilisateur connecté.
            - Filtre ses commandes selon l'état (payées ou expédiées).
            - Renvoie ces commandes à une vue Twig pour les afficher.
*/