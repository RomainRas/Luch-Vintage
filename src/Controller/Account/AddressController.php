<?php
//? Ce contrôleur AccountController gère la gestion des adresses user
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller\Account;
    /*
        - namespace : Definit l'espace de la classe, ici App\Controller indique que cette classe appartient au dossier Controller\Account de l’application Symfony.
    */

use App\Classe\Cart;
use App\Entity\Address;
    /*
        - Rôle : Classe de base pour tous les contrôleurs Symfony.
        - Utilité : Fournit des méthodes pratiques comme :
            - render() : Pour afficher des vues Twig.
            - getUser() : Pour récupérer l’utilisateur actuellement connecté.
    */

use App\Form\AddressUserType;
    /*
        - Rôle : Importation du formulaire AddressUserType pour gérer la création ou la modification des adresses des utilisateurs.
        - Utilité : Facilite la manipulation des adresses via un formulaire Symfony.
    */

use App\Repository\AddressRepository;
    /*
        - Rôle : Importe le dépôt AddressRepository pour accéder aux méthodes spécifiques à la gestion des adresses.
        - Utilité : Rechercher, filtrer ou lister les adresses dans la base de données grâce aux requêtes personnalisées.
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

/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
    class AddressController  extends AbstractController 
    // La classe AddressController gère les actions relatives aux adresses du compte utilisateur
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
!                      VUE ADRESSES                        *
************************************************************
*/
    //! ** Route et methode pour la vue twig de la gestion des adresses ** !//
    #[Route('/compte/adresses', name: 'app_account_addresses')]
        /*
            - #[Route(...)] : Attribut PHP définissant une route Symfony.
            - '/compte/adresses' : URL associée à cette méthode. Accéder à cette URL exécute la méthode addresses.
            - name: 'app_account_addresses' : Nom unique pour cette route, utile pour la référencer ailleurs dans le projet (exemple : dans un lien Twig ou une redirection).
        */
    //* -> Méthode pour afficher la page de gestion des adresses utilisateur.
    public function index(): Response 
    {
        //* Retourne la vue Twig accout/addresses.html.twig
        return $this->render('account/address/index.html.twig'); 
    }
        /*
            - public : La méthode est accessible depuis l'extérieur de la classe.
            - function : Déclare une méthode.
            - addresses : Nom de la méthode.
            - : Response : Indique que cette méthode retourne un objet Response (par exemple, une page HTML).
            - $this->render() : Méthode fournie par AbstractController. Génère une réponse HTTP avec une vue Twig.
            - 'account/addresses.html.twig' : Fichier Twig utilisé pour afficher la page des adresses.
        */

    
/*
************************************************************
!                   SUPPRESSION ADRESSE                    *
************************************************************
*/
    //! ** Route et methode pour la suppression d'une adresse ** !//
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
        /*
            - '/compte/adresses/delete/{id}' : URL avec un paramètre dynamique {id}. Ce paramètre correspond à l'identifiant de l'adresse à supprimer.
            - name: 'app_account_address_delete' : Nom unique pour cette route.
        */
    //* Méthode pour supprimer l'adresse
    public function delete($id, AddressRepository $addressRepository): Response 
        /*
            - $id : Paramètre dynamique capturé depuis l'URL. Il représente l'identifiant de l'adresse à supprimer.
            - AddressRepository $addressRepository : Dépendance injectée pour accéder aux adresses dans la base de données.
        */
    {
        //* 1. Recuperation de l'adresse
        $address = $addressRepository->findOneById($id);
        /*
            - findOneById($id) : Méthode personnalisée du repository pour trouver une adresse avec l'identifiant $id.
        */
        //* 2. Verification
        if (!$address OR $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }
        /*
            - !$address : Vérifie si aucune adresse n'a été trouvée.
            - $address->getUser() !== $this->getUser() : Vérifie que l'adresse appartient bien à l'utilisateur connecté.
            - Redirection : Si l'une des deux conditions est vraie, l'utilisateur est redirigé vers la liste des adresses.
        */
        //* 3. Flash Message
        $this->addFlash(
            'success',
            "Votre adresse est correctement supprimée."
        );
        /*
            - addFlash() : Ajoute un message temporaire affiché après redirection.
        */
        //* Suppression 
        $this->entityManager->remove($address);
        $this->entityManager->flush();
        /*
            - remove($address) : Prépare la suppression de l'adresse.
            - flush() : Exécute la suppression dans la base de données.
        */
        //* Redirection
        return $this->redirectToRoute('app_account_addresses');

    }


