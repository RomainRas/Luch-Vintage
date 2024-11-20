<?php
//? Ce contrôleur AccountController gère la gestion des pwd user
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller\Account;
    /*
        - namespace : Definit l'espace de la classe, ici App\Controller indique que cette classe appartient au dossier Controller\Account de l’application Symfony.
    */

use App\Form\PasswordUserType;
    /*
        - Rôle : Classe de base pour tous les contrôleurs Symfony.
        - Utilité : Fournit des méthodes pratiques comme :
            - render() : Pour afficher des vues Twig.
            - getUser() : Pour récupérer l’utilisateur actuellement connecté.
    */

use Doctrine\ORM\EntityManagerInterface;
    /*
        - Rôle : Gestionnaire d’entités de Doctrine.
        - Utilité : Permet d’interagir avec la base de données pour persister (enregistrer), mettre à jour ou supprimer des entités.
    */

use Symfony\Component\HttpFoundation\Request;
    /*
        - Rôle : Fournit un objet représentant la requête HTTP reçue par le serveur.
        - Utilité : Accéder aux données de la requête, comme les champs de formulaire soumis, les paramètres d’URL, ou les fichiers téléchargés.
    */

use Symfony\Component\HttpFoundation\Response;
    /*
        - Rôle : Fournit un objet représentant la réponse HTTP envoyée au client.
        - Utilité : Retourner des pages HTML, des JSON, ou d’autres types de contenu au client.
    */

use Symfony\Component\Routing\Attribute\Route;
    /*
        - Rôle : Déclare une route Symfony, associant une URL spécifique à une méthode de contrôleur.
        - Utilité :
            - Définir des chemins d’accès pour les fonctionnalités du site.
            - Ajouter des noms aux routes pour les référencer facilement dans le code.
    */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    /*
        - Rôle : Classe de base pour tous les contrôleurs Symfony.
        - Utilité : Fournit des méthodes pratiques comme :
            - render() : Pour afficher des vues Twig.
            - getUser() : Pour récupérer l’utilisateur actuellement connecté.
    */

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    /*
        - Rôle : Fournit des méthodes pour hacher (chiffrer) les mots de passe de manière sécurisée.
        - Utilité : Vérifier et encoder les mots de passe des utilisateurs, notamment lors de leur création ou modification.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
    class PasswordController extends AbstractController 
    // La classe PasswordController gère les actions relatives au pwd du compte utilisateur
{


/*
************************************************************
!                      PROPRIETES                          *
************************************************************
*/
    private $entityManager;
    /*
        - private : La propriété est accessible uniquement à l'intérieur de la classe.
        - $entityManager : Représente l'instance de EntityManagerInterface injectée dans le constructeur.
    */

    //*  - Rôle : Injection de l’EntityManagerInterface pour interagir avec la base de données.
    //*  - Utilité : Utilisé pour persister, supprimer ou mettre à jour des entités.
    public function __construct(EntityManagerInterface $entityManager) 
    {
    $this->entityManager = $entityManager;
    }
    /*
    - __construct : Méthode appelée automatiquement lors de l'instanciation de la classe.
    - EntityManagerInterface $entityManager : Injecte l'Entity Manager pour gérer les entités.
        - Rôle : Injection de l’EntityManagerInterface pour interagir avec la base de données.
        - Utilité : Utilisé pour persister, supprimer ou mettre à jour des entités.
    - $this->entityManager : Stocke l'instance de l'Entity Manager pour une utilisation dans toute la classe.
    */


