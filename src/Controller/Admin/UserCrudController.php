<?php
//? Contrôleur UserCrudController configure une interface d’administration pour l’entité User via EasyAdmin.
//! php bin/console make:admin:crud
    // Commande Symfony pour générer automatiquement un contrôleur CRUD (Create, Read, Update, Delete) pour l'administration.
    //? https://symfony.com/bundles/EasyAdminBundle/current/crud.html


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
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    /*
        - use App\Entity\User; : Indique que ce contrôleur gère l'entité User.
        - use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; : Permet de configurer les options d'affichage et de comportement pour les opérations CRUD.
        - use EasyCorp\Bundle\EasyAdminBundle\Field\TextField; : Permet d'ajouter et de configurer des champs texte dans l'interface CRUD.
        - use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController; : Fournit une base pour construire un contrôleur CRUD sur mesure pour l'entité User.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> Declare la classe UserCrudController, qui gère les opérations CRUD de l’entité User. En héritant de AbstractCrudController, la classe obtient les fonctionnalités CRUD de base pour gérer les utilisateurs.
class UserCrudController extends AbstractCrudController
    /*
        - UserCrudController : Nom de la classe, utilisée pour gérer les opérations CRUD sur l'entité (User)
        - extends AbstractCrudController : La classe UserCrudController hérite des fonctionnalités de AbstractCrudController, ce qui permet de bénéficier des méthodes CRUD de base.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //! ** Méthode statique qui retourne le nom complet de la classe de l'entité associée au CRUD  ** /
    //* -> Méthode statique qui retourne une chaîne de caractères représentant le nom complet de la classe associée à ce CRUD, ici User::class. EasyAdmin utilise cette méthode pour savoir quelle entité ce CRUD gère.
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
        /*
            - public static function : methode public et static, donc accessible sans instancier la class
            - getEntityFqcn() : Nom de la méthode qui retourne le nom complet de l'entité liée au CRUD (User ici).
            - string : cette methode retourne une chaine de caracteres
            - return User::class; : Retourne le nom complet de la classe User. Cela permet à EasyAdmin de savoir que ce CRUD gère l'entité User.
        */

    //! ** Méthode pour configurer l’apparence et les libellés dans l’interface CRUD  ** !//
    public function configureCrud(Crud $crud): Crud
        /* 
            - configureCrud() : Méthode permettant de configurer des options spécifiques pour l'affichage du CRUD.
            - (Crud $crud) :Le parametre $crud est une instance de la classe Crud, permettant d'accéder aux options de configuration.
            - Crud : Type de retour de la methode, indiquant qu'elle retourne uin objet de type Crud
        */
    {
        return $crud
            /*  
                - return : Mot clé qui renvoie une valeur depuis une methode
                - $crud : Objet Crud contenant les options de configuration pour l'interface CRUD
            */

            //* -> Définit le label au singulier de l’entité comme "Utilisateur".
            ->setEntityLabelInSingular('Utilisateur')
                /* 
                    - -> : Operateur d'acces a une methode ou propriété d'un objet
                    - setEntityLabelInSingular : Methode de Crud qui definit le libellé de l'entité au singulier ici Utilisateur
                */

            //* -> Définit le label au pluriel comme "Utilisateurs".
            ->setEntityLabelInPlural('Utilistaurs');
                /*
                    - setEntityLabelInPlural : Méthode de Crud qui définit le libellé de l’entité au pluriel, ici "Utilisateurs".
                */
        ;
    }

    //! ** Méthode pour configurer les champs affichés dans l’interface CRUD  ** /
    //* -> Cette méthode retourne un ensemble de champs affichés dans l’interface CRUD. Chaque champ est configuré pour apparaître dans l’interface selon la page (index, detail, edit, etc.) :
    public function configureFields(string $pageName): iterable
        /* 
            - configureFields : Méthode qui configure les champs visibles et modifiables dans l’interface CRUD.
            - (string $pageName) : Paramètre pageName de type string indiquant le nom de la page (comme index, detail, etc.) où s’applique cette configuration.
            - : iterable : Indique que cette méthode retourne une valeur itérable (un tableau ou une liste d’éléments).
        */
    {
        //* Quelles champs l'administrateur peux changer
        return [
            /* 
                - return : Mot-clé pour renvoyer la liste des champs configurés.
                - [ ... ] : Les crochets définissent un tableau contenant les champs à afficher ou modifier dans l’interface.
            */

            //* -> Crée un champ de texte pour le prénom de l’utilisateur avec le label "Prénom".
            TextField::new('firstname')->setLabel('Prénom'),
                /* 
                    - TextField::new('firstname') : Crée un nouveau champ de texte pour la propriété firstname de l’entité User.
                    - setLabel('Prénom') : Définit le libellé de ce champ dans l’interface comme "Prénom".
                */

            //* -> Crée un champ de texte pour le nom de l’utilisateur avec le label "Nom".
            TextField::new('lastname')->setLabel('Nom'),
                /*
                    - TextField::new('lastname') : Crée un champ de texte pour la propriété lastname.
                    - setLabel('Nom') : Définit le libellé de ce champ comme "Nom".
                */

            //* -> Crée un champ de texte pour l’email, affiché uniquement sur la page d’index, avec le label "Email".    
            TextField::new('email')->setLabel('Email')->onlyOnIndex(),
                /*
                    - TextField::new('email') : Crée un champ de texte pour la propriété email.
                    - setLabel('Email') : Définit le libellé du champ comme "Email".
                    - onlyOnIndex() : Spécifie que ce champ est visible uniquement sur la page d’index (liste des utilisateurs), pas dans les formulaires de création ou d’édition.
                */
        ];
    }
}

    /* 
    !Explications supplémentaires :
    * Ce contrôleur UserCrudController configure une interface d’administration CRUD pour l’entité User. Il utilise EasyAdmin pour afficher et modifier des utilisateurs, et spécifie quels champs doivent apparaître dans l’interface (avec TextField pour firstname, lastname, email). Les méthodes et paramètres personnalisent les libellés et les champs visibles selon les pages de l’administration (index, edit, etc.).
    
    * getEntityFqcn() :
        - Retourne le nom complet de la classe User. Cette méthode indique à EasyAdmin que ce contrôleur gère les opérations CRUD pour l'entité User.

    * configureCrud(Crud $crud): Crud :
        - Configure l’apparence et les options de l’interface CRUD.
        - setEntityLabelInSingular('Utilisateur') : Définit le label de l’entité au singulier.
        - setEntityLabelInPlural('Utilisateurs') : Définit le label de l’entité au pluriel pour faciliter la navigation dans l’interface.

    *  configureFields(string $pageName): iterable :
        - Configure les champs visibles dans l'interface CRUD.
        - TextField::new('firstname')->setLabel('Prénom') : Crée un champ de texte pour la propriété firstname avec le label "Prénom".
        - TextField::new('lastname')->setLabel('Nom') : Crée un champ pour la propriété lastname avec le label "Nom".
        - TextField::new('email')->setLabel('Email')->onlyOnIndex() : Crée un champ pour la propriété email avec le label "Email", visible uniquement dans la page d’index (liste des utilisateurs) et non dans les pages de création ou de modification.
    */

