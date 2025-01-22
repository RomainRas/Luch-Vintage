<?php
//? Contrôleur ProductCrudController configure les options et les champs pour la gestion CRUD de l’entité Product

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
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
    /*
        -IdField, TextField, TextEditorField, SlugField, ImageField, NumberField, ChoiceField, AssociationField, BooleanField : Champs disponibles dans EasyAdmin pour configurer les formulaires CRUD (identifiant, texte, éditeur de texte, slug, image, nombre, choix, association).
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class ProductCrudController extends AbstractCrudController
    /*
        - ProductCrudController : Nom de la classe qui gère les opérations CRUD pour l’entité Product.
        - extends AbstractCrudController : Hérite de AbstractCrudController pour utiliser ses fonctionnalités CRUD de base.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //! ** Méthode pour obtenir le nom de la classe de l'entité Product ** !//
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
        /*
            - public static function getEntityFqcn(): string : Méthode publique et statique qui retourne le nom complet (FQCN) de l’entité associée.
            - return Product::class; : Retourne la classe Product pour indiquer que ce contrôleur gère les produits.
        */
    //! ** Méthode pour configurer le CRUD (libellés et autres options) ** !//
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit') // Définit le label au singulier pour le CRUD
            ->setEntityLabelInPlural('Produits'); // Définit le label au pluriel pour le CRUD
    }
        /*
            - public function configureCrud(Crud $crud): Crud : Méthode publique pour configurer des options CRUD.
            - Crud $crud : Paramètre $crud de type Crud pour accéder aux options CRUD.
            - return $crud : Retourne l’objet $crud configuré.
            - ->setEntityLabelInSingular('Produit') : Définit le libellé de l’entité au singulier (Produit).
            - ->setEntityLabelInPlural('Produits') : Définit le libellé de l’entité au pluriel (Produits).
        */
    //! ** Méthode pour configurer les champs affichés dans le CRUD ** !//
    public function configureFields(string $pageName): iterable
        /*
            - string $pageName : Paramètre pour définir la page (par exemple, 'index', 'edit').
            - : iterable : La méthode retourne un ensemble de champs sous forme d’itérable.
        */
    {
        //* Définit une variable $required comme true, qui sera utilisée pour configurer le champ ImageField.
        $required = true;

        //* Si la page est 'edit', le champ illustration devient facultatif en définissant $required à false.
        if ($pageName == 'edit') {
            $required = false;
        }
        return [
            //* Crée un champ de type texte pour l’attribut name.
            TextField::new('name') // Crée un champ de type texte pour l’attribut name.
                ->setLabel('Nom') // Définit le label du champ
                ->setHelp("Nom de votre Produit"), // Message d’aide
                
            //* Champ pour ajouter un produit à la une sur la homepage
            BooleanField::new('isHomepage') 
                /*
                    - BooleanField : Classe fournie par EasyAdmin pour gérer les valeurs booléennes (vrai ou faux). Case a cocher
                    - Méthode new : Crée une nouvelle instance de BooleanField.
                    - 'isHomepage' : Nom de la propriété de l'entité associée à ce champ. Cela correspond à un champ booléen (type boolean ou bool) dans la classe de l'entité.
                */
                ->setLabel('Produit à la une')
                ->setHelp('Vous permet d\'afficher ce produit à la une'),

            //* Champ pour l’URL générée automatiquement.
            SlugField::new('slug') // Le champ slug est généré à partir de name.
                ->setTargetFieldName('name') // Génère le slug à partir du champ 'name'
                ->setLabel('URL') // Label pour le champ slug
                ->setHelp("URL de votre catégorie"),

            //* Champ pour saisir une description avec éditeur de texte.
            TextEditorField::new('description') 
                ->setLabel('Description') // Label pour la description
                ->setHelp("Description de votre produit"), // Message d’aide pour guider l’utilisateur

            //* Champ pour télécharger une image.
            ImageField::new('illustration')
                ->setLabel('Image') // Définit le label pour l’image
                ->setHelp("Image du produit 600x600") // Message d’aide pour la taille recommandée de l’image
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]') // Modèle de nom de fichier
                ->setBasePath('/uploads') // Chemin public pour accéder aux images
                ->setUploadDir('/public/uploads') // Répertoire de téléchargement dans le serveur
                ->setRequired($required), // Rend le champ requis ou non selon le contexte

            //* Champ pour saisir le prix hors taxes.
            NumberField::new('price')
                ->setLabel('Prix H.T') // Label pour le champ de prix
                ->setHelp("Prix H.T du produit sans le sigle €"), // Message d’aide pour indiquer le format du prix

            //* Champ pour sélectionner un taux de TVA.
            ChoiceField::new('tva')
                ->setLabel('Taux de TVA') // Définit le label pour le choix de la TVA
                ->setChoices([ // Définit les options de choix pour le champ, avec les taux de TVA disponibles.
                    '5,5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20'
                ]), // Liste des choix disponibles pour la TVA
            
            //*  Champ de type association pour lier un produit à une catégorie.
            AssociationField::new('category')
                ->setLabel('Categorie associé') // Champ de sélection pour associer une catégorie
        ];
    }
    
}
    /* 
    !Explications supplémentaires :
    * getEntityFqcn() : 
        - Retourne le nom de la classe Product pour indiquer que ce contrôleur gère l'entité Product.

    * configureCrud(Crud $crud): Crud : 
        - Configure les options d'affichage pour l'interface CRUD, en spécifiant les libellés au singulier et au pluriel de l'entité.

    * configureFields(string $pageName): iterable : 
        - Définit les champs à afficher dans les pages CRUD avec des labels personnalisés et des messages d’aide.

    * TextField, SlugField, TextEditorField, ImageField, NumberField, ChoiceField, et AssociationField 
        -configurent les différents champs avec leurs propriétés spécifiques.

    * ImageField :
        - setBasePath : Chemin public pour accéder aux images téléchargées.
        - setUploadDir : Répertoire de téléchargement pour les images.
        - setUploadedFileNamePattern : Modèle pour nommer les fichiers afin d’éviter les doublons.

    * ChoiceField : 
        - Configure un champ de choix pour la TVA, avec des valeurs prédéfinies pour une saisie rapide et uniforme.

    * BooleanField :
        - Configure un champ vrai ou faux ( a coche ) pour indiquer si le produit est en page d'acceuil (IsHomepage)

    * Ce contrôleur rend l'interface CRUD pour Product intuitive et adaptée aux besoins de gestion, tout en respectant les spécifications et les contraintes nécessaires pour chaque champ.
    */

