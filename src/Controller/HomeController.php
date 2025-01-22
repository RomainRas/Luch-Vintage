<?php
//? Contrôleur qui gère l’affichage de la page d’accueil :
//! Controller qui gere la route vers note homepage
    //! symfony console make:controller
        //! Nom du controller : HomeController
            //! created: src/Controller/HomeController.php
            //! created: templates/homes/index.html.twig

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

use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    /*
        - namespace : Mot clé php pour definir l'espace de nommage App\Controller qui est l'espace de nommage pour eviterr les conflits de nom
        - App\Controller : c'est le dossier Controller dans l'appplication. la class HommeController fait partie du namespace HomeController 
        - use : Mot clé pour importer une classe/composant à partir d'un autre espace de nom que ( App\Controller )
        - Symfony\Bundle\FrameworkBundle\Controller\AbstractController : importe de la class AbstractController de Symfo qui est une classe de base pour les controleurs. Elle fournis plusieurs methodes neccessaires
        - Importe de la classe Response : Class utilisé pour renvoyer une réponse HTTP au navigateur. Elle encapsule le contenue à envoyer (page HTML)
        - Import de l'attribut Route : Attribu utilisé pour definir les routes dans l'application, c'est à dire les URL qui declenches les actions des controleurs
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class HomeController extends AbstractController
    /*
        - class : Mot clé pour déclarer une class en PHP
        - HomeController : nom de la classe. C'est le controlleur qui va gerer la page d'acceuil de l'application dans ce cas
        - extends : Mot clé php = la class HomeController hérite de la class AbstractController (donc de ses propriété/methodes et fonctions)
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route vers la page d'acceuil
    #[Route('/', name: 'app_home')]
        /*
            - #[Route('/', name: 'app_home')]
            - '/' : definit la route ( ici page d'acceuil)
            - name: 'app_home' : Donne un nom à la route. il permet de référencer la route dans le code ( pour créer les liens entre les pages)
        */
    //! ** Déclaration de la méthode `index` qui gère la page d'accueil. ** !//
    public function index(HeaderRepository $headerRepository, ProductRepository $productRepository): Response
        /* 
            - public : methode qui peut etre appelée depuis l'exterieur de la class
            - function : mot clé pour déclarer la methode
            - index : Nom de la methode, utilisé pour représenter l'action du controler ( index = page d'acc )
            - (): Responde : indique que la methode retourne un objet de type Responde = reponse HTTP envoyée au navigateur
        */
    {

        return $this->render('home/index.html.twig',[
            'headers' => $headerRepository->findAll(),
            'productsInHomepage' => $productRepository->findByIsHomepage(true)
        ]);
            /*
                - return : mot clé php pour retourner une valeur depuis la methode
                - $this->render() : utilise la methode render qui fait partie de la class AbstractController pour afficher une vue (template)
                    - $this : ref à l'instance actuelle de la class (HomeController)
                    - render() : methode de AbstractControler qui permet de generer une page HTML a partir d'un fichier twig
                        - 2 arguments -> le chemin du fichier twig ('home/index.html.twig) et un tableau de variable à passer à la vue ( [] ) la il est vide, si il y avait eu des donnes on aurait put avoir ('variable' =>'valeur')
            */
    }
}
/*
!Explications supplémentaires :
* Namespace :
    Le namespace App\Controller organise les contrôleurs et permet d'éviter les conflits de noms. Toutes les classes du répertoire Controller sont sous ce namespace.

* Imports :
    - AbstractController : Fournit des méthodes pratiques comme render pour les contrôleurs.
    Response : Permet de créer des réponses HTTP que le navigateur va interpréter.
    Route : Utilisé pour définir des chemins URL qui déclenchent les méthodes du contrôleur.

* Classe HomeController :
    - Cette classe hérite d'AbstractController, ce qui lui donne accès aux fonctionnalités de base pour gérer les requêtes et les réponses, notamment render() pour afficher des vues.

* Route et méthode index() :
    - La route #[Route('/', name: 'app_home')] indique que la méthode index répond à l'URL racine /.
    - La méthode index() génère et retourne une vue pour la page d'accueil, sans passer de variables à la vue (tableau vide []).
    - Le fichier Twig home/index.html.twig est le modèle utilisé pour créer le contenu HTML de la page d'accueil.

* Ce contrôleur est simple mais essentiel pour gérer la page d'accueil de votre application Symfony. Il utilise les fonctionnalités de base d’un contrôleur Symfony, avec une route et un rendu de vue Twig.
*/