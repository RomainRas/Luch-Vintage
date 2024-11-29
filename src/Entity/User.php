<?php
//? Ce fichier définit les propriétés essentielles d'un utilisateur, gère ses relations avec d'autres entités (comme Address et Order) et implémente des interfaces Symfony pour la gestion des utilisateurs et de leur authentification.
// ? https://symfony.com/doc/current/security.html#the-user
    //! Création de la class User qui sera l'entité User en BDD
    // ! symfony console make:user
        // ! Name of the form class : User
            // ! Creation d'une Doctrine qui va stocker l'User dans la BDD : OUI
                // ! created: src/Entity/User.php
                // ! created: src/Repository/UserRepository.php
                // ! updated: src/Entity/User.php
                // ! updated: config/packages/security.yaml
    //! + Ajout d'informations suplementaire avec : symfony console make:entity ( mise à jour ou création d'une entité existance)
        //! Class de l'entité mise à jour : User


/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Entity;
    /*
        - Le namespace est utilisé pour organiser le code et éviter les conflits de noms entre différentes classes
        - Mot-clé PHP utilisé pour organiser les classes dans des espaces de noms (namespaces) et éviter les conflits de noms.
        - Nom du namespace. Cela signifie que la classe User fait partie de l'espace de noms App\Entity.
    */
use App\Repository\UserRepository;
    /*
        - On importe la classe UserRepository qui sera utilisée pour les requêtes sur l'entité User
    */
use Doctrine\Common\Collections\ArrayCollection;
    /*
        - ArrayCollection : est une implémentation de la classe Collection fournie par Doctrine.
            - Elle représente une collection d'objets (semblable à un tableau, mais avec des fonctionnalités supplémentaires).
    */
use Doctrine\Common\Collections\Collection;
    /*
        - use : Mot-clé utilisé pour importer une classe ou un autre élément situé dans un autre namespace.
        - App\Repository\UserRepository : Chemin vers la classe UserRepository, qui est une classe responsable de la gestion des utilisateurs dans la base de données.
    */
use Doctrine\ORM\Mapping as ORM;
    /*
        - On importe les annotations ou attributs de Doctrine pour mapper cette classe à la base de données
        - Doctrine\ORM\Mapping : Chemin vers la bibliothèque Doctrine qui permet de mapper une classe PHP sur une table de base de données.
        - as ORM : On renomme l'importation en utilisant un alias ORM. Cela simplifie l’écriture des annotations plus tard.
    */
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    /*
        - On importe l'interface UserInterface qui définit les méthodes à implémenter pour que l'utilisateur puisse être utilisé dans le système de sécurité de Symfony
        - use : Importation d’une interface. Les interfaces définissent des méthodes que la classe devra implémenter.
        - On importe l'interface PasswordAuthenticatedUserInterface qui permet de gérer les mots de passe pour l'authentification
    */
use Symfony\Component\Security\Core\User\UserInterface;
    /*
        - Chemin vers une autre interface de Symfony qui gère les utilisateurs, notamment pour obtenir les rôles et l'identifiant unique.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> On utilise l'attribut ORM pour indiquer que cette classe est une entité Doctrine (liée à une table en base de données)
#[ORM\Entity(repositoryClass: UserRepository::class)]
    /*
        - #[ : Ceci est une syntaxe d’attribut PHP. Les attributs sont des métadonnées attachées à des éléments comme des classes.
        - ORM\Entity : Utilisation de l’alias ORM que nous avons défini plus tôt. Ici, cela indique que cette classe est une entité Doctrine qui sera mappée à une table dans la base de données.
        - repositoryClass : Définit la classe de repository associée à cette entité.
        - UserRepository::class : Spécifie que la classe UserRepository sera utilisée pour gérer cette entité dans la base de données.
    */

//* -> On spécifie que la table associée à cette entité s'appelle 'user' (les backticks sont utilisés car 'user' est un mot réservé en SQL)
#[ORM\Table(name: '`user`')]
    /*
        - #[ : Attribut PHP.
        - ORM\Table : Indique que cette classe est mappée à une table dans la base de données.
        - name: ' : Définit le nom de la table dans la base de données. Les backticks (`) permettent d'éviter les conflits avec des mots réservés en SQL comme "user".
        - user : Nom de la table dans la base de données.
    */

