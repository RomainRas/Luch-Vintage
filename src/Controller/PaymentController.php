<?php
/*
?- Ce contrôleur permet :
    ? - D'initier une session de paiement Stripe lors de la commande.
    ? - De traiter la réponse de Stripe après paiement réussi.
    ? - D'afficher un message de succès après validation du paiement.
*/
/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Controller;
    /*
        Namespace :
            - Indique que ce contrôleur appartient à l’espace de noms App\Controller.
    */
use App\Classe\Cart;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
    /*
        - Cart : Service personnalisé pour gérer le panier.
        - OrderRepository : Repository pour accéder aux commandes en base de données.
        - EntityManagerInterface : Service Doctrine pour interagir avec la base de données.
        - Session (Stripe) : Permet de créer une session de paiement Stripe.
        - Stripe : Classe principale pour interagir avec l'API Stripe.
        - AbstractController : Contrôleur de base Symfony fournissant des méthodes pratiques.
        - Response : Objet de réponse HTTP.
        - Route	: Attribut permettant de définir les routes.
    */
/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class PaymentController extends AbstractController
    /*
        - class : Déclare une classe en PHP. Ici, la classe s'appelle PaymentController.
        - extends AbstractController : La classe hérite de AbstractController, une classe fournie par Symfony. Cela permet d’utiliser des méthodes utiles comme redirectToRoute() ou getUser().
    */
{
/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Route de la gestion de la page de paiement Stripe
    #[Route('commande/paiement/{id_order}', name: 'app_payment')]
        /*
            - #[Route(...)] : Attribut PHP qui définit une route.
            - 'commande/paiement/{id_order}' : URL de la route. Le paramètre dynamique {id_order} représente l'identifiant de la commande à payer.
            - name: 'app_payment' : Nom unique de la route, qui permet de la référencer facilement dans le code.
        */

    //! ** Méthode de la gestion de la page de paiement Stripe ** !//
    public function index($id_order, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface ): Response
        /*
            - public : La méthode est accessible depuis l'extérieur de la classe.
            - function index : Nom de la méthode principale qui sera exécutée lorsque l'URL /commande/paiement/{id_order} sera visitée.
            - $id_order : Paramètre dynamique capturé dans l'URL. Il représente l'identifiant de la commande.
            - OrderRepository $orderRepository : Permet de rechercher des commandes dans la base de données.
            - EntityManagerInterface $entityManagerInterface : Permet de manipuler la base de données.
        */
    {   
        //* -> 1- Initialisation de Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            /*
                - Stripe : Classe fournie par l'API Stripe.
                - ::setApiKey() : Méthode statique de Stripe utilisée pour configurer la clé API.
                - $_ENV : Superglobale PHP qui contient les variables d'environnement.
                - 'STRIPE_SECRET_KEY' : Nom de la variable d’environnement contenant la clé secrète Stripe.
            */

        //* -> 2- Recuperation de la commande
        $order = $orderRepository->findOneBy([
            'id' => $id_order,
            'user' => $this->getUser()
        ]);
            /*
                - $orderRepository : Instance de la classe OrderRepository.
                - findOneBy() : Méthode de repository qui recherche une entité en fonction de critères.
                - ['id' => $id_order, 'user' => $this->getUser()] : Critères de recherche. La méthode recherche une commande avec l’identifiant $id_order appartenant à l’utilisateur connecté.
                - $this->getUser() : Méthode de AbstractController qui retourne l’utilisateur actuellement connecté.
            */

        // dd($order);

        //* -> 3- Verification de la commande
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
            /*
                - Si la commande n'existe pas ou n'appartient pas à l'utilisateur, l'utilisateur est redirigé vers la page d'accueil.
            */
        
        //* -> 4- Creationp, des produits Stripe
        $product_for_stripe = [];
            /*
                - $product_for_stripe : Tableau contenant les produits à envoyer à Stripe.
            */

        foreach ($order->getOrderDetails() as $product) {
            /*
                - foreach est une structure de boucle qui permet de parcourir une collection d'éléments.
                - $order->getOrderDetails() retourne tous les détails de la commande (les produits).
                - Chaque détail est assigné à la variable $product à chaque itération.
            */
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => number_format($product->getProductPriceWt() * 100,0,'',''),
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'].'/uploads/'.$product->getProductIllustration()
                        ]
                    ]
                ],
                'quantity' => $product->getProductQuantity(),
            ];
                /*
                    - $product_for_stripe[] : On ajoute un nouvel élément au tableau $product_for_stripe.
                        - 'price_data' : Contient les informations sur le prix du produit.
                            - 'currency' => 'eur' : La devise utilisée est l’euro.
                            - 'unit_amount' : Prix unitaire en centimes (Stripe attend des valeurs en centimes).
                                - number_format() : Convertit le prix en un nombre sans décimales.
                                - $product->getProductPriceWt() * 100 : Multiplie le prix TTC du produit par 100 pour obtenir le montant en centimes.
                    - 'product_data' : Contient les informations sur le produit.
                        - 'name' => $product->getProductName() : Nom du produit.
                        - 'images' => [$_ENV['DOMAIN'].'/uploads/'.$product->getProductIllustration()] : Lien vers l’image du produit, basé sur le domaine défini dans les variables d’environnement.
                */
            // dd(vars: $product);
        }
        // dd($product_for_stripe);
        // dd(vars: $order);
        // dd($id_order);

        //* -> 5- Ajout du transporteur dans le tableau Stripe ( frais de transport):
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => number_format($order->getCarrierPrice() * 100,0,'',''),
                'product_data' => [
                    'name' => 'Transporteur : '.$order->getCarrierName(),
                ]
            ],
            'quantity' => 1,
        ];
            /*
                - $product_for_stripe[] : On ajoute un nouvel élément dans le tableau $product_for_stripe, qui représente les frais du transporteur.
                    - 'price_data' : Contient les informations sur le prix du transporteur.
                        - 'currency' => 'eur' : La devise utilisée pour le paiement est l'euro.
                        - 'unit_amount' => number_format(...) : Le montant est formaté pour Stripe, en centimes.
                            - $order->getCarrierPrice() récupère le prix du transporteur. On le multiplie par 100 pour convertir les euros en centimes, car Stripe attend un montant sans décimales.
                        - 'product_data' : Contient des informations sur le produit.
                            - 'name' => 'Transporteur : ' . $order->getCarrierName() : Le nom du transporteur est affiché dans la description de l’article à payer.
                - 'quantity' => 1 : La quantité est fixée à 1, car on ne paie qu'une seule fois les frais de transport.
            */
        
        //* -> 6- Creation de la session de paiement Stripe
        $checkout_session = Session::create([
            /*
                - $checkout_session : Variable qui stocke la session de paiement créée avec Stripe.
                - Session::create() : Méthode de la classe Session qui crée une nouvelle session de paiement Stripe.
            */
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                $product_for_stripe
            ]],

            'mode' => 'payment',
            'success_url' => $_ENV['DOMAIN'] . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/mon-panier/annulation',
        ]);
            /*
                - 'customer_email' => $this->getUser()->getEmail() : L'adresse e-mail du client est récupérée via getUser() pour que Stripe puisse envoyer un reçu de paiement.
                - 'line_items' => $product_for_stripe : Le tableau $product_for_stripe contient les produits que l'utilisateur doit payer (détails de la commande + transporteur).
                - 'mode' => 'payment' : Définit le mode de la session Stripe. Ici, on utilise 'payment', qui correspond à un paiement unique.
                - 'success_url' => $_ENV['DOMAIN'] . '/commande/merci/{CHECKOUT_SESSION_ID}' : URL de redirection après un paiement réussi.
                    - $_ENV['DOMAIN'] : Domaine de l'application (défini dans le fichier .env).
                    - /commande/merci/{CHECKOUT_SESSION_ID} : L'utilisateur sera redirigé vers cette URL avec l'identifiant de la session Stripe en paramètre.
                - 'cancel_url' => $_ENV['DOMAIN'] . '/mon-panier/annulation'
                    - URL de redirection si le paiement est annulé. L'utilisateur sera redirigé vers la page du panier.
            */

        //* -> 7- Enregistrement de la session Stripe dans la commande
        $order->setStripeSessionId($checkout_session->id);
        $entityManagerInterface->flush();
            /*
                - $order->setStripeSessionId() : Cette méthode met à jour la commande en enregistrant l'identifiant de la session Stripe créée.
                    - $checkout_session->id : Identifiant unique de la session Stripe.
                - $entityManagerInterface->flush() : La méthode flush() enregistre toutes les modifications dans la base de données.
            */

        //* -> 8- Redirection vers Stripe pour le paiement
        return $this->redirect($checkout_session->url);
            /*
                - $this->redirect() : Méthode de AbstractController qui redirige l'utilisateur vers une URL.
                - $checkout_session->url : URL générée par Stripe, où l'utilisateur sera redirigé pour effectuer le paiement.
            */
    }

    //! ** Méthode pour la confirmation de paiement ** !//
    #[Route('/commande/merci/{stripe_session_id}', name: 'app_payment_success')]
    public function success($stripe_session_id, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface, Cart $cart): Response 
    {
        /*
            - #[Route(...)] : Déclare une route /commande/merci/{stripe_session_id}, où {stripe_session_id} est un paramètre dynamique.
            - $stripe_session_id : Représente l'identifiant de la session Stripe récupéré dans l'URL.
            - OrderRepository $orderRepository : Utilisé pour rechercher la commande associée à la session Stripe.
            - EntityManagerInterface $entityManagerInterface : Utilisé pour mettre à jour la base de données.
            - Cart $cart : Service qui permet de manipuler le panier d'achat.
        */

        //* -> Recherche de la commande dans la base de données 
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);
            /*
                - findOneBy() : Recherche une commande avec l'identifiant de session Stripe et l'utilisateur connecté.
            */
        
        if(!$order) {
            return $this->redirectToRoute('app_home');
        }
            /*
                - if (!$order) : Si aucune commande n'est trouvée, on redirige l'utilisateur vers la page d'accueil.
            */

        //* -> Mise à jour de l'etat de la commande
        if ($order->getState() == 1) {
            $order->setState(2);
            $cart->remove();
            $entityManagerInterface->flush();
        }
            /*
                - if ($order->getState() == 1) : Vérifie que l'état de la commande est 1 (en attente de paiement).
                - $order->setState(2) : Met à jour l'état de la commande à 2 (paiement validé).
                - $cart->remove() : Vide le panier après validation du paiement.
                - $entityManagerInterface->flush() : Enregistre les modifications dans la base de données.
            */
        
        //* -> Affichage de la page de succes
        return $this->render('payment/success.html.twig', [
            'order' => $order,
        ]);
            /*
                - $this->render() : Méthode qui retourne une vue Twig.
                - 'payment/success.html.twig' : Chemin du fichier Twig utilisé pour afficher la page de succès.
                - 'order' => $order : Passe la commande à la vue Twig pour l'afficher.
            */
        
    }

}
/*
!Resumé
* Ce contrôleur gère le paiement via Stripe et le traitement de la commande après paiement réussi. Il utilise plusieurs concepts Symfony comme :

    - Routes dynamiques avec des paramètres.
    - Injection de dépendances pour les services.
    - Manipulation des entités Doctrine.
    - Utilisation du SDK Stripe pour créer une session de paiement.
*/

