<?php
//? Contrôleur CartController gère les actions courantes d’un panier :

/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller;
    /*
        - namespace : Définit l'espace de noms où cette classe est organisée. 
            - App\Controller\ : indique que ce fichier appartient au dossier Controller dans le répertoire src(app).
    */
use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
    /*
        - Cart : Classe personnalisée Cart pour gérer les actions liées au panier.
        - ProductRepository : Classe de Symfony pour interagir avec la table Product et récupérer des produits.
        - Response : Classe Symfony pour manipuler les réponses HTTP.
        - Route : Attribut pour définir des routes.
        - AbstractController : Classe de base de Symfony pour les contrôleurs, qui fournit de nombreuses fonctionnalités utiles.
        - Request : Classe pour gérer les requêtes HTTP, permettant d’accéder aux informations de la requête en cours.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class CartController extends AbstractController
    /*
        - CartController : Nom de la classe, dédiée aux opérations de gestion du panier.
        - extends AbstractController : Hérite de AbstractController pour bénéficier des fonctionnalités de contrôleur fournies par Symfony.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route pour afficher le panier
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: [ 'motif' => null ])]
        /*
            - [Route('/mon-panier', name: 'app_cart')] : Attribut PHP pour définir une route.
            - /mon-panier : Chemin de l’URL pour accéder à cette méthode (page du panier).
            - name: 'app_cart' : Nom unique de la route, permettant de la référencer ailleurs dans l’application.
            - {motif} : Paramètre optionnel pour identifier le motif de l'affichage (par exemple, en cas d'annulation d'un paiement).
            - defaults: [ 'motif' => null ]Définit une valeur par défaut pour le paramètre {motif}. Si aucune valeur n’est passée dans l’URL, le paramètre sera null.
        */
    //! ** Méthode pour afficher le panier de l’utilisateur  et gere le cas d'annulation d'en paiement** !//
    public function index(cart $cart, $motif): Response
        /*
            - public function index(Cart $cart): Response : Méthode publique pour afficher le contenu du panier.
            - Cart $cart : Paramètre injecté représentant le service Cart.
            - : Response : Spécifie que cette méthode retourne un objet Response.
        */
    {
        //* Gestion de l'annulation d'un paiement
        if ($motif == "annulation") {
            $this->addFlash(
                'info',
                'Paiement annulé : Vous pouvez mettre à jour votre panier et votre commande.'
            );
        }
            /*
                - if ($motif == "annulation") : Vérifie si le motif passé à la méthode est "annulation".
                - $this->addFlash('info', '...') : Ajoute un message flash de type "info" à la session.
                    Ce message sera affiché sur la page suivante, informant l'utilisateur que le paiement a été annulé.
                - Flash Messages :
                    Les flash messages sont des messages temporaires qui disparaissent après avoir été affichés.
                    Utilisés pour fournir un retour rapide à l'utilisateur (par exemple, confirmation ou erreur).
            */

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(), //* -> Récupère le contenu du panier
            'totalWt' => $cart->getTotalWt() //* -> Calcule le total du panier
        ]);
            /*
                - $this->render(...) : Méthode pour rendre une vue Twig et lui passer des variables.
                - 'cart/index.html.twig' : Chemin du fichier de template Twig à utiliser pour afficher la vue du panier.
                - 'cart' => $cart->getCart() : Passe le contenu du panier à la vue en appelant getCart() sur l’objet Cart.
                - 'totalWt' => $cart->getTotalWt() : Passe le total TTC du panier, calculé avec getTotalWt().
            */
    }

    //* -> Route pour ajouter un produit au panier
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
        /*
            - #[Route('/cart/add/{id}', name: 'app_cart_add')] : Attribut PHP pour définir une route d’ajout au panier.
            - /cart/add/{id} : Chemin de l’URL avec un paramètre {id} pour l’identifiant du produit à ajouter.
            - name: 'app_cart_add' : Nom unique de la route, permettant de la référencer ailleurs.
        */
    //! ** Méthode pour ajouter un produit au panier  ** !//
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
        /*
            - public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response : Méthode pour ajouter un produit au panier.
            - $id : Paramètre représentant l’identifiant du produit.
            - Cart $cart : Paramètre pour le service Cart.
            - ProductRepository $productRepository : Paramètre pour accéder aux données des produits.
            - Request $request : Paramètre pour accéder aux informations de la requête.
        */
    {
        //* -> Récupère le produit par son identifiant
        $product = $productRepository->findOneById($id);
        //* -> Ajoute le produit au panier
        $cart->add($product);
            /*
                - $product = $productRepository->findOneById($id); : Utilise ProductRepository pour récupérer le produit avec l’ID spécifié.
                - $cart->add($product); : Ajoute le produit au panier en appelant la méthode add() de Cart.
            */

        //* -> Ajoute un message flash pour confirmer l'ajout
        $this->addFlash(
            'success',
            "Produit correctement ajouté à votre panier"
        );
            /*
                - $this->addFlash(...) : Ajoute un message flash temporaire, qui s’affiche après la redirection.
                - 'success' : Type de message (ici success pour signaler un succès).
                - "Produit correctement ajouté à votre panier" : Contenu du message.
            */

        //* -> Redirige vers la page précédente
        return $this->redirect($request->headers->get('referer'));
            /*
                - $this->redirect(...) : Redirige l’utilisateur vers l’URL précédente.
                - $request->headers->get('referer') : Récupère l’URL de la page précédente depuis les en-têtes HTTP.
            */
    }

    //* -> Route pour diminuer la quantité d'un produit dans le panier
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
        /*
            - #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')] : Attribut PHP pour définir une route pour diminuer la quantité d’un produit.
            - /cart/decrease/{id} : Chemin d’URL avec le paramètre {id} pour l’identifiant du produit.
            - name: 'app_cart_decrease' : Nom unique de la route.
        */
    //! ** Méthode pour diminuer la quantité d'un produit dans le panier  ** /
    public function decrease($id, Cart $cart): Response
        /*
            - public function decrease($id, Cart $cart): Response : Méthode pour diminuer la quantité d’un produit dans le panier.
            - $id : Identifiant du produit.
            - Cart $cart : Service Cart pour gérer le panier.
        */
    {
        
        //* -> Diminue la quantité du produit dans le panier
        $cart->decrease($id);
            /*
                - $cart->decrease($id); : Diminue la quantité du produit dans le panier en appelant decrease().
            */

        //* -> Ajoute un message flash pour confirmer la suppression
        $this->addFlash(
            'success',
            "Produit correctement supprimé de votre panier"
        );

        //* -> Redirige vers la page du panier
        return $this->redirectToRoute('app_cart');
    }

    //* -> Route pour vider le panier
    #[Route('/cart/remove', name: 'app_cart_remove')]
        /*
            - #[Route('/cart/remove', name: 'app_cart_remove')] : Attribut PHP pour définir une route pour vider le panier.
            - /cart/remove : Chemin d’URL pour cette action.
            - name: 'app_cart_remove' : Nom unique de la route.
        */
    //! ** Méthode pour vider le panier  ** !//
    public function remove( Cart $cart): Response
        /*
            - public function remove(Cart $cart): Response : Méthode pour vider complètement le panier.
            - Cart $cart : Service Cart pour manipuler le panier.
        */
    {
        //* -> Supprime tout le contenu du panier
        $cart->remove();
            /*
                - $cart->remove(); : Vide le panier en appelant la méthode remove() du service Cart.
            */

        // dd('Produit ajouté au panier');
        //* -> Redirige vers la page d'accueil
        return $this->redirectToRoute('app_home', [
        ]);
            /*
                - $this->redirectToRoute('app_home'); : Redirige l’utilisateur vers la route app_home, qui correspond généralement à la page d’accueil.
            */
    }
}
/* 
!Explications supplémentaires :
* Dépendances injectées :
    - Cart $cart : La classe Cart est injectée dans les méthodes pour gérer le panier.
    - ProductRepository $productRepository : Le repository ProductRepository est utilisé pour récupérer les produits par leur ID.
    - Request $request : L'objet Request permet d'accéder aux informations de la requête HTTP.

* Méthodes :
    - index() : Affiche le panier en passant à la vue les détails du panier (cart) et le total (totalWt).
    - add() :
        - Ajoute un produit au panier en fonction de son ID.
        - Ajoute un message flash pour informer l'utilisateur de l’ajout réussi.
        - Redirige vers la page précédente en utilisant le referer dans les en-têtes de requête.
    - decrease() :
        - Diminue la quantité d'un produit dans le panier.
        - Si la quantité atteint zéro, le produit est supprimé du panier.
        - Ajoute un message flash de confirmation de suppression.
        - Redirige vers la page du panier.
    - remove() :
        - Vide complètement le panier.
        - Redirige vers la page d'accueil.

    - Gestion du motif	Vérifie si le paiement a été annulé et affiche un message flash.
    - Récupération du panier	Utilise le service Cart pour récupérer le contenu du panier et le total TTC.
    - Affichage de la vue	Retourne la vue Twig avec les données du panier.
    
* Messages Flash :
    - addFlash() : Ajoute un message de confirmation pour informer l'utilisateur du succès des opérations d’ajout ou de suppression, qui sera affiché lors de la redirection.
*/