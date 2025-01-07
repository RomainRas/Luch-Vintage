<?php
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Controller;
    /*
        - Place ce contrôleur dans le namespace App\Controller.
    */

use App\Classe\Cart;
    /*
        - App\Classe\Cart : Gère les opérations liées au panier.
    */

use App\Entity\User;
    /*
        - App\Entity\User : L'entité User pour manipuler les données utilisateur.
    */

use App\Entity\Order;
use App\Entity\OrderDetails;
    /*
        - App\Entity\Order et OrderDetails : Gèrent les commandes et leurs détails.
    */

use App\Form\OrderType;
    /*
        - App\Form\OrderType : Formulaire pour la sélection de l'adresse et du transporteur.    
    */

use Doctrine\ORM\EntityManagerInterface;
    /*
        - EntityManagerInterface : Permet d'interagir avec la base de données via Doctrine.
    */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
    /*
        - Request et Response : Manipulent les requêtes et réponses HTTP.
    */

use Symfony\Component\Routing\Attribute\Route;
    /*
        - Route : Déclare les routes Symfony.
    */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    /*
        - AbstractController : Fournit des fonctionnalités de base pour un contrôleur Symfony.
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
! ROUTE ET METHODES Addresse de livraison et transporteur  *
************************************************************
*/
    //*  Affiche la page de sélection de l'adresse de livraison et du transporteur.
    //* -> 1ere etape du tunnel d'achat
        //* Choix de l'adresse de livraison et du transporteur
    #[Route('/commande/livraison', name: 'app_order')]
        /*
            - Définit /commande/livraison comme URL pour cette méthode. Le nom de la route est app_order.
            - name: 'app_order' : Nom unique pour cette route.
        */
    public function index(): Response
    {
        //* 1. Récupération de l'utilisateur connecté :
        //* Utilise la méthode getUser() pour récupérer l'utilisateur actuellement connecté.
        $user = $this->getUser();
            /*
                - Récupère l'utilisateur connecté via la méthode getUser() fournie par AbstractController.
            */
        
        //* 2. Validation 
        //* Vérifiez si l'utilisateur est connecté (Si l'utilisateur n'est pas connecté :)
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
            /*
                - Vérifie que l'utilisateur est connecté :
                    - Si l'utilisateur n'est pas connecté, une exception est levée.
            */
        
        //* Vérifiez si l'utilisateur a des adresses (Si l'utilisateur n'a pas d'adresses enregistrées)
        if ($user->getAddresses()->isEmpty()) {
            return $this->redirectToRoute('app_account_address_form');
        }
            /*
                - Redirection si aucune adresse n'est disponible :
                - Si l'utilisateur n'a pas d'adresse enregistrée, il est redirigé vers le formulaire d'ajout d'adresse.
            */
        

        //* 3. Création du formulaire :
        // Création du formulaire avec les adresses existantes
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $user->getAddresses(),
            'action' => $this->generateUrl('app_order_summary')
        ]);
            /*
                - Formulaire basé sur la classe OrderType.
                    - Options du formulaire :
                        - addresses : Passe les adresses existantes de l'utilisateur.
                        - action : URL où le formulaire sera soumis.
            */
        
        //* 4. Affichage de la vue
        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
            /*
                - Affiche la vue Twig order/index.html.twig :
                - Passe le formulaire généré à la vue Twig sous le nom deliverForm.
            */
    }


