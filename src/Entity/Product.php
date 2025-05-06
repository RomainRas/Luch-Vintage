<?php
//? Cette entité Product représente un produit dans l’application.

/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Entity;
    // namespace : Organise la classe Product dans l’espace App\Entity, regroupant les entités de l’application.
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
    /* use : Importe des classes externes nécessaires pour cette entité.
        - ProductRepository : Dépôt spécifique pour gérer les requêtes personnalisées sur les produits.
        - Types : Utilisé pour spécifier des types de colonnes dans la base de données.
        - ORM : Alias pour l’ORM de Doctrine, qui permet d’utiliser les attributs de mapping des entités.
    */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
    /* 
        - #[ORM\Entity] : Indique que cette classe représente une entité de la base de données.
            - repositoryClass: ProductRepository::class : Spécifie que cette entité utilise ProductRepository pour ses requêtes personnalisées.
    */
class Product
{
    /*
    ************************************************************
    !      PROPRIETE/ATTRIBUTS DE L'ENTITIE/CLASS Product      *
    ************************************************************
    */

    //! ** id ** !//
    //* -> Propriété `$id` qui est la clé primaire, auto-générée, de type `int` pour chaque produit.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //! ** name ** !//
    //* -> Propriété `$name` pour le nom du produit, de type `string` avec une longueur maximale de 255 caractères.
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    //! ** slug ** !//
    //* -> Propriété `$slug` pour un identifiant unique du produit dans l'URL, également de type `string`.
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    //! ** description ** !//
    //* -> Propriété `$description` pour la description détaillée du produit, de type `TEXT` pour accepter de longues descriptions.
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;


    //! ** illustration ** !//
    //* -> Propriété `$illustration` pour le chemin de l'image du produit, de type `string` avec une longueur maximale de 255 caractères.
    #[ORM\Column(length: 255)]
    private ?string $illustration = null;

    //! ** price ** !//
    //* -> Propriété `$price` pour le prix HT du produit, de type `float`.
    #[ORM\Column]
    private ?float $price = null;

    //! ** tva ** !//
    //* -> Propriété `$tva` pour le taux de TVA appliqué au produit, de type `float`.
    #[ORM\Column]
    private ?float $tva = null;


    /*
    ************************************************************
    !                        RELATIONS                         *
    ************************************************************
    */

    //! ** category ** !//
    //* -> Relation ManyToOne entre `Product` et `Category`, chaque produit étant associé à une seule catégorie.
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    public ?bool $isHomepage = null;
        /*
            - #[ORM\ManyToOne(...)] : Définit une relation ManyToOne avec l’entité Category.
            - inversedBy: 'products' : Indique que la relation inverse est définie par la propriété products dans l’entité Category.
            - private ?Category $category = null : Propriété pour stocker la catégorie à laquelle appartient le produit.
        */

    /*
    ************************************************************
    !                 METHODES et FONCTIONS                    *
    ************************************************************
    */

    //! ** id ** !//
    //* -> Getter pour l'identifiant du produit.
    public function getId(): ?int
    {
        return $this->id;
    }
        /*
            - getId() : Retourne l’identifiant du produit.
        */

    //! ** name ** !//
    //* -> Getter pour le nom du produit.
    public function getName(): ?string
    {
        return $this->name;
    }
        /*
            - getName() : Retourne le nom du produit.
        */

    //* -> Setter pour le nom du produit.
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
        /*
            - setName() : Définit le nom du produit et retourne l’instance courante pour permettre le chaînage des méthodes.
        */

    //! ** slug ** !//
    //* -> Getter pour le slug du produit.
    public function getSlug(): ?string
    {
        return $this->slug;
    }
        /*
            - getSlug() : Retourne le slug du produit.
        */

    //* -> Setter pour le slug du produit.
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
        /*
            - setSlug() : Définit le slug du produit et retourne l’instance courante.
        */

    //! ** description ** !//
    //* -> Getter pour la description du produit.
        public function getDescription(): ?string
    {
        return $this->description;
    }
        /*
            - getDescription() : Retourne la description du produit.
        */

    //* -> Setter pour la description du produit.
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
        /*
            - setDescription() : Définit la description du produit.
        */

    //! ** illustration ** !//
    //* -> Getter pour l'illustration (chemin de l'image) du produit.
    public function getIllustration(): ?string
    {
        return $this->illustration;
    }
        /*
            - getIllustration() : Retourne l’illustration du produit.
        */

    //* -> Setter pour l'illustration du produit.
    public function setIllustration(string $illustration): static
    {
        $this->illustration = $illustration;

        return $this;
    }
        /*
            - setIllustration() : Définit l’illustration du produit.
        */

    //! ** price ** !//
    //* -> Getter pour le prix HT du produit.
    public function getPrice(): ?float
    {
        return $this->price;
    }
        /*
            - getPrice() : Retourne le prix hors taxes du produit.
        */

    //* -> Setter pour le prix du produit.
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
        /*
            - setPrice() : Définit le prix du produit.
        */

    //* -> Calcule le prix TTC en appliquant le taux de TVA au prix HT.
    public function getPriceWt()
    {
        $coeff = 1 + ($this->tva/100);
        return $coeff * $this->price;
    }
        /*
            - getPriceWt() : Calcule et retourne le prix TTC du produit.
                - $coeff : Calcule le coefficient en ajoutant le taux de TVA (tva / 100) à 1.
                - return $coeff * $this->price : Multiplie le prix HT par le coefficient pour obtenir le prix TTC.
        */

    //! ** tva ** !//
    //* -> Getter pour le taux de TVA du produit.
    public function getTva(): ?float
    {
        return $this->tva;
    }
        /*
            - getTva() : Retourne le taux de TVA du produit.
        */

    //* -> Setter pour le taux de TVA.
        public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
        /*
            - setTva() : Définit le taux de TVA du produit.
        */

    //! ** category ** !//
    //* -> Getter pour la catégorie du produit.
    public function getCategory(): ?Category
    {
        return $this->category;
    }
        /*
            - getCategory() : Retourne la catégorie du produit.
        */

    //* -> Setter pour la catégorie du produit.
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
        /*
            - setCategory() : Définit la catégorie à laquelle appartient le produit.
        */

        public function isHomepage(): ?bool
        {
            return $this->isHomepage;
        }

        public function setHomepage(?bool $isHomepage): static
        {
            $this->isHomepage = $isHomepage;

            return $this;
        }
}
/*
!Explications supplémentaires :
    *Annotations ORM et Mapping :
        - #[ORM\Entity] : Déclare la classe comme une entité, liée à la table product dans la base de données.
        - #[ORM\ManyToOne] : Déclare une relation de type "un produit appartient à une catégorie", avec la relation inversée sur la propriété products de Category.
    
    * Méthode getPriceWt() :
        - Calcule le prix TTC (toutes taxes comprises) du produit en fonction du prix HT et du taux de TVA.
        - Applique le coefficient 1 + (tva/100) au prix HT.
    
    *Retour de méthode avec static :
        - Les setters utilisent le type de retour static, ce qui permet d'enchaîner les appels de méthodes ($product->setName(...)->setPrice(...);).
!Résumé
    *Cette entité Product représente un produit dans l’application. Elle comprend :
        - Des propriétés pour le nom, le slug, la description, l’illustration, le prix hors taxes, le taux de TVA, et la catégorie.
        - Des méthodes pour gérer chaque propriété, ainsi qu’une méthode getPriceWt() pour calculer le prix TTC.

    L’utilisation de Doctrine facilite l’interaction avec la base de données, et les relations ManyToOne entre Product et Category permettent de gérer facilement les catégories de produits.
*/