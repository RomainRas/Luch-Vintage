<?php
/*
************************************************************
!           ESPACE ET IMPORTATION DES CLASSES              *
************************************************************
*/
namespace App\Entity;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
    /*
        - Déclare le namespace `App\Entity`, qui regroupe les entités du projet.
        - CategoryRepository : Dépôt spécifique à l’entité Category, permettant d’exécuter des requêtes personnalisées sur cette entité.
        - ArrayCollection et Collection : Classes de Doctrine pour manipuler des collections d’objets. ArrayCollection est une implémentation de Collection.
        - ORM : Alias pour le mapping objet-relationnel (ORM) de Doctrine, permettant de définir les métadonnées pour la base de données.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
    /*
        - #[ORM\Entity] : Attribut Doctrine indiquant que cette classe représente une entité stockée en base de données.
        - repositoryClass: CategoryRepository::class : Spécifie que cette entité utilise CategoryRepository pour ses requêtes personnalisées.
    */



class Category
{
    /*
    ************************************************************
    !                  PROPRIETES ET ATTRIBUTS                 *
    ************************************************************
    */
    //! ** id ** !//
    //* -> Clé primaire de l'entité, auto incr, column. nom = id, privé et nul
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
        /*
            - #[ORM\Id] : Attribut indiquant que cette propriété est la clé primaire de l’entité.
            - #[ORM\GeneratedValue] : Indique que l’identifiant est auto-généré.
            - #[ORM\Column] : Définit cette propriété comme une colonne dans la table correspondante de la base de données.
            - private ?int $id = null : Propriété privée de type int ou null, initialisée à null.
        */

    //! ** Name ** !//
    //* -> Propriété Name pour le nom de la catégorie
    #[ORM\Column(length: 255)]
    private ?string $name = null;
        /*
            - #[ORM\Column(length: 255)] : Définit une colonne de type string avec une longueur maximale de 255 caractères.
            - private ?string $name = null : Propriété privée de type string ou null, représentant le nom de la catégorie.
        */

    //! ** Slug ** !//
    //* -> Propriété `$slug` pour le slug de la catégorie, c'est un version URL-friendly du nom de la catégorie (slug = url)
    #[ORM\Column(length: 255)]
    private ?string $slug = null;
        /*
            - #[ORM\Column(length: 255)] : Définit une colonne de type string avec une longueur maximale de 255 caractères.
            - private ?string $slug = null : Propriété privée de type string ou null, utilisée pour stocker le slug de la catégorie (URL-friendly).
        */

    /*
    ************************************************************
    !                        RELATIONS                         *
    ************************************************************
    */

    //! ** Product ** !//
    //* -> Propriété `$products` qui représente la relation OneToMany entre `Category` et `Product`.
    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private Collection $products;
        /*
            - #[ORM\OneToMany(...)] : Définit une relation OneToMany avec l’entité Product.
            - targetEntity: Product::class : Spécifie que la relation est avec l’entité Product.
            - mappedBy: 'category' : Indique que cette relation est gérée par la propriété category de l’entité Product.
            - private Collection $products : Propriété privée de type Collection pour stocker tous les produits associés à cette catégorie.
        */


    /*
    ************************************************************
    !                 METHODES et FONCTIONS                    *
    ************************************************************
    */

    //! ** __construct ** !//
    public function __construct()
    {   
        //* -> Initialise `$products` comme une nouvelle instance d'ArrayCollection pour stocker les produits associés à cette catégorie.
        $this->products = new ArrayCollection();
    }
        /*
            - __construct() : Constructeur de la classe, initialisant la collection $products comme une ArrayCollection, ce qui permet de gérer facilement les produits associés.
        */

    //! ** __toString ** !//
    //* -> Méthode magique `__toString()` qui permet de retourner le nom de la catégorie comme chaîne de caractères.
    public function __toString()
    {
        return $this->name;
    }
        /*
            - __toString() : Méthode spéciale PHP qui permet de définir comment l’objet Category sera converti en chaîne de caractères. Ici, elle retourne le nom de la catégorie.
            - Elle est utile pour afficher les catégories dans les interfaces sans manipuler directement leurs propriétés.
        */
    
    //! ** getID ** !//
    //* -> Getter pour l'identifiant de la catégorie.
    public function getId(): ?int
    {
        return $this->id;
    }
        /*
            - getId() : Retourne l’identifiant de la catégorie.
        */

    //! ** getName et setName ** !//
    public function getName(): ?string
    //* -> Getter pour le nom de la catégorie.
    {
        return $this->name;
    }
        /*
            - getName() : Retourne le nom de la catégorie.
        */

    //* -> Setter pour le nom de la catégorie.
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
        /*
            - setName() : Définit le nom de la catégorie et retourne l’instance courante pour permettre le chaînage des méthodes.
        */

    //! ** getSlug et setSlug ** !//
    //* -> Getter pour le slug de la catégorie.
    public function getSlug(): ?string
    {
        return $this->slug;
    }
        /*
            - getSlug() : Retourne le slug de la catégorie.
        */
    
    //* -> Setter pour le slug de la catégorie.
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
        /*
            - setSlug() : Définit le slug de la catégorie et retourne l’instance courante.
        */

    //! ** getProduct, addProduct et RemoveProduct ** !//
    //* -> Getter pour la collection de produits associés à cette catégorie.
    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
        /*
            - getProducts() : Retourne la collection des produits associés à la catégorie.
        */

    //* -> Méthode pour ajouter un produit à la collection `$products`.
    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
            // Si le produit n'existe pas déjà dans la collection, on l'ajoute et on met à jour la catégorie du produit.

    }
        /*
            - addProduct() : Ajoute un produit à la collection si celui-ci n’y est pas déjà présent.
            - $this->products->contains($product) : Vérifie si le produit est déjà dans la collection.
            - $this->products->add($product) : Ajoute le produit à la collection.
            - $product->setCategory($this) : Définit la catégorie du produit ajouté comme étant l’instance courante de Category.
        */

    //* -> Méthode pour retirer un produit de la collection `$products`.
    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
        /*
            - removeProduct() : Supprime un produit de la collection et met à jour la relation.
            - $this->products->removeElement($product) : Supprime le produit de la collection.
            - if ($product->getCategory() === $this) : Vérifie si le produit est toujours lié à la catégorie avant de le détacher.
            - $product->setCategory(null) : Supprime la référence à la catégorie dans le produit pour éviter les relations orphelines.
        */
}
/*
*Résumé
    Cette entité Category représente une catégorie de produits dans l’application :

    - Elle possède des propriétés pour un identifiant ($id), un nom ($name), un slug ($slug), et une collection de produits ($products).
    - Des méthodes permettent d’obtenir et de définir le nom et le slug, ainsi que d’ajouter et de retirer des produits de la catégorie.
    - La relation avec l’entité Product est définie avec une relation OneToMany, chaque catégorie pouvant avoir plusieurs produits associés.
    
    Ce modèle simplifie la gestion des catégories et des produits associés dans une application Symfony.

*/