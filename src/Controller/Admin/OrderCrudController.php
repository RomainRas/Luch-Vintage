<?php
//! contrôleur CRUD pour gérer l'entité Order (commandes) dans l'interface d'administration avec EasyAdmin. Il inclut des personnalisations pour les actions, les champs, et l'affichage des commandes
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    /*
        App\Classe\Mail :
            - Classe pour envoyer des emails. Utilisée ici pour notifier les utilisateurs des changements de statut d'une commande.

        App\Classe\State :
            - Contient les différents statuts de commande. Par exemple, "En cours de préparation", "Expédiée", etc.
        App\Entity\Order :
            - Entité Order, qui représente les commandes dans la base de données. Ce CRUD gère cette entité.

        Doctrine\ORM\EntityManagerInterface :
            - Interface pour interagir avec la base de données (création, mise à jour, suppression).

        Symfony\Component\HttpFoundation\Request :
            - Classe pour accéder aux données de la requête HTTP (comme les paramètres state pour changer le statut d'une commande).

        EasyAdmin :
            - Les classes d'EasyAdmin permettent de configurer les champs, les actions, et les comportements de l'interface d'administration :
                - Crud : Configure l'apparence et le comportement des pages CRUD.
                - Action : Définit des actions comme "Afficher", "Modifier", etc.
                - IdField, TextField, DateField, etc.` : Types de champs utilisés pour afficher les données.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* Classe contrôleur pour gérer l'interface d'administration des commandes (Order).
class OrderCrudController extends AbstractCrudController
    /*  
        - OrderCrudController : Nom de la classe, qui gère le CRUD (Create, Read, Update, Delete) pour l'entité Order.
        - Hérite de AbstractCrudController : Fournit les fonctionnalités de base pour les opérations CRUD.
    */

/*
************************************************************
!                      METHODES                            *
************************************************************
*/
{   
    private $em;
        /*
            $em : 
                - Représente l'EntityManager, utilisé pour interagir avec la base de données (par exemple, enregistrer ou mettre à jour une commande).
        */
    public function __construct(EntityManagerInterface $entityManagerInterface) 
    {
        $this->em = $entityManagerInterface;
    }
        /*
            - __construct() : 
                - Méthode spéciale appelée automatiquement lorsque la classe est instanciée.
            - EntityManagerInterface $entityManagerInterface :
                - Symfony injecte automatiquement l'EntityManager dans cette méthode.
            - $this->em = $entityManagerInterface :
                - Initialise la propriété $em avec l'EntityManager fourni.
        */
    //! ** getEntityFqcn() ** !//
    //* Retourne le FQCN (Fully Qualified Class Name) de l'entité gérée par ce CRUD. Ici, il s'agit de App\Entity\Order.
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
        /*
            - getEntityFqcn() :
                - Méthode obligatoire dans un contrôleur CRUD EasyAdmin. Elle retourne le FQCN (Fully Qualified Class Name) de l'entité gérée (Order ici).
        */

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

    //! ** changeState ** !//
    //* Methode personnalisée liée au actions pour changer le statut d'une commande
    public function changeState($order,$state)
    {
        // dd(State::STATE);
        //* -> 1 Modification du statut de la commande
        // dd($state);
        $order->setState($state);
        $this->em->flush();
            /*
                - $order->setState($state) : Change le statut de la commande.
                - $this->em->flush() : Enregistre la modification dans la base de données.
            */

        //* -> 2 Affichage du Flash Message pour informer l'administrateur
        $this->addFlash('success', "Statut de la commande correctement mis à jour.");

        //* -> 3 Informer l'User par mail de la modification du statut de sa commande
        // dd($order->getUser()->getEmail()); adresse email de l'User

        $mail = new Mail();
        $vars = [
            'firstname' => $order->getUser()->getFirstname(),
            'id_order' => $order->getId()
        ];
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstname().' '.$order->getUser()->getLastname(),
            State::STATE[$state]['email_subject'],
            State::STATE[$state]['email_template'], 
            $vars);
        // dd($order->getUser()->getEmail());
            /*
                - new Mail() : Crée une instance de la classe Mail.
                - $mail->send(...) : Envoie un email pour informer l'utilisateur du changement de statut.
            */
        
    }
    //! ** Show ** !//
    //* Méthode personnalisée liée à l'action "Afficher" : Affiche les détails d'une commande.
    public function show(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator, Request $request)
    {   
        $order = $adminContext->getEntity()->getInstance();
        // dd($order);

        //* -> 1. Récuperer l'url de notre action "SHOW" ( recuperation de la commande)
        $url = $adminUrlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();
        // dd(vars: $url);
            /*
                $adminContext->getEntity()->getInstance() : Récupère l'entité actuellement sélectionnée.
            */

        //* -> 2. Traitement des changement de statut
        // dd($request->get('state'));
        if ($request->get('state')) {
            $this->changeState($order,$request->get('state'));
        }
            /*
                -Si un paramètre state est présent dans la requête, il est utilisé pour changer le statut via changeState().
            */
        //* -> 3. Affichage de la commande
        return $this->render('admin/order.html.twig', [
                'order' => $order,
                'current_url' => $url
        ]);
            /*
                - $this->render('admin/order.html.twig', [...]) : Affiche la commande en utilisant le template Twig order.html.twig.
            */
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