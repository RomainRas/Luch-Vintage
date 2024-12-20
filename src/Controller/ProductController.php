<?php
//? Ce contrôleur ProductController gère l’affichage des détails d’un produit spécifique :


/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
    /*
        - Déclare le namespace `App\Controller`, qui regroupe les contrôleurs de l'application.
        - ProductRepository : Classe pour accéder aux méthodes de requête de l’entité Product, notamment pour rechercher un produit spécifique dans la base de données.
        - AbstractController : Classe de base des contrôleurs dans Symfony, fournissant des méthodes utiles comme render() et redirectToRoute().
        - Response : Classe de Symfony utilisée pour encapsuler la réponse HTTP.
        - Route : Attribut pour définir les routes dans Symfony, en indiquant quelle URL correspond à quelle méthode du contrôleur.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class ProductController extends AbstractController
    /*
        - Déclaration de la classe `ProductController`, qui gère les actions liées aux produits.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route qui do produit definit par son slug
    #[Route('/produits/{slug}', name: 'app_product')]
        /*
            - Définit la route `/produits/{slug}`, où `{slug}` est une variable dynamique correspondant au slug du produit.
            - Le nom de la route est `app_product`, ce qui permet de la référencer facilement dans le code.
        */
    //! ** Méthode pour afficher les détails d'un produit spécifique en fonction de son slug. ** !//
    //* -> `ProductRepository $productRepository` est injecté pour interagir avec la base de données des produits.
    public function index($slug, ProductRepository $productRepository): Response
        /*
            - index : Nom de la méthode, souvent utilisée comme action principale d’un contrôleur.
            - $slug : Paramètre qui représente le slug du produit passé dans l’URL.
            - ProductRepository $productRepository : Paramètre injecté, permettant d’accéder aux méthodes de requête de l’entité Product.
            - : Response : Indique que la méthode retourne un objet Response.
        */

    {
        //* -> Recherche le produit correspondant au slug dans la base de données via le repository `ProductRepository`.
        $product = $productRepository->findOneBySlug($slug);
            /*
                - $product = $productRepository->findOneBySlug($slug); : Utilise la méthode findOneBySlug() du ProductRepository pour récupérer le produit correspondant au slug passé en paramètre.
                - findOneBySlug($slug) : Requête qui recherche un produit dans la base de données en fonction de son slug. Le slug est un identifiant unique utilisé dans l’URL pour reconnaître le produit.
            */
        //* -> Si aucun produit n'est trouvé avec ce slug, l'utilisateur est redirigé vers la page d'accueil.
        if (!$product) {
            return $this->redirectToRoute('app_home');
        }
            /*
                - if (!$product) : Vérifie si aucun produit n’a été trouvé pour le slug donné.
                - /!$product : Si $product est null, cela signifie qu’aucun produit ne correspond au slug fourni.
                - return $this->redirectToRoute('app_home'); : Redirige l’utilisateur vers la page d’accueil (app_home) si le produit n’est pas trouvé.
                - $this->redirectToRoute('app_home') : Méthode de AbstractController qui effectue une redirection vers une route spécifiée.
            */
        
        //* -> Affiche la vue Twig `product/index.html.twig`, correspondant à la page de détail du produit.
        return $this->render('product/index.html.twig', [
            'product' => $product,
                // Passe la variable `product` à la vue, qui contient les informations du produit.
        ]);
            /*
                - $this->render(...) : Utilise la méthode render() d’AbstractController pour générer et afficher une vue Twig.
                - 'product/index.html.twig' : Chemin du fichier de template utilisé pour afficher les détails du produit.
                - ['product' => $product] : Tableau de variables passées au template :
                - 'product' => $product : Passe le produit trouvé ($product) à la vue Twig, afin que ses détails puissent être affichés sur la page.
            */
    }
}
/*
!Explications supplémentaires :
* Route et Paramètres :
    - La route #[Route('/produits/{slug}', name: 'app_product')] :
        - Définit l'URL /produits/{slug}, où {slug} représente une partie dynamique de l'URL (le slug du produit).
        - name: 'app_product' : Attribue un nom à la route pour pouvoir la référencer facilement dans les liens et les redirections.

* Dépendances Injectées :
    - ProductRepository $productRepository :
        - Le repository est injecté pour interagir avec la base de données et récupérer les informations sur les produits.
        - Utilise la méthode findOneBySlug($slug) pour rechercher le produit correspondant au slug passé dans l’URL.

*Méthode index() :
    - Recherche du produit :
        - $productRepository->findOneBySlug($slug) récupère le produit en fonction du slug.
        - Si le produit n'existe pas (i.e., $product est null), l'utilisateur est redirigé vers la page d'accueil.
    - Affichage de la vue :
        - Si le produit est trouvé, la méthode retourne une vue Twig product/index.html.twig, en lui passant le produit sous forme de variable.
        - La vue peut alors utiliser cette variable product pour afficher les détails du produit.

* Résumé
    Ce contrôleur ProductController gère l’affichage des détails d’un produit spécifique :

    1. Il utilise le slug dans l’URL pour rechercher le produit correspondant.
    2. Si le produit n’est pas trouvé, il redirige l’utilisateur vers la page d’accueil.
    3. Si le produit est trouvé, il retourne une vue Twig (product/index.html.twig) qui affiche les informations du produit.
    Ce contrôleur permet ainsi de créer des pages de produits dynamiques, accessibles via des slugs uniques dans l’URL, et offre une navigation simple en redirigeant vers la page d’accueil en cas d’erreur.
*/