/*
************************************************************
!                   AJOUT/MODIF ADRESSE                    *
************************************************************
*/
    //! ** Route et methode pour l'ajout et la modif d'une adresse ** !//
    //* -> Route pour ajouter une adresse
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
        /*
            - '/compte/adresse/ajouter/{id}' : URL pour ajouter ou modifier une adresse. Le paramètre {id} est facultatif grâce à :
            - defaults: ['id' => null] : Définit la valeur par défaut de $id à null.
        */
    //* -> Méthode
    public function form(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response 
        /*
            - $request : Objet contenant les données de la requête HTTP (comme les champs du formulaire soumis).
            - $id : Identifiant de l'adresse, utilisé pour savoir si on modifie ou crée une nouvelle adresse.
            - $addressRepository : Dépendance injectée pour manipuler les adresses.
            - Cart $cart : Dépendance injectée pour manipuler le panier
        */
    {   
        //* 1. Récupération ou création d'une adresse
        if ($id) {
            $address = $addressRepository->findOneById($id);
            if (!$address OR $address->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
            /*
                - if ($id) : Cas de modification ($id est défini) :
                    - $address = $addressRepository->findOneById($id); : Récupère l'adresse dans la base via $addressRepository.
                    - if (!$address OR $address->getUser() !== $this->getUser()) : Vérifie que l'adresse existe et qu'elle appartient bien à l'utilisateur connecté.
                        - return $this->redirectToRoute('app_account_addresses'); : Redirige si les conditions ne sont pas remplies.
            */
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }
            /*
                - else : Cas de création ($id est null) :
                    - $address = new Address(); : Crée une nouvelle instance d'Address.
                    - $address->setUser($this->getUser()); : Associe l'adresse à l'utilisateur connecté via $address->setUser().
            */

        //* 2. Formulaire
        $form = $this->createForm(AddressUserType::class, $address);
            /*
                - createForm() : Crée un formulaire basé sur AddressUserType.
                - $address : Lie les champs du formulaire aux propriétés de l'objet $address.
            */

        //* 3. Validation
        $form->handleRequest($request);
            /*
                - handleRequest() : Traite les données soumises dans le formulaire.
            */

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Votre adresse est correctement sauvegardée."
            );
            
            if ($cart->fullQuantity() > 0) {
                return $this ->redirectToRoute("app_order");
            }

            return $this->redirectToRoute("app_account_addresses");
        }
            /*
                - isSubmitted() : Vérifie si le formulaire a été soumis.
                - isValid() : Vérifie si les données respectent les règles de validation.
                - persist($address) : Prépare l'enregistrement ou la mise à jour de l'adresse.
                - flush() : Exécute l'opération en base de données.

                - $this->addFlash : Message flash qui informe l'utilisateur que l'adresse a été sauvegardée.

                -if ($cart->fullQuantity() > 0) : si le panier est superieur a 0
                - return $this ->redirectToRoute("app_order") : redirige sur la route (app_order) pour que si l'utilisateur n'avait pas d'adresse au moment de l'achat il sera redirigé apres la creation de celle ci au tunnel d'achat

                - return $this->redirectToRoute("app_account_addresses"); : Redirection : Renvoie vers la liste des adresses.
            */

        //* 4. Affichage
        return $this->render('account/address/form.html.twig', [
            'addressForm' => $form
        ]); 
            /*
                - Vue Twig : Affiche le formulaire pour ajouter ou modifier une adresse.
                - Variable addressForm : Contient le formulaire à afficher dans le fichier Twig.
            */
    }
}
/*
!Explications supplémentaires :
    *Namespace et Imports :
        - namespace : Organise la classe dans la structure de l'application, évitant ainsi les conflits de noms.
        - use : Importe les classes nécessaires :

    *Routes :
        - La route #[Route('/compte', name: 'app_account')] pointe vers la méthode index() pour afficher la page de compte utilisateur.

    *Gestion des adresses postale
        - Afficher les adresses d’un utilisateur :
            - Route : /compte/adresses
            - Vue Twig : addresses.html.twig
            - Contrôleur : méthode addresses()
        - Ajouter/modifier une adresse :
            - Route : /compte/adresse/ajouter/{id}
            - Vue Twig : addressForm.html.twig
            - Contrôleur : méthode form()
                - Si {id} est présent : l’adresse est modifiée.
                - Si {id} est null : une nouvelle adresse est créée.
                - Si il y a plus de zero article l'user sera redirigé vers le tunnel d'achat
        - Supprimer une adresse :
            - Route : /compte/adresses/delete/{id}
            - Contrôleur : méthode addressDelete()
            - L'adresse est supprimée après vérification qu'elle appartient à l'utilisateur connecté.
    
    *Résumé
    Ce contrôleur gère les actions relatives au compte utilisateur : il affiche la page des adresses, permet de modifier/suprimer et créer les adresses de manière sécurisée et affiche des messages de confirmation. Symfony et Twig prennent en charge l'affichage et la validation des données du formulaire, simplifiant ainsi la gestion du compte utilisateur.
*/
?>