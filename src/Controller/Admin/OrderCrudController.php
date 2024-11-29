<?php
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller\Admin;
    /*
        - namespace App\Controller\Admin : Définit le namespace, indiquant que ce fichier fait partie du dossier Admin.
    */
use App\Entity\Order;
    /*
        - use App\Entity\Order; : Importation de l'entité Order pour gérer les données des commandes.
    */

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
    /*
        - use EasyCorp\Bundle\EasyAdminBundle\Config\Action; : Classe pour configurer des actions dans le CRUD.
    */
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    /*
        - use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; : Classe pour configurer le CRUD (libellés, templates).
    */

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
    /*
        - use EasyCorp\Bundle\EasyAdminBundle\Config\Actions; : Classe pour configurer les actions dans les différentes pages (index, détail...).
    */

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    /*
    - use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController; : Contrôleur de base pour le CRUD.
    */

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
/*
    - use EasyCorp\Bundle\EasyAdminBundle\Field :
        - IdField : Champ pour afficher un ID.
        - TextField : Champ pour afficher ou modifier du texte.
        - DateField : Champ pour gérer les dates.
        - NumberField : Champ pour afficher des nombres.
        - AssociationField : Champ pour gérer les relations entre entités.
*/


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* Classe contrôleur pour gérer l'interface d'administration des commandes (Order).
class OrderCrudController extends AbstractCrudController
    /*
        - Hérite de AbstractCrudController : Fournit les fonctionnalités de base pour les opérations CRUD.
    */

/*
************************************************************
!                      METHODES                            *
************************************************************
*/
{   
    //! ** getEntityFqcn() ** !//
    //* Retourne le FQCN (Fully Qualified Class Name) de l'entité gérée par ce CRUD. Ici, il s'agit de App\Entity\Order.
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    //! ** configureCrud() ** !//
    //* Configuration du CRUD
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }
        /*
            - configureCrud : Personnalise les libellés affichés pour l'entité dans l'interface.
            - Crud $crud : Instance de la classe Crud utilisée pour configurer le CRUD.

            - setEntityLabelInSingular : Définit le libellé de l'entité au singulier.
            - setEntityLabelInPlural : Définit le libellé de l'entité au pluriel.
        */

    //! ** configureActions() ** !//
    //* Configure les actions disponibles pour l'interface CRUD.
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');

        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }
        /*
            - Configuration des actions CRUD :
                - Action::new('Afficher') : Crée une nouvelle action "Afficher", qui redirige vers une vue personnalisée.
                - linkToCrudAction('show') : Lie cette action à la méthode show dans ce contrôleur.
            
            - Ajout/Retrait d'actions :
                - add(Crud::PAGE_INDEX, $show) : Ajoute l'action "Afficher" sur la page index.
                - remove(Crud::PAGE_INDEX, Action::NEW) : Supprime l'action "Nouveau" sur la page index.
                - remove(Crud::PAGE_INDEX, Action::DELETE) : Supprime l'action "Supprimer".
                - remove(Crud::PAGE_INDEX, Action::EDIT) : Supprime l'action "Modifier".
        */

    //! ** Show ** !//
    //* Méthode personnalisée liée à l'action "Afficher" : Affiche les détails d'une commande.
    public function show(AdminContext $adminContext)
    {
        $order = $adminContext->getEntity()->getInstance();
        // dd($order);

        return $this->render('admin/order.html.twig', [
                'order' => $order
        ]);
    }
        /*
            - AdminContext $adminContext : Permet d'accéder aux données du contexte administratif, y compris l'entité en cours.

            - $adminContext->getEntity()->getInstance() :
                - Récupère l'instance de l'entité actuellement sélectionnée.
                - Ici, on récupère une commande spécifique.

            - Rendu :
                - Utilise le template admin/order.html.twig pour afficher les détails de la commande.
        */

    //! ** configureFields() ** !//
    //* Configuration des champs
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt')->setLabel('Date'),
            NumberField::new('state')->setLabel('Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user')->setLabel('Utilisateur'),
            TextField::new('carrierName')->setLabel('Transporteur'),
            NumberField::new('TotalWt')->setLabel('Total TTC'),
            NumberField::new('TotalTva')->setLabel('Total TVA'),
            NumberField::new('carrierPrice')->setLabel('Total Transporteur')
        ];
    }
        /*
            - Configure les champs affichés sur les différentes pages du CRUD.
                - Champs utilisés :
                    - IdField : Champ pour afficher l'ID.
                    - DateField : Champ pour afficher une date (ici createdAt).
                    - NumberField : Champ pour afficher des nombres (statut, total, TVA, etc.).
                    - TextField : Champ texte pour afficher le nom du transporteur.
                    - AssociationField : Champ pour afficher l'utilisateur lié à la commande.
            
            - Personnalisation :
                - setLabel : Définit un libellé pour chaque champ.
                - setTemplatePath : Permet d'utiliser un template Twig personnalisé pour afficher le champ state.
        */
}
/*
! Résumé
    * Ce contrôleur est une intégration entre EasyAdmin et l'entité Order ( les commandes ). Il permet de :

    *Affichage personnalisé :
        - Modifier les actions CRUD. (création, modification, suppression).
        - Ajouter des vues personnalisées. Utilisation d'actions spécifiques (comme "Afficher").

    * Gérer les champs :
        - Afficher les informations pertinentes des commandes (date, utilisateur, total TTC/TVA, etc.).
        - Utiliser des champs spécifiques pour des types de données différents (texte, nombres, relations...).

    * Personnalisation :
        - Vue personnalisée pour afficher les commandes (show).
        - Template spécifique pour le champ state.

    * Ce contrôleur offre une gestion fluide et intuitive des commandes pour les administrateurs du site.
*/