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
    
    //! ** Méthode qui gere la logique d'affichage du tableau de bord admin ** !//
    //* -> Déclare la méthode index, accessible publiquement, qui retourne un objet Response. Cette méthode gère la logique d’affichage du tableau de bord lorsqu’un utilisateur accède à l’URL /admin.
    public function index(): Response
    
    {
        //* -> Initialise $adminUrlGenerator en récupérant le service AdminUrlGenerator depuis le conteneur de services de Symfony. Ce service permet de générer des URL pour les pages d’administration.
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            /*
                - $adminUrlGenerator : Variable qui stocke l’instance d’AdminUrlGenerator.
                - $this->container->get(...) : Méthode permettant d’obtenir un service de Symfony, ici AdminUrlGenerator.
                - AdminUrlGenerator::class : Génère des URL pour les pages d’administration en fonction des routes et paramètres du contrôleur.
            */
        //* -> Redirige l’utilisateur vers l’URL générée pour le contrôleur UserCrudController. La méthode setController spécifie le contrôleur de destination, et generateUrl crée l’URL complète pour cette page d’administration.
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    //! ** Méthode pour configurer les options generales pour le tableau de bord admin ** !//
    //* -> Déclare la méthode configureDashboard, qui retourne un objet Dashboard. Cette méthode permet de configurer des options générales pour le tableau de bord d’administration, telles que le titre.
    public function configureDashboard(): Dashboard
    {
        //* -> Crée une nouvelle instance de Dashboard et définit son titre à Luch Vintage, qui sera affiché en haut de l’interface du tableau de bord.
        return Dashboard::new()
            ->setTitle('Luch Vintage');
    }

    //! ** Méthode pour configurer les elements du menu lateral du tableau de bord admin ** !//
    //* -> Déclare la méthode configureMenuItems, qui retourne un itérable (généralement une liste d’éléments). Cette méthode est utilisée pour configurer les éléments du menu latéral du tableau de bord d’administration.
    public function configureMenuItems(): iterable
    {
        //* -> Ajoute un élément de menu qui renvoie à la page principale du tableau de bord. linkToDashboard crée le lien, Dashboard est le libellé du menu, et fa fa-home est la classe CSS qui affiche une icône de maison.
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        //* -> Ajoute un élément de menu pour accéder à la page CRUD (Create, Read, Update, Delete) de l’entité User. Utilisateurs est le libellé du menu, fas fa-list est la classe CSS pour une icône de liste, et User::class spécifie que cette page est dédiée à la gestion de l’entité User.
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);

        //* -> Ajout d'un lien vers la page CRUD pour l'entité Category avec l'icône de liste.
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);

        //* -> Ajout d'un lien vers la page CRUD pour l'entité Product avec l'icône de liste.
        yield MenuItem::linkToCrud('Produits', 'fas fa-list', Product::class);

        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-list', Carrier::class);

        yield MenuItem::linkToCrud('Commandes', 'fas fa-list', Order::class);

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
