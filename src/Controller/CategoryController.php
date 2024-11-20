<?php
//? Contrôleur CategoryController gère l'affichage d’une catégorie sur la base de son slug

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

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
    /*
        - CategoryRepository : Classe pour interagir avec la base de données et accéder aux données de l’entité Category.
        - AbstractController : Classe de base des contrôleurs Symfony, fournissant de nombreuses fonctionnalités utiles, comme le rendu de vues et les redirections.
        - Response : Classe Symfony pour gérer les réponses HTTP.
        - Route : Attribut pour définir des routes dans Symfony.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class CategoryController extends AbstractController
    /*
        - CategoryController : Nom de la classe, dédiée aux opérations liées aux catégories.
        - extends AbstractController : Hérite de AbstractController pour bénéficier des fonctionnalités de contrôleur de base fournies par Symfony.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route pour afficher une catégorie spécifique en fonction de son slug
    #[Route('/categories/{slug}', name: 'app_category')]
        /*
            - #[Route('/categories/{slug}', name: 'app_category')] : Attribut PHP pour définir une route.
            - /categories/{slug} : Chemin d’URL avec un paramètre {slug}, qui permet de passer un slug de catégorie pour accéder à une page spécifique de catégorie.
            - name: 'app_category' : Nom unique de la route pour identifier cette action dans l’application.
        */
    //! ** Méthode pour afficher une catégorie spécifique en fonction de son slug  ** !//
    public function index($slug, CategoryRepository $categoryRepository): Response
        /*
            - public function index($slug, CategoryRepository $categoryRepository): Response : Déclaration de la méthode index qui gère l’affichage d’une catégorie.
            - $slug : Paramètre pour le slug de la catégorie, utilisé pour identifier une catégorie spécifique.
            - CategoryRepository $categoryRepository : Injecte le CategoryRepository pour accéder aux méthodes de requête de l’entité Category.
            - : Response : Type de retour spécifiant que la méthode retourne un objet Response.
        */
    {   
        //* -> Récupère une catégorie depuis le repository en utilisant le slug
        $category = $categoryRepository->findOneBySlug($slug);
            /*
                - $category = $categoryRepository->findOneBySlug($slug); : Utilise findOneBySlug() du CategoryRepository pour récupérer la catégorie correspondant au slug passé en paramètre.
                - findOneBySlug($slug) : Méthode qui cherche une catégorie dans la base de données en fonction de son slug. Le slug est une chaîne de caractères unique utilisée pour identifier la catégorie dans l’URL.
            */

        //* -> Si la catégorie n'existe pas, redirige vers la page d'accueil
        if (!$category) {
            return $this->redirectToRoute('app_home');
        }
            /*
                - if (!$category) : Vérifie si aucune catégorie n’a été trouvée pour le slug donné.
                - /!$category : Si $category est null, cela signifie qu'aucune catégorie n’a été trouvée avec ce slug.
                - return $this->redirectToRoute('app_home'); : Redirige vers la route app_home (généralement la page d’accueil) si la catégorie n’a pas été trouvée.
                - $this->redirectToRoute('app_home') : Méthode pour rediriger vers une route Symfony. Ici, elle redirige l’utilisateur vers la page d’accueil si le slug de catégorie est incorrect.
            */

        //* -> Rend la vue Twig et lui passe la catégorie récupérée
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
            /*
                - $this->render(...) : Utilise le moteur de templates Twig pour générer et afficher une vue.
                - 'category/index.html.twig' : Chemin du fichier de template Twig qui sera utilisé pour afficher la page de la catégorie.
                - 'category' => $category : Passe la catégorie trouvée ($category) en tant que variable category à la vue, pour pouvoir l’afficher dans le fichier Twig.
            */
    }
}
/*
!Explications supplémentaires :
* Route :
    #[Route('/categories/{slug}', name: 'app_category')] :
        - Définit la route /categories/{slug} pour accéder à une catégorie en fonction de son slug. Le slug est une chaîne de texte utilisée dans l’URL pour identifier une catégorie de manière lisible.
        - name: 'app_category' donne un nom à la route, permettant de l’utiliser facilement dans d’autres parties de l’application.

* Dépendances injectées :
    - CategoryRepository $categoryRepository : Injecte le repository CategoryRepository, permettant d’interagir avec la base de données pour récupérer les informations de la catégorie.

* Méthode index() :
    - $category = $categoryRepository->findOneBySlug($slug); :
        Utilise le repository pour récupérer une catégorie en fonction de son slug. findOneBySlug() est une méthode de repository qui renvoie la catégorie correspondant au slug.

    - Vérification d'existence :
        - if (!$category) : Vérifie si la catégorie a été trouvée.
        - Si la catégorie n'existe pas (c'est-à-dire que $category est null), l’utilisateur est redirigé vers la page d'accueil.

    - Affichage de la vue :
        - $this->render('category/index.html.twig', [...]); :
            - Rend le template Twig category/index.html.twig pour afficher la catégorie.
            - Passe la catégorie ($category) à la vue sous forme de variable.
!Résumé
*Ce contrôleur affiche les détails d'une catégorie basée sur son slug. Si le slug ne correspond à aucune catégorie, il redirige l'utilisateur vers la page d'accueil. Il rend ensuite la vue category/index.html.twig, en passant la catégorie récupérée pour un affichage détaillé. Ce comportement assure que seules les catégories valides sont affichées à l’utilisateur.
*/