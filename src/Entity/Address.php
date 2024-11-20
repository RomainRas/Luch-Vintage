<?php
/*
************************************************************
!           ESPACE ET IMPORTATION DES CLASSES              *
************************************************************
*/
namespace App\Entity;
    /*
        - namespace : Definit l'espace de la classe, ici App\Entity indique que cette classe appartient aux entitité de l'application
    */

use App\Repository\AddressRepository;
    /*
        - use : Importe les classes neccesaire pour le fonctionnement de l'entité Address
        - AddressRepository : Depot personnalisé pour l'entité Address, utilisé pour les requetes spécifique
    */

use Doctrine\ORM\Mapping as ORM;
    /*
        - ORM : Contient les annotation Doctrine pour mapper la classe sur une table de la base de donnée
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
    /*
        - #[ORM\Entity] : Indique que cette class est une entité Doctrine donc mappé sur une table de la BDD
        - repositoryClass: Specifie que cette entité utilise le depot personnalisé AddressRepository
    */
{


/*
************************************************************
!                      PROPRIETES                          *
************************************************************
*/
    //! ** id ** !//
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
        /*
            - #[ORM\Id] : Indique que cette propriété est la clé primaire de la table
            - #[ORM\GeneratedValue] : Indique que la valeur de cette propriété est générée automatiquement (auto-increment)
            - #[ORM\Column] : Définit cette propriété comme une colonne dans la table
            - private : Propriété privé ( accessible uniquement depuis la class)
            - ?int : La propriété $id peux etre un entier (int) ou null (?)
        */

    //! ** firstname ** !//
    //* Stock le Prenom associé à l'adresse
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    //! ** lastname ** !//
    //* Stock le Nom associé à l'adresse
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    //! ** address ** !//
    //* Représente l'adresse complète (par exemple, "123 Rue de Paris").
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    //! ** postal ** !//
    //* Stocke le code postal (par exemple, "75000").
    #[ORM\Column(length: 255)]
    private ?string $postal = null;

    //! ** city ** !//
    //* Représente la ville associée à l'adresse (par exemple, "Paris")
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    //! ** country ** !//
    //* Représente le pays associé à l'adresse (par exemple, "France").
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    //! ** phone ** !//
    //* Représente le numéro de téléphone associé à l'adresse (par exemple, "0123456789").
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    //! ** user (relation avec l’entité User) ** !//
    //* Représente la relation avec l'entité User
    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
        /*
            - #[ORM\ManyToOne] : Definit une relation ManyToOne (Une adresse est associé à un User et un User peux avoir plusieurs adresses
            - (inversedBy: 'addresses') : Indique que cette relation est inversé par la propriété $addresses dans l'entité User
            - #[ORM\JoinColumn(nullable: false)] : cette relation est obligatoire, donc la colonne user_id ne peut pas etre null
        */


/*
************************************************************
!                        METHODES                          *
************************************************************
*/
    //! ** id ** !//
    //* Retourne l’ID unique de l’adresse.
    public function getId(): ?int
    {
        return $this->id;
    }
        /*
            - public : Methode accessible depuis l'exterieur de la classe
            - function getId() : nom de la methode utilisé pour acceder à la valeur de l'id
            - : ?int : Retourne un entier ou null
            - return $this->id; : Retourne la valeur de la propriété $id
        */

    //! ** firstname ** !//
    //* Recupere le prenom
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
        /*
            - return $this->firstname : retourne la valeur actuelle de firstname
        */

    //* Definit/Modifie la valeur de la propriété firstname (prenom)
    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }
        /*
            - string $firstname : La methode attend une chaine de caractere 
            - static : Retourne l'instance actuelle de l'objet pour permettre un chainage des appels
            - $this->firstname = $firstname : affecte la valeur passé en parametre à la propriété $firstname
            - return $this : retourne à l'instance actuelle de l'objet ($this)
        */

    //! ** lastname ** !//
    //* Recupere le nom
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    //* Definit la valeur de la propriété lastname (nom)
    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    //! ** addresss ** !//
    //* Recupere l'addresse
    public function getAddress(): ?string
    {
        return $this->address;
    }

    //* Definit la valeur de la propriété adresse (addresse)
    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    //! ** postal ** !//
    //* Recupere le code postale
    public function getPostal(): ?string
    {
        return $this->postal;
    }

    //* Definit la valeur de la propriété postal (code postale)
    public function setPostal(string $postal): static
    {
        $this->postal = $postal;

        return $this;
    }

    //! ** city ** !//
    //* Recupere le nom de la ville
    public function getCity(): ?string
    {
        return $this->city;
    }

    //* Definit la valeur de la propriété city (ville)
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    //! ** country ** !//
    //* Recupere le nom du pays
    public function getCountry(): ?string
    {
        return $this->country;
    }

    //* Definit la valeur de la propriété country (pays)
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    //! ** phone ** !//
    //* Recupere le telephone
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    //* Definit la valeur de la propriété phone (telephone)
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    //! ** user (relation avec User) ** !//
    //* Retourne l'utilisateur associé à cette adresse
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    //* Modifie l'utilisateur associé à cette addresse
    {
        $this->user = $user;

        return $this;
    }
        /*
            - ?User $user : attend un objet User ou null
        */
}

/* 
! Resumé
    * Entité Doctrine :
        - Address est une entité mappé à une table dans la BDD
    
    * Relations :
        - Une adresse est lié à un utilisateur (ManyToOne)
        - Chaque User peux avoir plusieurs adresses (OneToMany dans User)
    
    * Propriétés et colonnes
        - Toutes les propriétés sont definies avec des annotations Doctrine pour les mapper à des colonnes specifiques
        - Les champs sont typés (string, int, etc.) pour les variations de pays (phone, country, postale)

    * Getters et Setters
        - Getters et Setters simple pour les propriétés
*/