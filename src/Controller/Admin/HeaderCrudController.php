<?php
//! Ce code définit un contrôleur CRUD (Create, Read, Update, Delete) pour l'entité Header dans le but de personnaliser le header via easyadmin
/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller\Admin;
    /*
        - namespace : Organise le code dans des "dossiers virtuels". Ici, la classe appartient à l'espace de noms App\Controller\Admin, ce qui indique qu'elle se trouve dans le répertoire Admin sous Controller.
    */
use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    /*
        - App\Entity\Header : 
            - C'est une classe qui représente l'entité Header (correspondant à une table dans la base de données).
            - Ce CRUD est associé à cette entité pour permettre sa gestion dans l'administration.
        - TextField, TextareaField, ImageField :
            - Ces classes représentent des champs d'EasyAdmin.
                - TextField : Champ pour des textes simples (par exemple, le titre).
                - TextareaField : Champ pour des textes longs (par exemple, une description).
                - ImageField : Champ utilisé pour gérer des images (comme une image d'en-tête).
        - AbstractCrudController :
            - C'est une classe de base fournie par EasyAdmin. En héritant de cette classe, votre contrôleur CRUD obtient automatiquement des fonctionnalités pour gérer les opérations CRUD.
    */
/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class HeaderCrudController extends AbstractCrudController
    /*
        - class : Définit une classe PHP.
        - HeaderCrudController : Le nom de la classe, dédiée à la gestion CRUD de l'entité Header.
        - extends AbstractCrudController :
            - Cela signifie que cette classe hérite des fonctionnalités de la classe AbstractCrudController.
            - Elle simplifie la gestion des opérations CRUD pour l'entité Header.
    */
{
/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }
        /*
            getEntityFqcn() :
                - Méthode statique obligatoire dans un contrôleur CRUD EasyAdmin.
                - Elle retourne le FQCN (Fully Qualified Class Name) de l'entité gérée par ce CRUD.
            return Header::class :
                - Indique que ce CRUD est associé à l'entité Header.
        */

    
    public function configureFields(string $pageName): iterable
        /*
            configureFields :   
                - Permet de définir quels champs seront affichés et comment ils seront configurés dans l'interface d'administration.
            string $pageName : 
                - Le paramètre $pageName contient le nom de la page (par exemple : new, edit, index, detail).
            : iterable :    
                - La méthode retourne un tableau ou une liste itérable contenant les champs à afficher.
        */
    {
        //* Définit une variable $required comme true, qui sera utilisée pour configurer le champ ImageField.
        $required = true;
            /*
                $required :
                    - Cette variable détermine si le champ de téléchargement d'image est requis (obligatoire) ou non.
                    - Par défaut, la variable est définie à true (l'image est obligatoire).
            */

        //* Si la page est 'edit', le champ illustration devient facultatif en définissant $required à false.
        if ($pageName == 'edit') {
            $required = false;
        }
            /*
                Condition if ($pageName == 'edit') :
                    - Si l'utilisateur est sur la page d'édition (edit), l'image devient non obligatoire (parce que l'image peut déjà être présente).
            */
        return [
            //* Champs textuels
            TextField::new('title','Titre'),
            TextareaField::new('content','Contenu'),
            TextField::new('buttonTitle','Titre du bouton'),
            TextField::new('buttonLink','URL du bouton'),
                /*
                    TextField::new('title', 'Titre') :
                        - TextField::new() : Crée un champ texte.
                        - 'title' : Nom de la propriété dans l'entité Header (par exemple, $title).
                        - 'Titre' : Libellé du champ affiché dans l'interface d'administration.
                    TextareaField::new('content', 'Contenu') :
                        - Champ pour les textes longs (par exemple, des descriptions).
                    Autres champs (buttonTitle, buttonLink) :
                        - Représentent respectivement le texte du bouton et l'URL du bouton.
                */
            //* Champ pour télécharger une image.
            ImageField::new('illustration')// Crée un champ pour gérer une image,'illustration' correspond à une propriété de l'entité Header.
                ->setLabel('Image de fond du header') // Définit le label pour l’image
                ->setHelp("Image de fond du header JPG") // Message d’aide pour la taille recommandée de l’image
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]') // Modèle de nom de fichier
                ->setBasePath('/uploads') // Chemin public pour accéder aux images
                ->setUploadDir('/public/uploads') // Répertoire de téléchargement dans le serveur
                ->setRequired($required), // Rend le champ requis ou non selon le contexte
        ];
    }
}
/*
! Résumé global

*Ce contrôleur CRUD permet de gérer facilement l'entité Header dans l'interface d'administration.
    - Il configure les champs pour :
        - Le titre (title).
        - Le contenu (content).
        - Le bouton (titre et URL).
        - L'image d'en-tête (upload d'une image).
            - Le champ d'image est obligatoire uniquement lors de la création (pas lors de l'édition).
            - Les fichiers d'image sont enregistrés dans /public/uploads et accessibles depuis /uploads.
*/