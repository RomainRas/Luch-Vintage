<?php
//? CONTROLLEUR PERMETANT LA GESTION DES TRANSPORTEURS ?//
    /*
        ?1. Afficher la liste des transporteurs.
        ?2. Modifier les informations d'un transporteur.
        ?3. Ajouter un nouveau transporteur.
        ?4. Supprimer un transporteur.
    */
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller\Admin;
    /*
        - namespace : Définit l'emplacement de cette classe dans la structure du projet.
        - App\Controller\Admin : Indique que ce fichier appartient à la partie administration de l'application, dans le répertoire Admin du dossier Controller.
    */

use App\Entity\Carrier;
    /*
        - Importation de l’entité Carrier, qui représente les transporteurs.
        - L’entité est utilisée pour configurer les champs CRUD.
    */

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    /*
        - Classe permettant de configurer les options globales pour l’interface CRUD, comme les labels, la pagination, etc.
    */

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    /*
        - IdField : Champ utilisé pour afficher ou manipuler les identifiants (généralement un entier).
        - TextField : Champ pour gérer les données textuelles simples.
        - NumberField : Champ pour les données numériques.
        - TextareaField : Champ pour les zones de texte multi-lignes.
        - TextEditorField : Champ pour les zones de texte enrichi, comme un éditeur WYSIWYG.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class CarrierCrudController extends AbstractCrudController
    /*
        - Classe de base pour tous les contrôleurs CRUD dans EasyAdmin.
        - Fournit des fonctionnalités pour gérer les opérations CRUD (Create, Read, Update, Delete).
    */
{


/*
************************************************************
!                      METHODES                            *
************************************************************
*/
    //! ** getEntityFqcn ** !//
    //* -> Utilisation de la methode getEntityFqcn pour identifier quelle entité est associée au contrôleur CRUD
        //* -> Permet de générer automatiquement les interfaces CRUD (liste, formulaire, détails). et Appliquer les configurations définies dans les méthodes comme configureCrud() ou configureFields() au bon modèle de données (Carrier ici).
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }
        /*
            - public : methode accessible à l'exterieur de la class
            - static : permet d'apeller la methode sans instancier la class ( ::getEntityFqcn() )
            - function : Mot clé pour déclarer une methode 
            - getEntityFqcn() : Methode d'EasyAdmin EasyAdmin s'attend a ce que cette methode existe dans les controlleurs CRUD pour récuperer le nom complet de la classe d'entité à gerer ( dans notre cas Carrier)
            - () string : La methode attend une valeur de type string ( chaine de caractere )
            - return : Mot clé qui renvoi une valeur
            - Carrier::class : Utilisation du mot clé ::class pour retourner le nom complet de la class d'entité Carrier sous forme de chaine de caractere
        */
    
    //! ** configureCrud ** !//
    //* -> Configuration des libellés (singulier et pluriel) affichés dans l’interface EasyAdmin pour l’entité associée, pour personnaliser les options d'affichage des pages CRUD d'une entité.
    public function configureCrud(Crud $crud): Crud
        /*
            - public : Methode accessible depuis l'exterieur de la class 
            - function : declare une methode
            - configureCrud : nom de la methode, utilisé par EasyAdmin pour personaliser le comportement CRUD
            - Crud $crud : Parametres d'entreé representant une instance de la class Crud. Elle contient les parametres de configuration
            - : Crud : specifie que la methode retourne un objet de type CRUD
        */
    {
        //* Renvoie l’objet $crud configuré.
        return $crud
            /* 
                - return : Mot clé qui renvoie une valeur depuis une methode
                - $crud : Objet Crud contenant les options de configuration pour l'interface CRUD
            */
            //* Définit le label au singulier pour l'entité
            ->setEntityLabelInSingular('Transporteur')
            //* Définit le label au pluriel pour l'entité
            ->setEntityLabelInPlural('Transporteurs')
        ;
            /*
                - -> : Operateur pour apeller une methode sur un objet 
                - setEntityLabelInSingular : Methode de class CRUD. Definit le libéllé utilisé pour representer une entité au singulier
                - 'Transporteurs' : Texte à afficher dans l'interface
            */
    }
    
    //! configureFields !//
    //* -> Configuration des champs affichés dans l'interface d'administration pour l'entité Carrier.
    public function configureFields(string $pageName): iterable
        /*
            - string $pageName : Reprensente la page CRUD actuelle ( index, edit, new, par exemple) il permet de personaliser l'affichage des champs selon la page
            - : iterable : Le type de retour de la methode, indiquant qu'elle retourne une liste de champs sous forme d'iterable (tableau ou générateur)
            - 
        */
    {
        return [
            TextField::new('name')
                ->setLabel("Nom du transporteur"),
            TextareaField::new('text')
                ->setLabel("Description du transporteur"),
            NumberField::new('price')
                ->setLabel('Prix T.T.C') // Label pour le champ de prix
                ->setHelp("Prix T.T.C du transporteur sans le sigle €"), // Message d’aide pour indiquer le format du prix
        ];
            /*
                TextField::new('name') :
                    - Type de champ : Textfield = champ texte simple
                    - Nom du champ : 'name' = lié à la propriété name dans l'entité Carrier
                    - ->setLabel("Nom du transporteur") : Definit le label affiché pour ce champ dans l'interfate
                
                TextareaField::new('text') :
                    - Type de champ : TextareaField = zone de text utilisé pour de longues descriptions
                    - Nom du champ : 'text' = lié a la propriété text dans l'entité Carrier
                    - ->setLabel("Description du transporteur") : Definit le label affiché pour ce champ dans l'interface

                NumberField::new('price')
                    - Type de champ : Numberfield (champ numerique)
                    - Nom du champ : 'price' = lié à la propriété price dans l'entité Carrier
                    - ->setLabel('Prix T.T.C) : Definit le label affiché pour ce champ dans l'interface
                    - ->setHelp("Prix T.T.C du transporteur sans le sigle €") : Ajoute une aide contextuelle qui apparaît sous le champ, indiquant aux administrateurs de ne pas inclure le symbole "€".
            */
    }
}

