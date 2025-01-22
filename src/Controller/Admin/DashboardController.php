<?php
//? Contrôleur DashboardController configure le tableau de bord d’administration
//! composer require easycorp/easyadmin-bundle
    //? https://github.com/EasyCorp/EasyAdminBundle
    //? https://symfony.com/bundles/EasyAdminBundle/current/index.html
    //! php bin/console make:admin:dashboard     
        //? https://symfony.com/bundles/EasyAdminBundle/current/dashboards.html


/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller\Admin;
    /*
        - namespace : Définit l'espace de noms où cette classe est organisée. 
            - App\Controller\Admin / indique que ce fichier appartient au dossier Admin dans le répertoire Controller.
    */

use App\Entity\Carrier;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Order;
/*
        - App\Entity\Carrier, User, Product, Category, Header, Order : Ces classes d’entités représentent des tables en base de données pour Carrier, User, Product, Category et Order
    */
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
    /*
        - Symfony\Component\HttpFoundation\Response : Classe de Symfony qui gère les réponses HTTP.
        - Symfony\Component\Routing\Attribute\Route : Classe qui permet d'utiliser des attributs PHP pour définir des routes.
        - EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem : Classe EasyAdmin pour configurer les éléments de menu.
        - Dashboard : Classe pour configurer le tableau de bord.
        - AdminUrlGenerator : Utilisée pour générer des URL d’administration.
        - AbstractDashboardController : Classe de base d’EasyAdmin pour créer des contrôleurs de tableaux de bord.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> Cette ligne déclare la classe UserCrudController, qui gère les opérations CRUD de l’entité User. En héritant de AbstractCrudController, la classe obtient les fonctionnalités CRUD de base pour gérer les utilisateurs.
class DashboardController extends AbstractDashboardController
    /* 
        - class : Mot clé PHP pour déclarer une class
        - DashboardController : Nom de la classe. Ce controleur est dédié à la gestion du tableau de bord d'admin
        - extends AbstractDashboardController : DashboardController herite des fonctionnalités de AbstractDashboardController
    */ 
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //! ** Route de l'url /admin ** !//
    //* -> Lorsque les utilisateurs accèdent à /admin, la methode index(): Response sera exécutée.
    #[Route('/admin', name: 'admin')]
        /*
            - #[Route(...)] : Attribut PHP pour définir une route Symfony, qui lie une URL spécifique à une méthode de contrôleur.
            - '/admin' : Chemin de l’URL. Ici, /admin est l’URL qui active cette méthode (index).
            - name: 'admin' : Nom unique de la route, ici admin, pour faire référence à cette route facilement dans d’autres parties de l’application.
        */
    
    //! ** Méthode qui gere la logique d'affichage du tableau de bord admin ** !//
    //* -> Déclare la méthode index, accessible publiquement, qui retourne un objet Response. Cette méthode gère la logique d’affichage du tableau de bord lorsqu’un utilisateur accède à l’URL /admin.
    public function index(): Response
        /*
            - public : Mot-clé définissant la visibilité de la méthode. public permet à la méthode d’être accessible depuis l’extérieur de la classe.
            - function : Mot-clé pour déclarer une méthode.
            - index : Nom de la méthode. Elle gère l’action par défaut de ce contrôleur (la page principale du tableau de bord).
            - (): Response : Le type de retour de la méthode est Response, un objet Symfony représentant la réponse HTTP.
        */
    
    {
        //* Option 1. You can make your dashboard redirect to some common page of your backend
            // Cette section décrit différentes manières de rediriger l’utilisateur une fois qu’il accède au tableau de bord, selon les besoins spécifiques de l’administration.
        
        //* -> Initialise $adminUrlGenerator en récupérant le service AdminUrlGenerator depuis le conteneur de services de Symfony. Ce service permet de générer des URL pour les pages d’administration.
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            /*
                - $adminUrlGenerator : Variable qui stocke l’instance d’AdminUrlGenerator.
                - $this->container->get(...) : Méthode permettant d’obtenir un service de Symfony, ici AdminUrlGenerator.
                - AdminUrlGenerator::class : Génère des URL pour les pages d’administration en fonction des routes et paramètres du contrôleur.
            */
        //* -> Redirige l’utilisateur vers l’URL générée pour le contrôleur UserCrudController. La méthode setController spécifie le contrôleur de destination, et generateUrl crée l’URL complète pour cette page d’administration.
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
            /*
                - return : Mot-clé qui renvoie une réponse depuis la méthode.
                - $this->redirect(...) : Redirige l’utilisateur vers une URL spécifique.
                - $adminUrlGenerator->setController(UserCrudController::class) : Configure AdminUrlGenerator pour rediriger vers UserCrudController.
                - generateUrl() : Génère l’URL complète à partir des paramètres définis et de la configuration d’EasyAdmin.
            */

        //* Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        //* Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    //! ** Méthode pour configurer les options generales pour le tableau de bord admin ** !//
    //* -> Déclare la méthode configureDashboard, qui retourne un objet Dashboard. Cette méthode permet de configurer des options générales pour le tableau de bord d’administration, telles que le titre.
    public function configureDashboard(): Dashboard
    {
        //* -> Crée une nouvelle instance de Dashboard et définit son titre à Luch Vintage, qui sera affiché en haut de l’interface du tableau de bord.
        return Dashboard::new()
            ->setTitle('Luch Vintage');
    }
        /*
            - configureDashboard : Méthode qui configure le tableau de bord en définissant des options globales.
            - () : Dashboard : Indique que la méthode retourne un objet Dashboard avec les options de configuration.
            - Dashboard::new() : Crée une nouvelle instance de Dashboard.
            - ->setTitle('Luch Vintage') : Définit le titre du tableau de bord comme Luch Vintage.
        */

    //! ** Méthode pour configurer les elements du menu lateral du tableau de bord admin ** !//
    //* -> Déclare la méthode configureMenuItems, qui retourne un itérable (généralement une liste d’éléments). Cette méthode est utilisée pour configurer les éléments du menu latéral du tableau de bord d’administration.
    public function configureMenuItems(): iterable
        /*
            - configureMenuItems : Méthode qui configure les éléments de menu affichés dans la barre latérale d’administration.
            - : iterable : Type de retour de la méthode, indiquant qu’elle retourne une liste d’éléments itérables (comme un tableau).
        */
    {
        //* -> Ajoute un élément de menu qui renvoie à la page principale du tableau de bord. linkToDashboard crée le lien, Dashboard est le libellé du menu, et fa fa-home est la classe CSS qui affiche une icône de maison.
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
            /*
                - yield : Mot-clé PHP pour retourner des valeurs dans une fonction génératrice.
                - MenuItem::linkToDashboard(...) : Crée un lien vers le tableau de bord principal.
                - Dashboard : Libellé du lien.
                - fa fa-home : Classe d’icône CSS (FontAwesome) pour afficher une icône de maison.
            */

        //* -> Ajoute un élément de menu pour accéder à la page CRUD (Create, Read, Update, Delete) de l’entité User. Utilisateurs est le libellé du menu, fas fa-list est la classe CSS pour une icône de liste, et User::class spécifie que cette page est dédiée à la gestion de l’entité User.
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
            /*
                - MenuItem::linkToCrud(...) : Crée un lien vers une page CRUD pour l’entité User.
                - Utilisateurs : Libellé du lien dans le menu.
                - fas fa-list : Classe d’icône CSS (FontAwesome) pour une icône de liste.
                - User::class : Spécifie que ce lien est associé à l’entité User, dirigeant vers sa gestion CRUD.
            */
        //* -> Ajout d'un lien vers la page CRUD pour l'entité Category avec l'icône de liste.
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);

        //* -> Ajout d'un lien vers la page CRUD pour l'entité Product avec l'icône de liste.
        yield MenuItem::linkToCrud('Produits', 'fas fa-list', Product::class);
            /*
                - 'Catégories' et 'Produits' : Libellés des liens.
                - 'fas fa-list' : Icône de liste pour chaque entrée de menu.
                - Category::class et Product::class : Associe chaque lien aux entités Category et Product.
            */
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-list', Carrier::class);
            /*
                - yield :
                    - Role : Le mot-clé yield est utilisé pour generer des valeurs dans une methode ou une fonction. Dans ce cas pour definir un element dans un menu EasyAdmin
                    - Particularité : Contrairement à return, qui termine l'execution d'une methode, yield permet de generer plusieurs valeurs succesivement
                - MenuItem::linkToCrud
                    - Role : C'est une methode static de la class MenuItem. Elle permet de créer un element dans le menu EasyAdmin qui pointe vers une page CRUD   
                    - Parametres :
                        - 'Transporteurs' : Texte affiché dans le menu, ce sera visible dans l'interface d'administration
                        - 'fas fa-list' : Nom de l'icone a afficher a coté du texte dans le menu. c'est une class CSS de FontAwesome (ici une icone de liste)
                        - Carrier::class : Spécifie l'entité ou le contrôleur CRUD associé. Ici, Carrier::class pointe vers l'entité Carrier, qui doit être gérée par un contrôleur CRUD. Symfony utilise ce nom pour lier l'élément de menu au contrôleur CRUD correspondant.
            */
        yield MenuItem::linkToCrud('Commandes', 'fas fa-list', Order::class);
            /*
                - Parametres :
                    - ' Commande ' : Text affiché dans le menu, visible dans l'interface d'administration
                    - Order::class : Specifie l'entité ou le controleur CRUD associé. Ici il point vers l'entité Order pour qui symfony et easyadmin gere par un CRUD controller (OrderCrudController.php)
            */
        yield MenuItem::linkToCrud('Header', 'fas fa-list', Header::class);

    }
}

/* 
!Explications supplémentaires :
    * DashboardController : 
        - Ce contrôleur gère l'affichage du tableau de bord principal et redirige vers la gestion des utilisateurs.
        - Il configure l’interface principale de l’administration :
        - Il redirige par défaut vers la gestion des utilisateurs (UserCrudController).
        - Définit un titre de tableau de bord et des éléments de menu pour accéder à la page principale (Dashboard) et à la gestion CRUD des utilisateurs. Les classes et méthodes importées facilitent l’organisation et la navigation dans l’administration, tout en permettant une personnalisation via les icônes et les libellés.

    * index() : 
        - Cette méthode est appelée lorsque l’utilisateur accède à /admin. Elle redirige vers UserCrudController, permettant de commencer par la gestion des utilisateurs.

    * configureDashboard() : 
        - Cette méthode personnalise l'interface globale du tableau de bord en définissant un titre.

    * configureMenuItems() : 
        - Crée un menu de navigation pour le tableau de bord avec des liens vers les pages CRUD pour chaque entité (User, Category, Product).

    */