//* -> On définit une contrainte d'unicité sur le champ 'email' pour que chaque utilisateur ait un email unique dans la base de données
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
    /*
        - #[ : Attribut PHP.
        - ORM\UniqueConstraint : Indique qu'une contrainte d'unicité sera appliquée sur un ou plusieurs champs.
        - name: 'UNIQ_IDENTIFIER_EMAIL' : Nom de la contrainte d’unicité dans la base de données.
        - fields: ['email'] : Liste des champs sur lesquels la contrainte d’unicité s’applique (ici, le champ email doit être unique).
    */

class User implements UserInterface, PasswordAuthenticatedUserInterface
    /*
        - class : Mot-clé PHP qui sert à déclarer une classe.
        - User : Nom de la classe. C’est ici la classe qui représente l’utilisateur.
        - implements : Mot-clé qui signifie que la classe va implémenter une ou plusieurs interfaces, c’est-à-dire qu’elle doit fournir des définitions pour les méthodes de ces interfaces.
        - UserInterface : Interface Symfony qui définit des méthodes liées à l'utilisateur (comme obtenir les rôles et l'identifiant).
        - PasswordAuthenticatedUserInterface : Interface Symfony qui permet de définir des méthodes pour gérer l'authentification par mot de passe.
    */
{
    /*
    ************************************************************
    !      PROPRIETE/ATTRIBUTS DE L'ENTITIE/CLASS USER         *
    ************************************************************
    */

    //! ** email en id ** !//
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
        /*
            - ORM\Id : Indique que ce champ est la clé primaire de l'entité dans la base de données.
            - ORM\GeneratedValue : Indique que la valeur de cette colonne sera générée automatiquement par la base de données, généralement via un auto-incrément.
            - ORM\Column : Indique que cette propriété sera mappée à une colonne dans la base de données.
        */
    private ?int $id = null;  
        /*
            -'id' est un entier nullable (peut être null) avec une valeur par défaut de null
        */
    #[ORM\Column(length: 180)]
        /*
            - ORM\Column : Indique que cette propriété sera mappée à une colonne dans la base de données.
        */
    private ?string $email = null;
        /*
            - private : Mot-clé qui définit la visibilité de la propriété. private signifie que cette propriété est accessible uniquement depuis l'intérieur de la classe.
            - .? : Cela signifie que cette variable peut être null. Elle est optionnelle.
            - int : Type de la variable, ici c’est un entier.
            - $id : Nom de la propriété. Elle représente l'ID unique de l'utilisateur.
            - = null : Valeur par défaut de cette propriété, ici null.
        */

    //! ** roles ** !//
    #[ORM\Column]  
        /*
            -ORM\Column : Indique que cette propriété sera une colonne dans la base de données.
        */
    private array $roles = [];
        /*
            - private : Propriété privée, accessible uniquement depuis la classe.
            - array : Type de la variable, ici c’est un tableau.
            - $roles : Nom de la propriété. Elle stocke les rôles de l’utilisateur.
            - = [] : Le tableau des rôles est initialisé vide par défaut.
        */

    //! ** password ** !//
    #[ORM\Column]
        /*
            - ORM\Column : Indique que cette propriété sera mappée à une colonne de la base de données.
        */
    private ?string $password = null;
        /*
            - private : Propriété privée, accessible uniquement à l’intérieur de la classe.
            - .? : Indique que cette propriété peut être null.
            - string : Type de la variable, ici c’est une chaîne de caractères.
            - $password : Nom de la propriété. Elle stocke le mot de passe haché de l'utilisateur.
            - = null : Le mot de passe est initialisé à null par défaut.
        */

    //! ** firstname ** !//
    #[ORM\Column(length: 255)]
        /*
            - Attribut Php utilisé pour spécifier que la propriété sera un colonne dans la bdd
            - ORM\Column : Specifie que c'est une colonne de la table (user)
            - length: 255 : la colonne peut contenir une longueur maximal de 255 caracteres
        */
    private ?string $firstname = null;
        /*
            - private : propriété privée, donc accessible que dans la classe elle meme (user)
            - .?string : string(chaine de caractere) mais elle peut egalement etre null. Le ? signifie qu'elle est optionnelle
            - $firstname : propriété = prenom de l'utilisateur
            - = null : la valeur initiale de la propriété est null (elle n'est pas encore definie)
        */
    //! ** lastname ** !//
    #[ORM\Column(length: 255)]
        /*
            - Meme dchose que la propriété firstname
        */
    private ?string $lastname = null;
        /*
            - Meme dchose que la propriété firstname
        */

    //! ** addresses ** !//
    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'user')]
    private Collection $addresses;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private Collection $orders;
        /*
            - @var Collection<int, Address> : Indique que cette propriété est une collection d’objets Address.
            - #[ORM\OneToMany] : Annotation Doctrine qui définit une relation OneToMany entre l’utilisateur et ses adresses.
            - targetEntity: Address::class : Spécifie que cette relation pointe vers l’entité Address.
            - mappedBy: 'user' : Indique que le champ user dans l’entité Address est l’inverse de cette relation. Cela signifie que chaque adresse est associée à un utilisateur.
            - private Collection $addresses; : La propriété $addresses est de type Collection et stocke toutes les adresses liées à cet utilisateur.
        */

    //! ** methodes ** !//
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }
        /*
            - __construct() : Initialisation de la collection $addresses en tant qu’instance de ArrayCollection.
            - $this->addresses = new ArrayCollection(); : Crée une collection vide pour stocker les adresses. ArrayCollection est une implémentation de Collection fournie par Doctrine, optimisée pour gérer des ensembles d’entités.
        */

    public function __tostring()
    {
        return $this->getFirstname().' '.$this->getLastname();
    }
    /*
    ************************************************************
    !                        ID                                *
    ************************************************************
    */
    public function getId(): ?int
        /*
            - public : Déclare que cette méthode est accessible depuis l’extérieur de la classe.
            - function : Mot-clé pour déclarer une fonction ou une méthode.
            - getId : Nom de la méthode. Cette méthode retourne l'ID de l'utilisateur.
            - (): ?int : Indique que cette méthode retourne un entier (int), ou null si l'ID n’est pas encore défini.
        */
    {
        return $this->id;
            /*
                - return : Mot-clé qui renvoie une valeur au point d’appel de la fonction.
                - $this : Représente l'instance actuelle de l'objet.
                - ->id : Accède à la propriété id de l'objet.
            */
    }

    /*
    ************************************************************
    !                     EMAIL                                *
    ************************************************************
    */
    //* -> Cette méthode permet de récupérer l'email de l'utilisateur
    public function getEmail(): ?string
        /*
            - public : Méthode est accessible depuis l'extérieur de la classe.
            - function : Mot-clé qui définit une fonction ou une méthode.
            - getEmail : méthode pour récupérer l'email de l'utilisateur.
            - (): ?string : la méthode retourne un type string (chaîne de caractères) ou null si l'email n’est pas encore défini.
        */
    {
        return $this->email;
            /*
                - return : Mot-clé pour renvoyer une valeur = email de l'utilisateur.
                - $this : Représente l'instance actuelle de l'objet = classe User.
                - ->email : Accède à la propriété email de l’objet actuel (User).
            */
    }

    public function setEmail(string $email): static
        /*
            - public : méthode est accessible depuis l'extérieur de la classe.
            - function : Mot clé qui définit une fonction ou une methode
            - setEmail : Nom de la methode = Definit l'email de l'user
            - (string $email) : argument de la methode de type string = nouvelle email à definir
            - static : Retourne l’instance actuelle de l'objet (cela permet le chaînage des méthodes, où tu peux appeler plusieurs méthodes successivement).
        */
    {
        $this->email = $email;
            /*
                - $this : fait reference a l'instance actuelle de la class (user)
                - ->email : modifie la proprieté email de l'instance actuelle (user)
                - =$email : Assigne la valeur passé en parametre de la proprieté email
            */
        return $this;
            /*
                - return $this : retourne l'instance (user) de la classe actuelle ($this), qui permet de chainer les appels des methodes
            */
    }

    /*
    ************************************************************
    !                        EMAIL EN ID                       *
    ************************************************************
    */
    public function getUserIdentifier(): string
        /*
            - public : methode accessible depuis l'exterieur de la classe
            - function : declare la methode
            - getUserIdentitfier : nom de la methode pour obtenir un id unique pour l'utilisateur
            - (): string : La methode retournera une chaine de caractere (string)
        */
    {
        return (string) $this->email;
            /*
                - return : mot clé pour retourner une valeur
                - (string) : conversion(cast) de la proprieté email en chaine de caractére au cas ou elle aurais un autre type
                - $this->email : Accede a la propriété email dans l'instance de la classe (user)
            */
    }

    /*
    ************************************************************
    !                        ROLES                             *
    ************************************************************
    */
    public function getRoles(): array
        /*
            - public : methode accessible depuis l'exterieur de la classe
            - function : declare une methode
            - getRoles : Nom de la methode qui retourne les roles de l'user
            - (): array : Methode retourne un tableau qui conteint les roles de l'user
        */
    {
        $roles = $this->roles;
            /*
                - $roles : créer une nouvelle variables $roles qui contient les roles actuels de l'user
                - = : operateur qui assigne la valeur de $this->roles à $roles
                - $this->roles : accede à la proprieté roles de l'instance actuelle de la class (user)
            */

        //* -> On s'assure que chaque utilisateur a au moins le rôle 'ROLE_USER'
        $roles[] = 'ROLE_USER';
            /*
                - $roles[] = 'ROLE_USER' : On ajoute un role supplémentaire ROLE_USER, dans le tableau $roles pour que chaque user ait ce role par defaut
            */

        //* -> On retourne un tableau de rôles unique (sans doublon)
        return array_unique($roles);
            /*
                - return : mot clé qui retourne une valeur
                - array_unique($roles) : Supprime les doublons dans le tableau $roles avant de le retourner
            */
    }
    public function setRoles(array $roles): static
        /*
            - public : methode accessible depuis l'exterieur
            - function : mot clé pour déclarer la methode
            - setRoles : Nom de la methode pour definir les roles User
            - (array $roles) : Methode qui prend un parametre $roles qui est un tableau (array) de chaine de caractere
            - static : retourn l'instance actuelle de la class (user) pour permetre le chainage des methodes
        */
    {   
        //* -> On attribue les rôles fournis au tableau 'roles'
        $this->roles = $roles;  
            /*
                - $this->roles Modifie la propriété roles de l'instance actuelle (user)
                - =$roles : assigne les roles passées en parametre de la propriété roles
            */
        //* -> On retourne l'instance de l'objet pour permettre le chaînage de méthodes
        return $this;  
            /*
                - return $this : Reoturne l'instance actuelle de la class, ce qui permet le chainage des methodes
            */
    }

    /*
    ************************************************************
    !                        PASSWORD                          *
    ************************************************************
    */
    //* -> Grace à la config password_hashers dans le fichier security.yaml le pwd sera hashé
    public function getPassword(): ?string
        /*
            - public : peux etre utilisé en dehors de la class
            - function : declare la methode
            - getPassword : Nom de la methode qui renvoie le mdp de l'user
            - (): ?string : indique que la methode retourne soit une chaine de caractere (string) soit null
        */
    {
        //* -> On renvoie le mot de passe haché de l'utilisateur
        return $this->password;  
        /*
            - return : renvoie la valeur du mot de passe
            - $this->password : accede a la propriete password de l'instance actuelle (user)
        */
    }
    public function setPassword(string $password): static
        /*
            - public : methode accessible en dehors de la class (user)
            - function : mot clé pour déclarer la methode
            - setPassword : nom de la methode utilisée pour definir le mdp de l'user
            - (string $password) : prend un parametre $password de type string
            - static : retourn l'instance actuelle de la class (user) pour permettre le chainages des methodes
        */
    {
        //* -> On stocke le mot de passe haché dans l'attribut 'password'
        $this->password = $password;  
            /*
                - $this->password : modifier la propriété password de l'instance actuelle (user)
                - =$password : Assigne la valeur du parametre $password à la propriété password
            */

        //* -> On retourne l'instance actuelle de la classe    
        return $this;
            /*
                - return $this : retourne l'instance actuelle de la class user ( cette user en cours )
            */
    }

    /*
    ************************************************************
    !                   ERASECREDENTIALS                       *
    ************************************************************
    */
    public function eraseCredentials(): void
        /*
            - public : methode accessible en dehors de la class user
            - function : mot clé pour déclarer une methode
            - eraseCredentials : nom de la methode utilisée pour effacer les données sensible de l'user
            - ():void Indique que cette methode ne retourne aucune valeur
        */
    {
        /*
            - Si vous stockez des données sensibles temporaires, vous pouvez les effacer ici
            - $this->plainPassword = null; // Exemple pour effacer un mot de passe en clair (non haché) s'il existe
        */
    }

    /*
    ************************************************************
    !                        firstname                         *
    ************************************************************
    */
    public function getFirstname(): ?string
        /*
            - public : methode accessible depuis l'exterieur de la classe
            - function ; declare une fonction ou une methode
            - getFirstname : nom de la methode utilisé pour obtenir ou lire la valeur de la propriété $firstname
            - (): ?string : la methode retourne une valeur de type string ou null. le ? indique que le retour peut etre null si le prénom n'ap as encore été defini
        */
    {
        return $this->firstname;
            /*
                - return : Mot clé pour renvoyer une valeur depuis une methode
                - $this->firstname : On accede à la propriété $firstname de l'objet courant ($this). On renvoi cette valeur (la valeur definit de $firstname)
            */
    }

    public function setFirstname(string $firstname): static
        /*
            - public : methode accessible depuis l'exterieur de la class
            - function : declare fonction/methode
            - setFirstname : Nom de la methode, definit/modifie la valeur de $firstname
            - (string $firstname) : parametre $firstname de type string = valeur a atribuer au prénom de user
            - static : methode retourne l'instance actuelle de l'objet (user) ce qui permet de chainer plusieurs appels de methodes dans user
        */
    {
        $this->firstname = $firstname;
            /*
                - $this->firstname : On accede à la proprité $firstname de l'objet courant (user) et on lui assigne la valeur du parametre $firstname
                - return $this : retourn l'objet courant pour pouvoir chainer les methodes ensemble notamment grace a static 
            */
        return $this;
    }


    /*
    ************************************************************
    !                        lastname                          *
    ************************************************************
    */
    //* -> Meme principe que getFirstname et setFirstmane
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

