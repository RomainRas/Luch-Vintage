<?php
//? Contrôleur qui permet aux utilisateurs connectés de consulter les détails d'une commande spécifique depuis leur compte.
/*
************************************************************
!           namespace et import des classes                *
************************************************************
*/
namespace App\Controller\Account;
    /*
        - namespace App\Controller\Account : Déclare que ce contrôleur se trouve dans le dossier Account de l'application.
    */

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
    /*
        use :
            - OrderRepository : Classe permettant d'effectuer des requêtes sur l'entité Order dans la base de données.
            - AbstractController : Contrôleur de base Symfony fournissant des méthodes pratiques (comme render() ou getUser()).
            - Response : Représente la réponse HTTP envoyée au client.
            - Route : Annotation pour définir une route Symfony.
    */

/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class OrderController extends AbstractController
{
/*
************************************************************
!                     Route /compte                        *
************************************************************
*/
    #[Route('/compte/commande/{id_order}', name: 'app_account_order')]
        /*
            - #[Route(...)] : Annotation pour définir une route Symfony.
            - /compte/commande/{id_order} : Chemin d'accès à la page de commande individuelle.
                - {id_order} est un paramètre dynamique correspondant à l'ID de la commande.
            name: 'app_account_order' : Nom unique de la route, utilisé pour la référencer dans le code (par exemple, dans les redirections ou les liens Twig).
        */

    //! ** Methode index ** !//
    public function index($id_order, OrderRepository $orderRepository): Response
        /*
            $id_order : Paramètre dynamique de la route, représentant l'ID de la commande.
            OrderRepository $orderRepository : Injection de dépendance du repository pour effectuer une requête sur la table Order.
        */
    {
        //* Recuperaction de la commande .
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);
            /*
                - findOneBy([...]) : Méthode de Doctrine pour récupérer une entité correspondant à certains critères.
                    Criteres :
                        'id' => $id_order : Recherche une commande dont l'ID correspond à $id_order.
                        'user' => $this->getUser() : Vérifie que la commande appartient à l'utilisateur actuellement connecté.
            */

        //* Vérification si la commande existe :
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
            /*
                if (!$order) : Vérifie si la commande n'existe pas ou ne correspond pas à l'utilisateur connecté.
                redirectToRoute('app_home') : Si la commande n'est pas trouvée, redirige l'utilisateur vers la page d'accueil.
            */

        //* Affichage de la vue Twig :
        return $this->render('account/order/index.html.twig', [
            'order' => $order,
        ]);
            /*
                - $this->render() : Méthode de AbstractController pour retourner une vue Twig.
                - 'account/order/index.html.twig' : Fichier de template Twig utilisé pour afficher les détails de la commande.
                - ['order' => $order] : Transmet l'objet order à la vue Twig, où il sera utilisé pour afficher les informations de la commande.
            */
    }
}
/*
! Resumé
//* méthode index() :
    - Capture le paramètre dynamique id_order depuis l'URL.
    - Utilise le dépôt OrderRepository pour rechercher la commande dans la base de données.
    - Vérifie que la commande appartient bien à l'utilisateur connecté.
    - Si la commande est trouvée, elle est affichée dans une vue Twig.
    - Si la commande n'existe pas ou n'appartient pas à l'utilisateur, une redirection vers la page d'accueil est effectuée.
*/