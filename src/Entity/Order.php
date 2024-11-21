<?php
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/
namespace App\Entity;
    /*
        - Organise la classe Order dans l'espace de noms App\Entity, correspondant au répertoire src/Entity.
    */

use App\Repository\OrderRepository;
    /*
        - Importe la classe OrderRepository pour permettre son association avec cette entité.
    */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
    /*
        - Utilisés pour gérer les collections d'objets (comme les relations OneToMany).
    */

use Doctrine\DBAL\Types\Types;
    /*
        - Fournit les types de données utilisés dans les colonnes (par exemple, DATETIME_MUTABLE).
    */

use Doctrine\ORM\Mapping as ORM;
    /*
        - Fournit les annotations pour mapper les propriétés de cette classe à la base de données.
    */

/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
    /*
        - #[ORM\Entity(...)] : Indique que cette classe est une entité Doctrine.
        - repositoryClass : Associe cette entité au dépôt personnalisé OrderRepository.
        - #[ORM\Table(name: 'order')] : Définit que la table dans la base de données est nommée order, les backticks sont nécessaires car order est un mot réservé dans SQL.
    */
{


/*
************************************************************
!                      PROPRIETES                          *
************************************************************
*/
    //! ** id ** !//
    //* Identifiant unique de la commande.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
        /*
            - #[ORM\Id] : Définit cette propriété comme la clé primaire.
            - #[ORM\GeneratedValue] : Indique que la valeur sera générée automatiquement (autoincrement).
            - #[ORM\Column] : Crée une colonne dans la table correspondante.
            - private ?int $id = null; : Propriété privée, de type int ou null.
        */

    //! ** createdAt ** !//
    //* Stocke la date et l'heure de création de la commande.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;
        /*
            - #[ORM\Column(type: Types::DATETIME_MUTABLE)] : Propriété mappée à une colonne de type DATETIME dans la base.
            - MUTABLE signifie que la valeur peut être modifiée après sa création.
            - Type PHP : ?\DateTimeInterface.
        */
        
    //! ** state ** !//
    //* Représente l'état de la commande (ex. : 0 = en cours, 1 = validée, 2 = annulée, etc.).
    #[ORM\Column]
    private ?int $state = null;
        /*
            - #[ORM\Column] : Définit une colonne entière pour stocker l'état de la commande.
            - private ?int $state = null; : Propriété de type int ou null. Peut être utilisée pour indiquer des états tels que "en attente", "payée", "expédiée", etc.
        */

    //! ** carrierName ** !//
    //* Nom du transporteur choisi pour cette commande.
    #[ORM\Column(length: 255)]
    private ?string $carrierName = null;
        /*
            - length: 255 : Spécifie la longueur maximale de la chaîne de caractères.
            - carrierName : Stocke le nom du transporteur sélectionné pour la commande.
        */

    //! ** carrierPrice ** !//
    //* Prix du transporteur pour cette commande.
    #[ORM\Column]
    private ?float $carrierPrice = null;

    //! ** delivery ** !//
    //* Informations de livraison (adresse, contact, etc.).
    #[ORM\Column(type: Types::TEXT)]
    private ?string $delivery = null;
        /*
            - type: Types::TEXT : Permet de stocker une description ou une adresse de livraison sous forme de texte long.
        */
    
    //! ** relation avec OrderDetails ** !//
    //*  Stocke tous les détails associés à la commande.
    /**
     * @var Collection<int, OrderDetails>
     */
    #[ORM\OneToMany(targetEntity: OrderDetails::class, mappedBy: 'myOrder')]
    private Collection $orderDetails;
        /*
            - #[ORM\OneToMany(...)] : Définit une relation OneToMany avec l'entité OrderDetails.
            - mappedBy: 'myOrder' : Spécifie que cette relation est définie du côté myOrder dans l'entité OrderDetails.
            - private Collection $orderDetails; : Utilise une collection pour gérer les multiples OrderDetails liés à une commande.
        */

/*
************************************************************
!                      METHODES                            *
************************************************************
*/
    //! ** __construct() ** !//
    //* Initialise la propriété $orderDetails avec une instance de ArrayCollection.
        //* Assure que la collection est prête à recevoir des éléments dès la création d'une instance d'Order.
    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    //! ** id ! **!//
    //* Retourne l'identifiant de la commande.
    public function getId(): ?int
    {
        return $this->id;
    }

    //! ** Getteur et Setteur de createdAt ** !//
    //* Retourne la date de création de la commande.
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    //* Définit la date de création.
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    //! ** Getteur et Setteur de state ** !//
    //* Retourne l'état de la commande.
    public function getState(): ?int
    {
        return $this->state;
    }

    //* Définit l'état de la commande.
    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    //! ** Getteur et Setteur de carrierName ** !//
    //* Retourne le nom du transporteur.
    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    //* Définit le nom du transporteur.
    public function setCarrierName(string $carrierName): static
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    //! ** Getteur et Setteur de carrierPrice ** !//
    //* Retourne le prix du transporteur.
    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    //* Définit le prix du transporteur.
    public function setCarrierPrice(float $carrierPrice): static
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    //! ** Getteur et Setteur de delivery ** !//
    //* Retourne les informations de livraison.
    public function getDelivery(): ?string
    {
        return $this->delivery;
    }
    //* Définit les informations de livraison.
    public function setDelivery(string $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    //! ** Getteur, add et remove de la relation OrderDetails ** !//
    //* Retourne tous les détails associés à la commande sous forme de collection.
    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    //* addOrderDetail() :
        //* Ajoute un détail à la commande.
        //* Associe le détail à cette commande via setMyOrder().
    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setMyOrder($this);
        }

        return $this;
    }

    //* removeOrderDetail() :
        //* Supprime un détail de la commande.
        //* Dissocie le détail en mettant setMyOrder() à null.
    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getMyOrder() === $this) {
                $orderDetail->setMyOrder(null);
            }
        }

        return $this;
    }
}
/*
!Résumé
    * L'entité Order représente une commande dans le système e-commerce. Voici les points clés :

* Propriétés principales :
    - id : Identifiant unique de la commande.
    - createdAt : Date de création.
    - state : État de la commande (en attente, validée, etc.).
    - carrierName : Nom du transporteur.
    - carrierPrice : Coût du transport.
    - delivery : Adresse ou détails de livraison.

* Relations :
    - orderDetails : Une commande peut contenir plusieurs détails (produits commandés, quantités, etc.).
        - Méthodes :
            - Getters et setters pour manipuler les propriétés.
            - Méthodes pour gérer la relation avec orderDetails.

            Cette entité est prête à être utilisée avec Doctrine pour gérer les commandes dans une base de données.
*/