/*
************************************************************
!                  RELATION avec addresses                 *
************************************************************
*/
    //* -> Methode pour retourner la collection d'adresses assoscié à l'User
    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }
        /*
            - getAddresses() : Retourne la collection d’adresses associées à l’utilisateur.
            - @return Collection<int, Address> : Indique que la méthode retourne une Collection d’objets Address.
            - : Collection : Spécifie que la méthode renvoie un objet de type Collection.
        */

    //* -> Methode pour ajouter une adresse à l'utilisateur
    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setUser($this);
        }

        return $this;
    }
        /*
            - addAddress(Address $address) : Ajoute une adresse à la collection d’adresses de l’utilisateur.
            - Address $address : Paramètre de type Address, représentant l’adresse à ajouter.
            - if (!$this->addresses->contains($address)) : Vérifie si l’adresse n’est pas déjà dans la collection.
            - $this->addresses->add($address); : Ajoute l’adresse à la collection.
            - $address->setUser($this); : Met à jour la propriété user de l’adresse pour l’associer à l’utilisateur actuel, assurant la synchronisation des deux côtés de la relation.
            - return $this; : Retourne l’instance actuelle pour permettre le chaînage de méthodes.
        */

    //* -> methode pour retirer l'adresse de la collection d'adresses de l'User
    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }
        /*
            - removeAddress(Address $address) : Supprime une adresse de la collection d’adresses de l’utilisateur.
            - $this->addresses->removeElement($address) : Supprime l’adresse de la collection et retourne true si l’élément existait et a été supprimé.
            - if ($address->getUser() === $this) : Vérifie si l’utilisateur actuel est bien celui assigné à l’adresse.
            - $address->setUser(null); : Supprime l’association de l’utilisateur dans l’entité Address pour désynchroniser les deux côtés de la relation.
            - return $this; : Retourne l’instance actuelle pour permettre le chaînage de méthodes.
        */