/*
************************************************************
!              Modification du mot de passe                *
************************************************************
*/
    //*-> Définit la route `/compte/modifier-mot-de-passe`, qui gère la modification du mot de passe.
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
        // Cette route est associée à l'URL '/compte/modifier-mot-de-passe'. Elle permet de gérer la modification du mot de passe.
        // name: 'app_account_modify_pwd' est le nom de la route pour la modification du mot de passe.
    
    //! ** Methode de la modif du pwd ** !//
    //? https://symfony.com/doc/current/security/passwords.html#hashing-the-password (UserPasswordHasherInterface)
    //*-> Méthode pour gérer l'affichage et le traitement du formulaire de modification de mot de passe.
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
        /* 
            - password : methode qui gère l'affichage et le traitement du formulaire de modification du mot de passe.
            - Request $request : Objet qui contient les informations de la requête HTTP (comme les données du formulaire soumises)
            - UserPasswordHasherInterface $passwordHasher : Represente l'objet qui implemente l'interface UserPasswordHasherInterface qui gere les hachage et la verif des pwd
                - on va utiliser dans ce cas isPasswordValid pour verifier la modification de pwd
        */
    {   
        //* 1. Récupération de l'utilisateur connecté
        $user = $this->getUser(); 
            /* 
                - $this->getUser() permet de récupérer l'utilisateur actuellement connecté. Il s'agit généralement d'une instance de la classe User.
            */

        //* 2. Création du formulaire   
        // ? make:form PasswordUserType
        //? https://symfony.com/doc/current/forms.html#passing-options-to-forms
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]); 
            /*basé sur `PasswordUserType`, associé à l'utilisateur connecté (`$user`).
                - PasswordUserType::class : Indique le type de formulaire à créer. Ici, on spécifie qu'on veut créer un formulaire basé sur la classe PasswordUserType, qui est un formulaire personnalisé créé pour gérer la modification de mot de passe.
                - $user : donnéesliées au formulaire. C'est l'objet User qui représente l'utilisateur en cours de modification.
                - [passwordHasher' => $passwordHasher]` : Permet de passer des options supplémentaires au formulaire.
                    - $passwordHasher est l'instance de UserPasswordHasherInterface, ce qui permet de hacher les pwd. Cette option, ce parametre va permetre au formulaire d'acceder à l'objet passwordHasher pour hasher le mot de passe actuelle et donc le comparer avec celui inscrit en BDD grace à $options['require_due_date'] (voir doc)
            */

        //* 3. Traitement du formulaire
        $form->handleRequest($request); 
            /*
                - handleRequest() : analyse la requête et met à jour l'objet `$user` avec les données du formulaire si le formulaire a été soumis.
            */
        if ($form->isSubmitted() && $form->isValid()) { 
            /*
                - On vérifie si le formulaire a été soumis avec isSubmitted() et si les données sont valides avec isValid().
                - dd($form->getData()); : On peux faire un dump de debug pour verifier 
                    - dd() est une fonction de débogage qui arrête l'exécution du script et affiche les données soumises par le formulaire.
            */
            //? https://symfony.com/doc/current/session.html#flash-messages
            // https://getbootstrap.com/docs/5.3/components/alerts/#examples
            $this->addFlash(
                'success',
                "Votre mot de passe est correctement mis à jour."
            );
                /*
                    - $this->addFlash : ajoute un message flash de confirmation pour informer l'utilisateur que son mot de passe a été mis à jour.
                */
            $entityManager->flush();
                /*
                    - $entityManager->flush(); Exécute toutes les opérations en attente dans Doctrine, enregistrant ainsi les modifications en base de données.
                */
        }

        //* 4. Retour la vue Twig 
        return $this->render('account/password/index.html.twig', [
            'modifyPwd' => $form->createView() 
                /*
                    - account/password.html.twig`: qui contient le formulaire de modification du mot de passe.
                    - La méthode createView() permet de générer l'affichage HTML du formulaire.
                    - Le formulaire est passé à la vue Twig sous la variable 'modifyPwd', qui sera utilisée dans 'account/password.html.twig' pour afficher le formulaire.
                */
        ]);
    }
}
/*
!Explications supplémentaires :
    *Namespace et Imports :
        - namespace : Organise la classe dans la structure de l'application, évitant ainsi les conflits de noms.
        - use : Importe les classes nécessaires :
        - PasswordUserType : Formulaire pour modifier le mot de passe.
        - EntityManagerInterface : Pour interagir avec la base de données.
        - UserPasswordHasherInterface : Pour hacher et vérifier les mots de passe de manière sécurisée.

    *Routes :
        - La route #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')] pointe vers la méthode password() pour gérer le changement de mot de passe.

    *Gestion du pws dans password() :
        - createForm() : Crée un formulaire basé sur PasswordUserType et associé à l'utilisateur actuellement connecté.
        - handleRequest() : Gère la soumission du formulaire et met à jour l'objet $user.
        - isSubmitted() et isValid() : Vérifient que le formulaire a été soumis et que les données sont valides.
    *Gestion du mot de passe :
        - UserPasswordHasherInterface permet de hacher les mots de passe de manière sécurisée avant de les stocker.
        - En cas de mise à jour réussie, un message flash est ajouté pour confirmer le changement de mot de passe.
    *Retour de la Vue :
        - render() affiche la vue Twig avec le formulaire de modification du mot de passe.
        - modifyPwd : La vue du formulaire est transmise au fichier Twig sous cette variable, pour l'affichage du formulaire dans account/password.html.twig.
    
    *Résumé
    Ce contrôleur gère les actions relatives au compte utilisateur : il affiche la page de compte, permet de modifier le mot de passe de manière sécurisée et affiche des messages de confirmation. Symfony et Twig prennent en charge l'affichage et la validation des données du formulaire, simplifiant ainsi la gestion du compte utilisateur.
*/


