<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;
    // Importe la classe RequestStack de Symfony, qui permet d'accéder à la pile de requêtes et de gérer la session.

// Classe Cart pour gérer le panier
class Cart
{
    //? https://symfony.com/doc/7.0/session.html#basic-usage

    //! *********** Le constructeur injecte RequestStack pour gérer la session ************* */
    public function __construct(private RequestStack $requestStack) 
        /*
        function : Mot-clé qui définit une fonction ou une méthode.
        __construct : Nom réservé pour le constructeur de la classe, qui est automatiquement appelé lors de l’instanciation de la classe Cart.
        RequestStack : Type de la variable $requestStack, spécifiant qu’elle doit être une instance de RequestStack.
        $requestStack : Propriété privée de la classe qui contient une instance de RequestStack et permet d’accéder aux données de session.
        */
    {
    }

    //! *********** Méthode pour ajouter un produit au panier ************* */
    public function add($product)
    {
        //* -> Récupère le panier actuel depuis la session. Si "cart" n'existe pas encore, $cart sera NULL
        $cart = $this->requestStack->getSession()->get('cart');

        //* -> Vérifie si le produit est déjà dans le panier
        if(isset($cart[$product->getId()])) {
            // Si oui, augmente la quantité de ce produit de 1

            $cart[$product->getId()] = [
                'objet' => $product, // Stocke l'objet produit
                'qty' => $cart[$product->getID()]['qty'] + 1 
                    // Incrémente la quantité de 1
            ];
            // Si le produit n'est pas encore dans le panier, l'ajoute avec une quantité de 1        
            } else { 
            $cart[$product->getId()] = [
                'objet' => $product,
                'qty' => 1
                    // Si le produit n’est pas dans le panier, on l’ajoute avec une quantité initiale de 1.
            ];     
        }

        //* -> Enregiste le panier mis à jour dans la session
        $this->requestStack->getSession()->set('cart', $cart);
            /*
            $this->requestStack->getSession()->set(...) : Met à jour le contenu de la session avec la nouvelle valeur de $cart.
            'cart' : Nom de la clé dans la session où le panier est stocké.
            $cart : Contenu actuel du panier mis à jour avec le nouveau produit ou la nouvelle quantité.
            */

        // dd($this->requestStack->getSession()->get('cart'));
    }
    
    //! *********** Méthode pour diminuer la quantité d'un produit dans le panier ************* */
    //* -> Définit une méthode publique decrease pour diminuer la quantité d’un produit dans le panier.
    public function decrease($id)
    {
        //* -> Récupère le panier actuel depuis la session
        $cart = $this->requestStack->getSession()->get('cart');

        // Si la quantité du produit est supérieure à 1, la diminue de 1
        if ($cart[$id]['qty'] > 1) {
                /*
                if ($cart[$id]['qty'] > 1) : Vérifie si la quantité actuelle du produit est supérieure à 1.
                */
            $cart[$id]['qty'] = $cart[$id]['qty'] -1;
                /*
                $cart[$id]['qty'] - 1 : Si oui, décrémente la quantité de 1.
                */
        } else {
            // Si la quantité est 1 ou moins, retire le produit du panier
            unset($cart[$id]);
                /*
                unset($cart[$id]); : Si la quantité est de 1 ou moins, supprime le produit du panier.
                */
        }

        //* -> Met à jour le panier dans la session avec le panier modifié
        $this->requestStack->getSession()->set('cart', $cart);
    }

    //! *********** Méthode pour calculer la quantité totale de produits dans le panier ************* */
    public function fullQuantity()
    {
        //* -> Récupère le contenu du panier dans la session (tableau vide si le panier n'existe pas)
        $cart = $this->requestStack->getSession()->get('cart', []); // Valeur par défaut : tableau vide
        //* -> Initialise une variable $quantity pour stocker la quantité totale.
        $quantity = 0;
        
        //* -> Si le panier n'existe pas, retourne 0
        if (!isset($cart)) {
                return $quantity;
        }

        //* -> Ajoute la quantité de chaque produit au total
        foreach ($cart as $product) {
            $quantity = $product['qty']; // Ajoute la quantité de chaque produit au total
        }
        //* -> Retourne la quantité totale
        return $quantity;
    }

    //! *********** Méthode pour calculer le prix total des produits dans le panier ************* */
    public function getTotalWt()
    {
        //* -> Récupère le panier depuis la session (ou un tableau vide par défaut)
        $cart = $this->requestStack->getSession()->get('cart', []); // Valeur par défaut : tableau vide
        //* -> $price = 0 : Initialise $price à 0 pour calculer le prix total.
        $price = 0;

        // Si le panier est vide, retourne 0
        if (!isset($cart)) {
            return $price;
        }
        // Calcule le prix total en multipliant le prix de chaque produit par sa quantité
        foreach ($cart as $product) {
            $price = $price + ($product['objet']->getPriceWt() * $product['qty']);
        }
            /*
            foreach : Boucle sur chaque produit dans $cart.
            $price += ... : Calcule le prix de chaque produit multiplié par sa quantité, et l’ajoute à $price.
            */
        
        //* -> Retourne le prix total
        return $price; // Retourne le prix total
    }

    //! *********** Méthode pour vider complètement le panier ************* */
    public function remove()
        // public function remove() : Supprime tout le panier de la session en appelant remove('cart').
    {
        //* -> Supprime le panier de la session
        return $this->requestStack->getSession()->remove('cart');
    }

    //! *********** Méthode pour obtenir le contenu actuel du panier ************* */
    public function getCart()
        // public function getCart() : Récupère et retourne le contenu du panier actuel depuis la session.
    {
        //* -> Retourne le contenu du panier stocké dans la session
        return $this->requestStack->getSession()->get('cart');
    }
}

    /* 
    !Explications supplémentaires :
        * RequestStack : permet de gérer les sessions en récupérant l'objet Request contenant les données de session.
        * add($product) : ajoute un produit au panier. Si le produit est déjà présent, il augmente la quantité ; sinon, il l'ajoute avec une quantité initiale de 1.
        * decrease($id) : diminue la quantité d'un produit. Si la quantité est de 1, il retire le produit du panier.
        * fullQuantity() : calcule la quantité totale d'articles dans le panier.
        * getTotalWt() : calcule le prix total des articles en se basant sur le prix avec taxes (getPriceWt()).
        * remove() : supprime tous les articles du panier.
        * getCart() : retourne le contenu du panier pour affichage ou autres manipulations.
    */