/*
************************************************************
!                  RELATION avec Order                 *
************************************************************
*/
        /**
         * @return Collection<int, Order>
         */
        public function getOrders(): Collection
        {
            return $this->orders;
        }

        public function addOrder(Order $order): static
        {
            if (!$this->orders->contains($order)) {
                $this->orders->add($order);
                $order->setUser($this);
            }

            return $this;
        }

        public function removeOrder(Order $order): static
        {
            if ($this->orders->removeElement($order)) {
                // set the owning side to null (unless already changed)
                if ($order->getUser() === $this) {
                    $order->setUser(null);
                }
            }

            return $this;
        }
}
/*
!Explications :
*1. Structure globale
    La classe User est une entité Doctrine qui implémente les interfaces UserInterface et PasswordAuthenticatedUserInterface. Elle est conçue pour :
        - Représenter un utilisateur dans la base de données.
        - Interagir avec le système de sécurité Symfony.

* 2. Mappage avec Doctrine
    - Annotations Doctrine (#[ORM\...]) :
        - #[ORM\Entity] : Indique que cette classe est une entité Doctrine mappée à une table dans la base de données.
        - #[ORM\Table(name: 'user')] : Définit explicitement le nom de la table comme user, tout en évitant les conflits avec des mots réservés SQL grâce aux backticks.
        - #[ORM\Column] : Mappe une propriété PHP à une colonne dans la base de données.
    - Contrainte d'unicité :
        - #[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])] : Garantit qu'un email est unique dans la table user.

* 3. Propriétés et méthodes
    - Propriétés principales :
        -id : Identifiant unique de l'utilisateur.
            - Généré automatiquement.
        - email : Email de l'utilisateur.
            - Doit être unique dans la base de données.
        - roles : Tableau de rôles associés à l'utilisateur.
            - Par défaut, contient ROLE_USER.
        - password : Mot de passe haché de l'utilisateur.
        - firstname et lastname : Prénom et nom de l'utilisateur.

    - Relations Doctrine :
        - addresses : Relation OneToMany entre un utilisateur et ses adresses.
            - Gérée avec une collection (ArrayCollection) pour contenir plusieurs entités Address.
        - orders : Relation OneToMany entre un utilisateur et ses commandes (Order).

    - Méthodes importantes :
        - getUserIdentifier : Retourne l'email comme identifiant unique.
        - getRoles : Ajoute automatiquement ROLE_USER au tableau de rôles avant de le retourner.
        - setPassword : Définit le mot de passe haché.
        - eraseCredentials : Méthode de sécurité pour supprimer des données sensibles temporaires.

        - Relations (addresses et orders) :
            - addAddress / addOrder : Ajoute une adresse ou une commande à l'utilisateur.
            - removeAddress / removeOrder : Retire une adresse ou une commande de l'utilisateur.

*4. Utilisation avec Symfony Security
    Grâce aux interfaces UserInterface et PasswordAuthenticatedUserInterface, la classe User est compatible avec le système de sécurité Symfony :

    - Authentification :
        - L'email est utilisé comme identifiant (getUserIdentifier).
        - Le mot de passe est haché via PasswordAuthenticatedUserInterface.
    - Rôles : Gérés via la méthode getRoles.
*/