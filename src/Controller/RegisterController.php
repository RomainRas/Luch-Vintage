<?php
//? RegisterController gère la création de nouveaux utilisateurs avec un formulaire d'inscription :
//! Controller qui gere l'inscription
    //! symfony console:make controller
        //! created: src/Controller/RegisterController.php
        //! created: templates/register/index.html.twig

/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller;
use App\Classe\Mail;
use App\Form\RegisterUserType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
    /*
        - namespace : Mot-clé PHP pour définir l'espace de nommage. Ici, `App\Controller` permet d'organiser la classe RegisterController dans le dossier `Controller`.
            - Cela permet d'éviter les conflits de noms de classe dans l'application.
        - `use` : Mot-clé qui permet d'importer une classe externe dans le fichier courant.
        - `RegisterUserType` : C'est le type de formulaire créé avec Symfony pour gérer l'inscription des utilisateurs. On l'a créé avec la console Symfony.
use App\Entity\User;
use App\Classe\Mail;
        - `User` : Cette classe représente l'entité `User`, qui est liée à la table `user` dans la base de données.
            - On utilisera cette entité pour manipuler les données de l'utilisateur dans la base.
        - `EntityManagerInterface` : Interface fournie par Doctrine, qui permet de gérer les entités dans la base de données (insertion, mise à jour, suppression).
            - On l'utilise pour interagir avec la base de données.
        - `Request` : Classe Symfony qui encapsule les informations relatives à la requête HTTP envoyée par le client (comme les données soumises dans le formulaire).
        - `AbstractController` : Classe de base pour tous les contrôleurs Symfony. Elle fournit des méthodes comme `render()` pour afficher les vues, ou `createForm()` pour créer des formulaires.
        - `Response` : Classe utilisée pour encapsuler la réponse HTTP que le serveur envoie au client (comme une page HTML).
        - `Route` : Attribut utilisé pour définir les routes dans Symfony. Il associe une URL spécifique à une méthode du contrôleur.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class RegisterController extends AbstractController
    /*
        - `class` : Mot-clé PHP pour déclarer une classe.
        - `RegisterController` : Nom de la classe. Ce contrôleur est responsable de la gestion des routes liées à l'inscription des utilisateurs.
        - `extends AbstractController` : La classe hérite de `AbstractController`, ce qui lui donne accès à des méthodes comme `render()` et `getUser()`.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route pour l'inscription
    #[Route('/inscription', name: 'app_inscription')]
        /*
            - `#[Route(...)]` : Attribut PHP qui définit une route.
            - `'/register'` : URL associée à cette méthode. Cela signifie que lorsque l'utilisateur visitera `/inscription`, cette méthode sera appelée.
            - `name: 'app_register'` : Donne un nom unique à cette route pour pouvoir la référencer facilement ailleurs dans le code.
        */
    //! ** Méthode pour afficher le formulaire d'inscription et gérer l'enregistrement de l'utilisateur. ** /
    public function index(Request $request, EntityManagerInterface $entityManager): Response
        /*
            - `public` : Indique que la méthode peut être appelée depuis l'extérieur de la classe.
            - `function` : Mot-clé pour déclarer une méthode.
            - `index` : Nom de la méthode. Elle représente la page principale pour l'inscription d'un utilisateur.
            - `Request $request` : Paramètre qui représente la requête HTTP envoyée par le client. Ici, il contient les données du formulaire.
            - `EntityManagerInterface $entityManager` : Permet de gérer les interactions avec la base de données, comme l'insertion d'un utilisateur.
            - `: Response` : La méthode retourne une réponse HTTP (page HTML).
        */
    {
        // dd($request); // Cette ligne est généralement utilisée pour déboguer et afficher les données de la requête.
        //* -> Crée une nouvelle instance de l'entité `User`, qui sera remplie avec les données du formulaire.
        $user = new User();
            /*
                - `new User()` : On crée une nouvelle instance de la classe `User`. 
                - Cet objet `$user` va représenter l'utilisateur que l'on veut enregistrer.
            */

        //* -> Crée un formulaire basé sur `RegisterUserType` et l'associe à l'objet `$user`, pour lier les champs du formulaire aux propriétés de l'entité `User`.
        $form = $this->createForm(RegisterUserType::class, $user);
            /*
                - `createForm()` : Méthode de `AbstractController` qui permet de créer un formulaire Symfony.
                - `RegisterUserType::class` : Le type de formulaire que l'on crée, ici un formulaire d'inscription basé sur la classe `RegisterUserType`.
                - `$user` : On associe l'objet `$user` au formulaire. Cela permet de lier les champs du formulaire aux propriétés de l'objet `User`.
            */

        //* -> Verification si le formulaire a bien été soumis
        $form->handleRequest($request);
            /*
                - `handleRequest($request)` : Cette méthode vérifie si le formulaire a été soumis (en analysant la requête).
                - Si le formulaire est soumis, elle met à jour l'objet `$user` avec les données du formulaire.
            */

        //* -> Vérifie que le formulaire a été soumis et que les données sont valides (par exemple, email au bon format, champs requis remplis).
        if ($form->isSubmitted() && $form->isValid()) {
            /*
                - `isSubmitted()` : Vérifie si le formulaire a été soumis.
                - `isValid()` : Vérifie si les données du formulaire sont valides (par exemple, si l'email est au bon format, si les champs obligatoires sont remplis, etc.).
            */

            //* -> Preparation de l'insertion de l'objet `$user` dans la base de données.
            $entityManager->persist($user);
                /*
                    - `persist()` : Prépare l'enregistrement de l'objet `$user` dans la base de données. Cela ne l'enregistre pas encore, mais indique à Doctrine qu'il faut l'ajouter.
                */

            //* -> Execution de l'insertion, enregistrant ainsi l'utilisateur dans la base de données.
            $entityManager->flush();
                /*
                    - `flush()` : Exécute toutes les opérations en attente (ici, l'enregistrement de l'utilisateur dans la base de données).
                    - Si le formulaire est valide, les données de l'utilisateur sont désormais sauvegardées dans la base de données.
                */

            //* -> Ajout d'un message flash de succès pour informer l'utilisateur que son compte a bien été créé.
            $this->addFlash(
                'success',
                "Votre compte à bien été créé. Veuillez vous connecter"
            );
            
            //* -> Envoie d'un email de confirmation d'inscription
            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstname(),
            ];
            $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),'Bonjour, merci pour votre inscription<', "welcome.html", $vars);
            
            //* -> Redirection de l'utilisateur vers la page de connexion après inscription réussie.
            return $this->redirectToRoute('app_login');

        }
        //* -> Passe la vue du formulaire (`registerForm`) pour que le template Twig puisse afficher le formulaire.
        return $this->render('register/index.html.twig', [
            /*
                - `return` : Renvoie une réponse HTTP. Ici, cela signifie que l'on renvoie une page HTML générée par Twig.
                - `$this->render()` : Méthode de `AbstractController` pour afficher un template Twig (fichier HTML dynamique).
            */

            'registerForm' => $form->createView()
            /*
                - `createView()` : Génère la vue HTML du formulaire, qui sera utilisée dans le fichier Twig pour afficher les champs du formulaire.
                - `'registerForm' => $form->createView()` : On passe la vue du formulaire au fichier Twig sous forme de variable `registerForm`, que le template Twig utilisera pour afficher le formulaire.
            */
        ]);
        // Le fichier Twig `register/index.html.twig` sera utilisé pour afficher la page HTML contenant le formulaire d'inscription.
    }
}
/* 
!Explications supplémentaires
* Namespace et Imports :
    - namespace organise les classes dans une structure logique.
    - Les use statements importent des classes essentielles :
        - RegisterUserType pour le formulaire d'inscription.
        - User pour l'entité utilisateur.
        - EntityManagerInterface pour gérer les interactions avec la base de données.

* Route et Méthode index() :
    - La route #[Route('/inscription', name: 'app_inscription')] déclenche la méthode index() pour afficher le formulaire d'inscription.
    - La méthode index() crée un formulaire d'inscription, traite les données soumises, valide le formulaire et enregistre les données si elles sont valides.

* Gestion du formulaire :
    - createForm() : Génère le formulaire d'inscription en associant les champs à l'objet $user.
    - handleRequest() : Gère la soumission du formulaire, liant les données à l'objet $user.
    - isSubmitted() et isValid() : Vérifient que le formulaire a été soumis et que les données sont valides avant de persister l'objet.

* Persist et Flush :
    - persist() indique à Doctrine de préparer l'insertion de l'utilisateur.
    - flush() exécute toutes les opérations de base de données en attente, finalisant l'enregistrement de l'utilisateur.
    
* Retour de la Vue :
    - render() affiche la page d'inscription en utilisant le fichier register/index.html.twig.
    - registerForm : La vue du formulaire est transmise au fichier Twig, permettant d'afficher les champs du formulaire.

* Résumé
Ce contrôleur gère l'affichage d'un formulaire d'inscription, la soumission des données, et l'enregistrement d'un nouvel utilisateur en base de données. Si les données sont valides, l'utilisateur est redirigé vers la page de connexion avec un message de confirmation. Le formulaire et la gestion des erreurs sont pris en charge par Symfony, rendant le code simple et maintenable.
 */