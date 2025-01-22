<?php
//? Order : représente une commande dans le système. Elle est mappée à une table de base de données nommée order et contient des informations telles que la date de création, l'état de la commande, les détails du transporteur et les informations de livraison.


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
        /*
            - 1 = En attente de paiement
            - 2 = Payée
            - 3 = En cours de préparation
            - 4 = Expedié
            - 5 = Annulée
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
    #[ORM\OneToMany(targetEntity: OrderDetails::class, mappedBy: 'myOrder', cascade:['persist'])]
    private Collection $orderDetails;
        /*
            - #[ORM\OneToMany(...)] : Indique une relation de type OneToMany. Cela signifie qu'une commande (Order) peut être liée à plusieurs détails de commande (OrderDetails).
            - targetEntity: OrderDetails::class : Spécifie l'entité liée à cette relation, ici OrderDetails.
            - mappedBy: 'myOrder' : Définit le côté propriétaire de la relation. La propriété myOrder dans l'entité OrderDetails est utilisée pour établir la relation bidirectionnelle.
            - cascade: ['persist'] : Active la propagation des opérations sur les entités associées. Ici, lorsque vous appelez persist() sur une commande (Order), les objets associés OrderDetails seront également persistés automatiquement.

            - private Collection $orderDetails : Utilise une collection (généralement ArrayCollection de Doctrine) pour stocker les multiples objets OrderDetails liés à une commande.
        */

    //! ** relation avec User ** !//
    #[ORM\ManyToOne(inversedBy: 'orders')]
        /*
            - #[ORM\ManyToOne(...)] : Indique une relation de type ManyToOne. Cela signifie que plusieurs commandes (Order) peuvent être associées à un seul utilisateur (User).
            - inversedBy: 'orders' :
                - Définit le côté inverse de la relation dans l'entité User.
                - La propriété orders dans l'entité User stocke les commandes associées à cet utilisateur.
        */
    #[ORM\JoinColumn(nullable: false)]

        /*
            - #[ORM\JoinColumn(nullable: false)] :
                - Spécifie la colonne de jointure (clé étrangère) dans la base de données.
                - nullable: false : Rend cette relation obligatoire. Une commande doit être liée à un utilisateur.
        */
    private ?User $user = null;
        /*
            -private ?User $user = null : Une commande peut être liée à un utilisateur (relation obligatoire).
        */

    #[ORM\Column(type: Types::TEXT, nullable: true)]
        /*
            #[ORM\Column(...)] : Cette ligne utilise un attribut PHP pour indiquer que la propriété $stripe_session_id sera mappée à une colonne dans la base de données.
                - type: Types::TEXT Définit le type de la colonne dans la base de données.
                    - Types::TEXT signifie que cette colonne stockera une grande quantité de texte (idéal pour des identifiants de session comme ceux de Stripe).
                    - nullable: true :Indique que cette colonne peut être null (vide) dans la base de données.
        */
    private ?string $stripe_session_id = null;
        /*
            - private : Définit la visibilité de la propriété.
                - private : signifie que la propriété est accessible uniquement depuis l’intérieur de la classe.
            - ?string : Indique que la propriété peut être de type string (chaîne de caractères) ou null.
            - $stripe_session_id :Nom de la propriété.
                - Cette propriété stockera l'identifiant de session Stripe lié à une commande.
                - = null : La valeur initiale de la propriété est définie à null, car elle n'est pas encore définie au moment de la création de l'objet.
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

    //! ** getTotalWt ** !//
    //* Methode pour avoir le prix total avec taxe

    public function getTotalWt()
    {
        $totalWt = 0;
        $products = $this->getOrderDetails();

        foreach ($products as $product) {
            // dd($product);
            $coeff = 1 + ($product->getProductTva() / 100);
            // dd($product->getProductPrice() * $coeff);
            $totalWt += ($product->getProductPrice() * $coeff) * $product->getProductQuantity();
        }
        return $totalWt + $this->getCarrierPrice();
    }

    //! ** getTotalTva ** !//
    //* Methode pour avoir le montant de TVA
    public function getTotalTva()
    {
        $totalTva = 0;
        $products = $this->getOrderDetails();

        foreach ($products as $product) {
            // dd($product);
            $coeff = $product->getProductTva() / 100;
            // dd($product->getProductPrice() * $coeff);
            $totalTva += $product->getProductPrice() * $coeff;
        }
        return $totalTva;
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
            // Cette méthode permet de supprimer un OrderDetails (détail de commande) de la collection orderDetails associée à l'instance actuelle de Order.
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
        /*
            - OrderDetails $orderDetail : Le détail de commande à supprimer.

            - $this->orderDetails->removeElement($orderDetail) :
                - Supprime l'objet $orderDetail de la collection orderDetails (s'il est présent).
                - Retourne true si l'objet a été supprimé, sinon false.

        - Vérification et nettoyage de la relation :
            - Si $orderDetail est bien supprimé de la collection et que la commande (myOrder) associée à ce détail est la commande actuelle ($this), alors la relation côté propriétaire (myOrder) dans l'entité OrderDetails est mise à null en appelant :
            - $orderDetail->setMyOrder(null);
            - Cela garantit que la relation est totalement supprimée des deux côtés (relation bidirectionnelle).
        */

    //! ** getUser ** !//
    //* Récupère l'utilisateur (User) associé à l'instance actuelle de Order.
    public function getUser(): ?User
    {
        return $this->user;
    }
        /*
            - Retour :
                - Retourne une instance de User si elle est définie, sinon retourne null.
            - Type de retour :
                - ?User : Le point d'interrogation (?) indique que le retour peut être soit une instance de User, soit null.
        */

    //! ** setUser ** !// 
    //* Définit l'utilisateur (User) associé à la commande actuelle.
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
        /*
            - Paramètre :
                - ?User $user :
                    - Une instance de l'entité User ou null.
                    - Cela permet de lier une commande à un utilisateur spécifique ou de supprimer cette liaison en passant null.
            - Fonctionnement :
                - L'attribut $this->user est mis à jour avec la valeur de $user.
            - Retour :
                - : static :
                - Retourne l'instance actuelle de Order, permettant une approche fluide.
        */

        public function getStripeSessionId(): ?string
        {            
            /*
                - public :
                    - Le mot-clé public signifie que la méthode est accessible depuis n'importe où, en dehors de la classe.
                    - Cela permet de lire la valeur de la propriété $stripe_session_id à partir d'autres classes, comme dans un contrôleur.
                - function :
                    - Mot-clé utilisé pour déclarer une fonction ou méthode.
                - getStripeSessionId :
                    - Nom de la méthode.
                    - Par convention, les méthodes qui récupèrent une valeur commencent par get.
                - (): ?string :
                    - : indique le type de valeur que la méthode retourne.
            */
            return $this->stripe_session_id;
                /*
                    - return :
                        - Mot-clé qui renvoie une valeur à l'endroit où la méthode est appelée.
                    - $this->stripe_session_id :
                        - $this fait référence à l'instance actuelle de la classe.
                    - ->stripe_session_id accède à la propriété $stripe_session_id de cette instance.
                        - Cette ligne retourne donc la valeur de la propriété $stripe_session_id.
                */
        }


        public function setStripeSessionId(?string $stripe_session_id): static
        {
            /*
                - public :
                    - La méthode est accessible depuis n'importe où.
                - function :
                    - Déclare une méthode.
                - setStripeSessionId :
                    - Nom de la méthode.
                    - Par convention, les méthodes qui modifient une valeur commencent par set.
                - (?string $stripe_session_id) :
                    - ?string indique que le paramètre $stripe_session_id doit être soit une chaîne de caractères, soit null.
                    - $stripe_session_id est le paramètre passé à la méthode, correspondant à la valeur que tu veux attribuer à la propriété $stripe_session_id.
                - : static :
                    - La méthode retourne l'instance actuelle de la classe.
                    - Cela permet de chaîner les appels de méthodes (par exemple, setStripeSessionId()->setOtherProperty()).
            */
            $this->stripe_session_id = $stripe_session_id;
                /*
                    - $this->stripe_session_id :
                        - Accède à la propriété $stripe_session_id de l'instance actuelle.
                    = $stripe_session_id :
                        - Assigne la valeur du paramètre $stripe_session_id à la propriété $stripe_session_id.
                */
            return $this;
                /*
                    - return :
                        - La méthode retourne une valeur.
                    -$this :
                        - Retourne l'instance actuelle de la classe.
                        - Cela permet de chaîner les appels de méthodes, une pratique courante en programmation orientée objet.
                */
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

    - cascade: ['persist'] ?
        Cela évite d'avoir à appeler explicitement persist() sur chaque objet OrderDetails lors de la création ou de la modification d'une commande.
        Très utile pour simplifier le code lorsque vous ajoutez plusieurs OrderDetails à une Order.

    * Relation entre Order et OrderDetails :

        -Une commande peut contenir plusieurs détails de commande (relation OneToMany).
        La méthode removeOrderDetail() permet de gérer cette relation en supprimant un détail de commande de la collection.

    *Relation entre Order et User :

        Une commande est toujours associée à un utilisateur unique (relation ManyToOne).
        Les méthodes getUser() et setUser() permettent de lire ou de modifier cette relation.

    *Methodes avec Stripe
        - getStripeSessionId() : Récupère la valeur de la propriété $stripe_session_id.
        - setStripeSessionId() : Modifie la valeur de la propriété $stripe_session_id et retourne l'instance actuelle.
*/