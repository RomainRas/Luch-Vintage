<?php
/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Twig;
    // namespace : Définit l’espace de noms App\Twig, indiquant que cette classe appartient aux extensions Twig de l’application.
use App\Classe\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
    /*
        - use : Importe les classes externes nécessaires.
            - Cart : La classe Cart qui gère les opérations de panier.
            - CategoryRepository : Le dépôt CategoryRepository, utilisé pour récupérer les catégories.
            - AbstractExtension : Classe de base pour toutes les extensions Twig.
            - GlobalsInterface : Interface permettant de définir des variables globales accessibles dans tous les templates.
            - TwigFilter : Utilisé pour créer des filtres Twig personnalisés.
    */

/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> AppExtensions : Classe qui étend `AbstractExtension` et implémente `GlobalsInterface` pour ajouter des filtres et des variables globales aux templates Twig.
class AppExtensions extends AbstractExtension implements GlobalsInterface
    /*
        - class : Déclare une classe.
            - AppExtensions : Nom de la classe, représentant une extension Twig pour ajouter des fonctionnalités personnalisées.
            - extends AbstractExtension : Hérite de AbstractExtension, permettant de créer des filtres et des fonctions Twig.
            - implements GlobalsInterface : Implémente GlobalsInterface, permettant de définir des variables globales accessibles dans les templates Twig.
    */

{
/*
************************************************************
!                   PROPRIETE ET ATTRIBUTS                 *
************************************************************
*/
    //* -> Propriétés privées `$categoryRepository` et `$cart` pour stocker les dépendances `CategoryRepository` et `Cart`.
    private $categoryRepository;
    private $cart;
        /*
            - $categoryRepository : Stocke le dépôt de catégories pour récupérer les catégories dans Twig.
            - $cart : Stocke une instance de la classe Cart pour interagir avec le panier.
        */


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //! ** __construct ** !//
    //* -> Constructeur de la classe qui injecte les dépendances `CategoryRepository` et `Cart`.
    public function __construct(CategoryRepository $categoryRepository = null, Cart $cart) 
    {
        //* -> Affecte les dépendances aux propriétés pour pouvoir les utiliser dans les méthodes de la classe.
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }

    //! ** getFilters ** !//
    //* -> getFilters est une méthode obligatoire dans une extension Twig pour ajouter des filtres personnalisés.
    public function getFilters()
    {
        return [
            //* -> Crée un filtre Twig nommé `price` qui utilise la méthode `formatPrice` pour formater les prix.
            new TwigFilter('price', [$this, 'formatPrice'])
        ];
    }

    //! ** formatPrice ** !//
    //* -> formatPrice : Méthode pour formater un nombre en ajoutant une virgule comme séparateur décimal et un symbole `€`.
    public function formatPrice($number)
    {
        //* -> number_format : Formate le nombre avec 2 décimales, une virgule comme séparateur, et ajoute le symbole euro.
        return number_format($number,'2',','). ' €';
    }

    //! ** getGlobals ** !//
    //* -> getGlobals est une méthode de `GlobalsInterface` pour ajouter des variables globales accessibles dans tous les templates Twig.
    public function getGlobals(): array
    {
        return [
            //* -> allCategories : Appelle `findAll` sur `CategoryRepository` pour obtenir toutes les catégories et les rendre disponibles dans Twig.
            'allCategories' => $this->categoryRepository->findAll(),
            //* -> fullCartQuantity : Utilise la méthode `fullQuantity` de la classe `Cart` pour obtenir la quantité totale des articles dans le panier.
            'fullCartQuantity' => $this->cart->fullQuantity()
        ];
    }
}
/*
!Explications supplémentaires
    * Filtres personnalisés :
        - getFilters : Méthode qui retourne une liste de filtres personnalisés.
        - formatPrice : Méthode qui formate les prix avec deux décimales et le symbole €. Cela permet d'utiliser {{ price | price }} dans Twig pour afficher les prix formatés.

    * Variables globales :
        - getGlobals : Méthode qui retourne un tableau associatif de variables globales pour Twig.
        - allCategories : Récupère toutes les catégories via CategoryRepository, les rendant disponibles dans Twig sous allCategories.
        - fullCartQuantity : Récupère la quantité totale d'articles dans le panier, accessible sous fullCartQuantity.

    * Résumé
        AppExtensions est une extension Twig qui fournit un filtre personnalisé pour formater les prix et des variables globales pour afficher toutes les catégories et la quantité totale des articles dans le panier. Elle améliore la gestion des templates en rendant ces données disponibles et facilement utilisables dans toute l'application Twig.
*/