/*
************************************************************
!        ROUTE ET METHODES Recap et bouton paiement        *
************************************************************
*/
    //* -> 2eme etape du tunnel d'achat
        //* Recap de la commande de l'user  
        //* Insertion en BDD
        //* Preparation du paiement vers stripe
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
        /*
            - Définit /commande/recapitulatif comme URL. Nom de la route : app_order_summary.
        */

    //* Logique dans add()
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManagerInterface): Response
        /*
            - Paramètres :
                - Request : Permet d'accéder aux données soumises.
                - Cart : Service pour manipuler le panier.
                - EntityManagerInterface : Permet de gérer les entités en base de données.
        */
    {
        $user = $this->getUser();
            
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        //* 1. Validation des prérequis :
            //* L'utilisateur doit être connecté.
            //* La méthode HTTP doit être POST :
        if ($request->getMethod() !='POST') {
            return $this->redirectToRoute('app_cart');
        }
            /*
                - Vérifie que la requête est de type POST :
                    - Redirige vers le panier si la méthode n'est pas correcte.
            */

        //* 2. Création et traitement du formulaire :
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $user->getAddresses(),
        ]);
            /*
                - Création et validation du formulaire 
                    - Récupère les adresses et transporteurs soumis.
            */

        $form->handleRequest($request);

        //* 3. Récupération des données du panier :
        $products = $cart->getCart();

        if($form->isSubmitted() &&  $form->isValid()){
            /*
                - Vérifie que le formulaire est soumis et valide.
            */

            //* 4. Stockage des données en BDD de la commande 
            //* 4.1 Création de la chaîne d'adresse
                /*
                    - Utiliser le dump pour debuger not variables et methode pour retrouver comment acceder à notre information !
                        - dd($form->getData());
                        - dd($form->get('addresses')->getData());
                */
            //* -> Creation d'une variable $addressObj pour eviter de repeter à chaque fois $form->get('addresses')->getData() ...
            $addressObj = $form->get('addresses')->getData();
                
            $address = $addressObj->getFirstname().' '.$addressObj->getLastname().'<br>';
            $address .= $addressObj->getAddress().'<br>';
            $address .= $addressObj->getPostal().' '.$addressObj->getCity().'<br>';
            $address .= $addressObj->getCountry().'<br>';
            $address .= $addressObj->getPhone();
                /*
                    - Formate les informations de l'adresse sélectionnée pour les stocker dans une chaîne.
                */

            //* 4.2 Création d'une instance de commande 
            $order = new Order(); 
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
                /*
                    - 1 = En attente de paiement
                    - 2 = Paiement validé
                    - 3 = Expedié
                    - 4 = Livré
                */
            $order->setCarrierName($form->get('carriers')->getData()->getName());
                /*
                    - Utiliser le dump pour debuger not variables et methode pour retrouver comment acceder à notre information !
                        - dd($form->getData());
                        - dd($form->get('addresses')->getData());
                */
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            //* Voir creation de la chaine adresse
            $order->setDelivery($address);
                /*
                    - Création de la commande :
                        - Associe la commande à l'utilisateur connecté.
                        - Définit les informations principales :
                            - Date de création.
                            - Statut de la commande.
                            - Transporteur et prix.
                            - Adresse de livraison.
                */

            //* 4.3 Ajout des détails de la commande 
            // dd($address);
            // dd($cart); (pour verifier si notre methode $cart->getCart(); est bien passé dans la variable et que les informations dedans sont celle voulues)
            foreach ($products as $product) {
                // dd($product);
                // dd($product['objet']->getName());
                $orderDetail = new OrderDetails();
                $orderDetail->setProductName($product['objet']->getName());
                $orderDetail->setProductIllustration($product['objet']->getIllustration());
                $orderDetail->setProductQuantity($product['qty']);
                $orderDetail->setProductPrice($product['objet']->getPrice());
                $orderDetail->setProductTva($product['objet']->getTva());
                $order->addOrderDetail($orderDetail);
            }
                /*
                    - Détails de commande :
                        - Parcourt les produits du panier.
                        - Crée une entité OrderDetails pour chaque produit.
                        - Associe les détails à la commande principale.
                */

            //* 5. Insertion en base de données :
            $entityManagerInterface->persist($order);
            $entityManagerInterface->flush();
                /*
                    - Persistance des entités :
                        - Enregistre la commande et ses détails en base.
                */
        }

        //* 6. Rendu de la vue :
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'order' => $order,
            'totalWt' => $cart->getTotalWt()
        ]);
            /*
                - return $this->render('order/summary.html.twig', [...])
                    - return : Mot-clé PHP utilisé pour renvoyer une valeur (ici, une réponse HTTP générée par Symfony) à la fin d'une méthode.
                    - $this->render() : Méthode héritée de la classe AbstractController, utilisée pour :
                    - 'order/summary.html.twig' :Chemin vers la template Twig que Symfony doit afficher. Ce fichier est situé dans le dossier templates/order/summary.html.twig.            
                
                //* Les variables passées à Twig sous forme de tableau associatif :
                    - Chaque clé du tableau correspond à une variable disponible dans le fichier Twig :
                        - 'choices' => $form->getData()
                            - 'choices' : Variable Twig contenant les choix d'adresse et de transporteur sélectionnés par l'utilisateur.
                            - $form->getData() : Méthode de l'objet Form qui retourne les données soumises par le formulaire.
                            Ces données incluent l'adresse choisie et le transporteur sélectionné.
                        - 'cart' => $products
                            - 'cart' : Variable Twig contenant les produits du panier.
                            - $products : Variable locale qui contient le contenu du panier.
                                - Provient de l’appel à la méthode $cart->getCart().
                            Le panier est un tableau associatif composé des produits ajoutés et de leurs quantités.
                        - 'order' => $order
                            - 'order' : Variable Twig contenant les informations de la commande.
                            - $order : Instance de l'entité Order, créée lors de la soumission du formulaire.
                                Contient toutes les informations sur la commande, telles que :
                                    -L'utilisateur, adresse de livraison, le transporteur, le montant total.
                        - 'totalWt' => $cart->getTotalWt()
                            - 'totalWt' : Variable Twig contenant le total TTC du panier.
                            - $cart->getTotalWt() : Méthode de l'objet Cart qui calcule le montant total du panier TTC (toutes taxes comprises).
            */
    }
}
/*
! Résumé
    * Étape 1 (index) :
    - Vérifie que l'utilisateur est connecté et a des adresses.
    - Affiche un formulaire pour sélectionner l'adresse de livraison et le transporteur.

    * Étape 2 (add) :
    - Récupère les informations du panier et du formulaire.
    - Crée une commande et ses détails associés.
    - Insère les données en base.
    - Prépare la vue pour le récapitulatif de la commande.
        - Récupère les données du formulaire de commande (adresse et transporteur).
        - Récupère le contenu du panier et le montant total TTC.
        - Passe ces informations à la vue Twig order/summary.html.twig.
        - La vue Twig utilise ces variables pour afficher un récapitulatif de la commande.

    * Fonctionnalités clés :
    - Validation stricte (utilisateur connecté, méthode HTTP correcte).
    - Utilisation de services pour le panier et la gestion de la base.
    - Génération dynamique des données de commande et de ses détails.
*/