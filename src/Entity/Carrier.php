<?php
//? ENTITE QUI REPRESENTE UN TRANSPORTEUR : Elle est liée à une table de base de données qui stocke les informations des transporteurs, telles que leur nom, description et prix.


/*
************************************************************
!           ESPACE ET IMPORTATION DES CLASSES              *
************************************************************
*/
namespace App\Entity;
    /*
        - namespace : Définit l’espace de noms pour cette classe.
        - App\Entity :indique que cette classe appartient au dossier Entity de l'application Symfony.
    */
use App\Repository\CarrierRepository;
    /*
        - use : Importation de la classe CarrierRepository pour associer cette entité à son dépôt personnalisé.
    */
use Doctrine\DBAL\Types\Types;
    /*
        - Doctrine\DBAL\Types\Types : Fournit les types de données supportés par Doctrine, comme TEXT ou INTEGER.
    */
use Doctrine\ORM\Mapping as ORM;
    /*
        - Doctrine\ORM\Mapping as ORM : Alias pour importer les annotations/mots-clés nécessaires pour mapper les propriétés de l'entité à la base de données.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* Définit cette classe comme une entité Doctrine, ce qui signifie qu'elle est liée à une table de base de données.
#[ORM\Entity(repositoryClass: CarrierRepository::class)]
    /*
        - #[ORM\Entity(...)] : Annotation indiquant que cette classe est une entité Doctrine, elle est mappée à une table dans la base de données.
        - repositoryClass : Spécifie que cette entité utilise CarrierRepository pour les requêtes personnalisées.
        - class Carrier : Nom de la classe représentant l’entité.
    */
class Carrier
{


/*
************************************************************
!                      PROPRIETES                          *
************************************************************
*/
    //! id !//
    //* Identifiant unique du transporteur.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
        /*
            - #[ORM\Id] : Définit la propriété comme clé primaire de l'entité.
            - #[ORM\GeneratedValue] : Indique que la valeur de cette clé primaire sera générée automatiquement.
            - #[ORM\Column] : Spécifie que cette propriété est une colonne de la table associée, sans préciser le type, Doctrine utilise un type par défaut basé sur la propriété (ici int).
            - private ?int $id = null; :
                - private : Propriété accessible uniquement depuis cette classe.
                - ?int : Accepte une valeur de type int ou null.
                - = null : Valeur par défaut.
        */

    //! name !//
    //* Nom du transporteur.
    #[ORM\Column(length: 255)]
    private ?string $name = null;
        /*
            - #[ORM\Column(length: 255)] : Indique que cette propriété est une colonne de type VARCHAR avec une longueur maximale de 255 caractères.
            - Type PHP : ?string (nullable string).
        */

    //! text (description) !//
    //* Description détaillée du transporteur.
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;
        /*
            - #[ORM\Column(type: Types::TEXT)] :
            - Définit cette propriété comme une colonne de type TEXT (pour stocker de longs textes).
            - Type PHP : ?string (nullable string).
        */

    //! price !//
    //* Prix du transporteur (T.T.C).
    #[ORM\Column]
    private ?float $price = null;
        /*
            - #[ORM\Column] :
            - Définit cette propriété comme une colonne dans la base de données.
            - Le type de la colonne est inféré comme FLOAT grâce au type PHP float.
            - Type PHP : ?float (nullable float).
        */


/*
************************************************************
!                        METHODES                          *
************************************************************
*/
    //! id !//
    //* Retourne l'identifiant du transporteur.
    public function getId(): ?int
    {
        return $this->id;
    }
        /*
            - public : La méthode est accessible depuis l'extérieur de la classe.
            - function getId() : Getter pour la propriété id.
            - : ?int : La méthode retourne une valeur de type int ou null.
        */

    //! name !//
    //* Retourne le nom du transporteur.
    public function getName(): ?string
    {
        return $this->name;
    }

    //* Définit le nom du transporteur.
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    //! text (description) !//
    //* Retourne la description du transporteur
    public function getText(): ?string
    {
        return $this->text;
    }

    //* Définit la description du transporteur.
    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    //! price !//
    //* Retourne le prix du transporteur.
    public function getPrice(): ?float
    {
        return $this->price;
    }

    //* Définit le prix du transporteur
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
/*
* Résumé
    - L'entité Carrier permet de gérer les données des transporteurs, notamment :
        - Identifiant ($id) : Généré automatiquement par la base de données.
        - Nom ($name) : Nom du transporteur (champ texte limité à 255 caractères).
        - Description ($text) : Description longue (champ TEXT).
        - Prix ($price) : Prix T.T.C du transporteur.

*Grâce à ces propriétés et méthodes, cette entité est utilisée pour stocker, récupérer et modifier les données des transporteurs dans l'application.

*/