/*
!Explications Supplementaires :
*getEntityFqcn()
    * Utilité de la méthode
        - Rôle : Cette méthode est utilisée par EasyAdmin pour savoir quelle entité doit être gérée par le contrôleur CRUD actuel.
        - Contexte d’utilisation : Dans EasyAdmin, chaque contrôleur CRUD doit être lié à une entité spécifique.
    * Cette méthode permet de définir cette liaison en renvoyant le nom de l’entité.
        - Pourquoi une méthode static ? : EasyAdmin appelle cette méthode sans instancier le contrôleur. Cela évite de créer un objet inutilement juste pour récupérer le nom de l’entité.
    * Ce que fait la méthode :
        - Elle retourne le nom complet de la classe d’entité associée (par exemple, App\Entity\Carrier).
        - Pourquoi elle est importante : Elle lie le contrôleur CRUD à une entité spécifique, permettant à EasyAdmin de générer l’interface d’administration pour cette entité.
    * Quand elle est utilisée :
        Appelée automatiquement par EasyAdmin lors de l’affichage, de l’édition, ou de la gestion des données de l’entité dans l’interface d’administration.

*configurCrud()
    * Ce que fait la méthode :
        - Configure les libellés (singulier et pluriel) affichés dans l’interface EasyAdmin pour l’entité associée.
    * Quand elle est utilisée :
        - Appelée automatiquement par EasyAdmin lorsque vous accédez aux pages CRUD de l’entité.
    * Personnalisation :
        - Vous pouvez remplacer 'Transporteur' et 'Transporteurs' par d'autres termes adaptés à votre projet, comme :
            'Produit' et 'Produits'.
            'Client' et 'Clients'.

*configureFields() 
    - Cette méthode configure l'interface pour l'entité Carrier avec les champs suivants :
        - Nom du transporteur (name) : Champ texte.
        - Description du transporteur (text) : Zone de texte.
        - Prix T.T.C (price) : Champ numérique avec une aide pour le format attendu.
    L'interface d'administration devient intuitive grâce à des labels explicites et des messages d'aide.
*/