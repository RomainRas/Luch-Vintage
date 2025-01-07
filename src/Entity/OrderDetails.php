<?php
//? OrderDetailsphp : Détails d'une commande dans le système, tels que les informations sur les produits, leurs quantités, leur prix, et les taxes associées. Chaque instance de cette entité est liée à une commande spécifique via une relation ManyToOne.


/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Entity;
    /*
        - namespace : Place la classe OrderDetails dans l'espace de noms App\Entity, correspondant au répertoire src/Entity.
    */

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
    /*
        - OrderDetailsRepository : Associe l'entité à un dépôt personnalisé pour exécuter des requêtes spécifiques.
        - ORM : Fournit des annotations pour mapper les propriétés de cette entité à la base de données.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
    /*
        - #[ORM\Entity(...)] : Indique que cette classe est une entité Doctrine.
        - repositoryClass : Associe cette entité au dépôt personnalisé OrderDetailsRepository.
    */
class OrderDetails
{


/*
************************************************************
!                      PROPRIETES                          *
************************************************************
*/  
    //! ** id ** !//
    //* Identifiant unique pour chaque détail de commande.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
        /*
            - #[ORM\Id] : Déclare la propriété comme clé primaire.
            - #[ORM\GeneratedValue] : La valeur est générée automatiquement.
            - #[ORM\Column] : Propriété mappée à une colonne de la table.
            - Type PHP : ?int (nullable integer).
        */

    //! ** myOrder : Relation avec Order ** !//
    //* Associe ce détail à une commande spécifique.
    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $myOrder = null;
        /*
            - #[ORM\ManyToOne(...)] : Définit une relation ManyToOne avec l'entité Order, plusieurs détails de commande peuvent être liés à une seule commande.
            - inversedBy: 'orderDetails' : Nom de la propriété dans Order qui inverse cette relation, cela correspond à $order->getOrderDetails() dans l'entité Order.
            - #[ORM\JoinColumn(nullable: false)] : Spécifie que cette relation est obligatoire.
            - Type PHP : ?Order.
        */

    //! ** productName ** !//
    //* Stocke le nom du produit associé à ce détail.
    #[ORM\Column(length: 255)]
    private ?string $productName = null;
        /*
            - length: 255 : Définit une colonne de type VARCHAR avec une longueur maximale de 255 caractères.
            - Type PHP : ?string.
        */

    //! ** productIllustration ** !//
    //* Stocke le chemin ou l'URL de l'image du produit, fournit une illustration pour le produit.
    #[ORM\Column(length: 255)]
    private ?string $productIllustration = null;
        /*
            - #[ORM\Column(length: 255)] : Colonne pour stocker l'illustration (URL ou chemin) du produit.
            - Type PHP : ?string.
        */

    //! ** productQuantity ** !//
    //* Stocke la quantité commandée de ce produit.
    #[ORM\Column]
    private ?int $productQuantity = null;
        /*
            - #[ORM\Column] : Colonne pour stocker un entier.
            - Type PHP : ?int.
        */

    //! ** productPrice ** !//
    //* Stocke le prix unitaire du produit (hors TVA).
    #[ORM\Column]
    private ?float $productPrice = null;
        /*
            - #[ORM\Column] : Colonne pour stocker un float (prix).
            - Type PHP : ?float.
        */

    //! ** productTva ** !//
    //* Stocke le taux de TVA appliqué au produit.
    #[ORM\Column]
    private ?float $productTva = null;
        /*
            - #[ORM\Column] : Colonne pour stocker un float (taux de TVA).
            - Type PHP : ?float.
        */


/*
************************************************************
!                      METHODES                            *
************************************************************
*/
    //! ** getId ** !//
    //* Retourne l'identifiant unique du détail de commande.
    public function getId(): ?int
    {
        return $this->id;
    }
    
    //! ** Getteur et Setteur de myOrder ** !//
    //* Retourne la commande à laquelle ce détail est lié.
    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    //* Associe ce détail à une commande spécifique.
    public function setMyOrder(?Order $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    //! ** Getteur et Setteur productName ** !//
    //* Retourne le nom du produit pour ce détail.
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    //* Définit le nom du produit pour ce détail.
    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    //! ** Getteur et Setteur de productIllustration ** !//
    //* Retourne l'illustration du produit.
    public function getProductIllustration(): ?string
    {
        return $this->productIllustration;
    }

    //* Définit l'illustration pour ce produit.
    public function setProductIllustration(string $productIllustration): static
    {
        $this->productIllustration = $productIllustration;

        return $this;
    }

    //! ** Getteur et Setteur de productQuantity ** !//
    //* Retourne la quantité commandée pour ce produit.
    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    //* Définit la quantité commandée pour ce produit.
    public function setProductQuantity(int $productQuantity): static
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    //! ** Getteur et Setteur de productPrice ** !//
    //* Retourne le prix unitaire du produit.
    public function getProductPriceWt()
    {
        $coeff = 1 + ($this->productTva/100);
        return $coeff * $this->productPrice;
    }

    //! ** Getteur et Setteur de productPrice ** !//
    //* Retourne le prix unitaire du produit.
    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    //* Définit le prix unitaire du produit.
    public function setProductPrice(float $productPrice): static
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    //! ** Getteur et Setteur de productTva ** !//
    //* Retourne le taux de TVA pour ce produit.
    public function getProductTva(): ?float
    {
        return $this->productTva;
    }

    //* Définit le taux de TVA pour ce produit.
    public function setProductTva(float $productTva): static
    {
        $this->productTva = $productTva;

        return $this;
    }
}
/*
!Résumé
* L'entité OrderDetails représente les détails d'une commande individuelle :

    * Propriétés principales :
        - id : Identifiant unique.
        - myOrder : Commande associée.
        - productName : Nom du produit.
        - productIllustration : Illustration du produit.
        - productQuantity : Quantité commandée.
        - productPrice : Prix unitaire.
        - productTva : Taux de TVA.

    *Relation :
        - ManyToOne avec l'entité Order.
    
    * Utilité :
        - Gère les détails d'une commande, permettant de suivre les produits, quantités, prix, et TVA pour chaque commande.

    *Cette entité s'intègre dans un système de gestion de commandes e-commerce pour détailler les produits commandés.
*/