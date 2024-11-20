<?php
//? contrôleur de CRUD pour l’entité Category

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
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
    /*
        - use : Importation de classes nécessaires à l'exécution du contrôleur.
            - App\Entity\Category : Classe de l'entité Category, utilisée pour manipuler les catégories.
            - Crud : Classe permettant de configurer les options CRUD (Create, Read, Update, Delete) d'EasyAdmin.
            - AbstractCrudController : Classe de base pour gérer les contrôleurs CRUD avec EasyAdmin.
            - TextField et SlugField : Types de champs disponibles dans EasyAdmin pour configurer l'interface des formulaires.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class CategoryCrudController extends AbstractCrudController
    /*
        - class : Déclare une nouvelle classe PHP.
        - CategoryCrudController : Nom de la classe, spécifique à EasyAdmin, qui permet de gérer les opérations CRUD (Create, Read, Update, Delete) sur l'entité Category.
        - extends AbstractCrudController : Hérite de la classe AbstractCrudController, fournissant des fonctionnalités CRUD standards.
    */
{


/*
************************************************************
!                      METHODES                            *
************************************************************
*/
    //! ** Méthode pour obtenir le nom de classe complet de l'entité Category ** !//
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }
        /*
            - public static : La méthode est publique et peut être appelée sans instancier la classe.
            - getEntityFqcn : Méthode obligatoire, utilisée par EasyAdmin pour connaître l'entité associée au contrôleur.
            - Category::class : Retourne le nom complet de la classe Category (FQCN - Fully Qualified Class Name) pour que le contrôleur sache quelle entité il doit gérer.
        */

    //! ** Méthode permettant de configurer des options spécifiques pour l'affichage du CRUD. ** !//
    public function configureCrud(Crud $crud): Crud
    {
        //* Renvoie l’objet $crud configuré.
        return $crud
            /* 
                - return : Mot clé qui renvoie une valeur depuis une methode
                - $crud : Objet Crud contenant les options de configuration pour l'interface CRUD
            */
            //* Définit le label au singulier pour l'entité
            ->setEntityLabelInSingular('Catégorie')
                /*
                    - -> : Operateur d'acces a une methode ou propriété d'un objet
                */

            //* Définit le label au pluriel pour l'entité
            ->setEntityLabelInPlural('Categories')
        ;
    }
        /*
            - public function : Déclare une méthode publique, accessible depuis l'extérieur de la classe.
            - configureCrud : Permet de configurer des options spécifiques pour l'interface CRUD.
            - Crud $crud : Paramètre de type Crud, permettant de modifier les paramètres d'affichage.
            - setEntityLabelInSingular : Définit le libellé au singulier pour l'entité, ici "Catégorie".
            - setEntityLabelInPlural : Définit le libellé au pluriel, ici "Categories".
        */

    //! ** Méthode qui configure les champs affichés dans les différentes pages CRUD ** !//
    public function configureFields(string $pageName): iterable
        /*
            - string $pageName : Paramètre indiquant le nom de la page (par exemple, 'index', 'edit').
            - : iterable : Type de retour, signifiant que la méthode retourne un ensemble de champs sous forme d’itérable.
        */
    {
        return [
            //* Champ de texte pour le nom de la catégorie (Crée un champ texte lié à l'attribut name de l'entité Category.)
            TextField::new('name')
                ->setLabel('Titre') // Définit un label pour le champ
                ->setHelp("Titre de la catégorie"), // Ajoute un message d’aide pour l'utilisateur
                    /*
                        - TextField::new('name') : Crée un champ de type texte pour l’attribut name de Category.
                        - ->setLabel('Titre') : Définit le libellé affiché pour le champ, ici Titre.
                        - ->setHelp("Titre de la catégorie") : Ajoute une aide contextuelle affichée sous le champ.
                    */

            //* Champ slug pour générer automatiquement une URL basée sur le nom (Crée un champ slug (URL) lié à l'attribut slug de l'entité Category.)
            SlugField::new('slug')
                ->setLabel('URL') // Définit un label pour le champ
                ->setTargetFieldName('name') // Génère le slug en fonction du champ 'name'
                ->setHelp("URL de vote catégorie générée automatiquement"), // Message d'aide pour l'utilisateur
                    /*
                        - SlugField::new('slug') : Crée un champ de type Slug pour l’attribut slug de Category.
                        - ->setLabel('URL') : Définit le libellé affiché pour le champ, ici URL.
                        - ->setTargetFieldName('name') : Spécifie que le champ slug sera généré automatiquement à partir du champ name.
                        - ->setHelp("URL de votre catégorie générée automatiquement") : Ajoute un texte d’aide pour expliquer que l’URL est générée automatiquement.
                    */
        ];
    }
}
/*
!Explications supplémentaires :
    *1. Namespace et imports
        - Namespace : App\Controller\Admin
            - Définit que cette classe appartient au module Admin de l'application.
        - Imports :
            - Entity Category : Permet de gérer les données des catégories (ex. : CRUD).
            - EasyAdmin Config & Fields : Fournit les outils pour configurer et personnaliser les interfaces CRUD via EasyAdmin.

    *2. Structure du Controller
        *2.1. Classe
            - CategoryCrudController hérite de AbstractCrudController :
            - Gère automatiquement les opérations CRUD pour l'entité Category grâce à EasyAdmin.
        *2.2. Méthodes principales
            - getEntityFqcn()
                - Rôle : Retourne le Fully Qualified Class Name de l'entité gérée par ce CRUD.
                - Utilisation : EasyAdmin utilise cette méthode pour savoir sur quelle entité (ici Category) appliquer le CRUD.
            - configureCrud()
                - Rôle : Configure des options globales pour l’interface CRUD de l’entité.
                - Paramètre :
                    - $crud : Instance de la classe Crud.
                - Exemples d’options :
                    - setEntityLabelInSingular('Catégorie') : Définit le label au singulier pour l'entité.
                    - setEntityLabelInPlural('Categories') : Définit le label au pluriel.
            - configureFields()
                - Rôle : Configure les champs affichés dans les pages CRUD (index, edit, new, etc.).
                - Paramètre :
                    - $pageName : Nom de la page (index, edit, new).
                - Retour : Une liste de champs sous forme d’itérable.
        
    * 3. Champs configurés dans configureFields()
        - TextField::new('name')
            - Crée un champ texte pour le nom de la catégorie.
            - Options personnalisées :
                - setLabel('Titre') : Change le libellé affiché du champ en "Titre".
                - setHelp('Titre de la catégorie') : Ajoute un message d'aide pour guider l'utilisateur.
        - SlugField::new('slug')
            - Crée un champ slug pour générer automatiquement une URL à partir du champ name.
            - Options personnalisées :
                - setLabel('URL') : Libellé du champ.
                - setTargetFieldName('name') : Spécifie que le slug sera généré automatiquement à partir du champ name.
                - setHelp('URL de votre catégorie générée automatiquement') : Explique l'objectif du champ.

    *Le CategoryCrudController est un contrôleur, qui gère les opérations CRUD pour l'entité Category. Grâce à EasyAdmin, les options de configuration, telles que les libellés, les champs ou les actions, sont facilement personnalisables pour répondre aux besoins spécifiques.
*/