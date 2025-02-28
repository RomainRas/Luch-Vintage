<?php
//? Formulaire qui gere l'inscription
    //! symfony console:make form
        //! Name of the form class : RegisterUserType
            //! Nom de la class lié : User


/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Form;
    // namespace : Définit l’espace de noms App\Form, indiquant que cette classe appartient aux formulaires de l’application.
use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

    /*
        - use : Importation de classes externes, nécessaires au fonctionnement du formulaire.
            - User : L’entité User à laquelle ce formulaire est lié.
            - UniqueEntity : Contrainte de validation pour vérifier l’unicité d’une propriété (ici, l'email).
            - AbstractType : Classe de base pour les formulaires Symfony.
            - Types de champs (EmailType, TextType, PasswordType, RepeatedType, SubmitType) : Différents types de champs pour le formulaire.
            - FormBuilderInterface : Interface pour construire les champs du formulaire.
            - OptionsResolver : Classe pour configurer les options du formulaire.
            - Length : Contrainte de validation pour imposer des limites de longueur sur les champs.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> Classe qui représente le formulaire d'inscription pour un nouvelle utilisateur
class RegisterUserType extends AbstractType
    /*
        - class : Déclare la classe.
        - RegisterUserType : Nom de la classe qui représente le formulaire d’inscription.
        - extends AbstractType : Hérite d’AbstractType, permettant de construire un formulaire Symfony.
    */
{


/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Methode pour construire les champs de formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
        /*
            - public : La méthode est publique et accessible de l’extérieur.
            - function` : Mot-clé qui déclare une méthode.
            - buildForm : Nom de la méthode, utilisée pour construire les champs du formulaire.
            - FormBuilderInterface $builder : Interface pour ajouter et configurer les champs du formulaire.
            - array $options : Paramètre pour des options supplémentaires de configuration.
            - : void : La méthode ne retourne rien.
        */

    {
    /*
    ************************************************************
    !      CONSTRUCTION DU FORMULAIRE AVEC LES CHAMPS          *
    ************************************************************
    */
        //* -> Objet pour ajouter les champs au formulaire
        $builder
        ->add('email', EmailType::class, [ // NotBlank, email, UniqueEntity
            'label' => "Votre Adresse Email",
            'attr' => [
                'placeholder' => "Indiquez votre adresse email",
                'class' => 'form-control registration-input'
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => "L'adresse email est obligatoire."
                ]),
                new Assert\Email([
                    'message' => "L'adresse email '{{ value }}' n'est pas valide."
                ]),
            ],
        ])
        ->add('plainPassword', RepeatedType::class, [ // NotBlank, min12, regex 
            'type' => PasswordType::class,
            'constraints' => [
                new Assert\NotBlank([
                    'message' => "Le mot de passe est obligatoire."
                ]),
                new Length([
                    'min' => 12,
                    'max' => 30,
                    'minMessage' => "Le mot de passe doit contenir au moins 12 caractères.",
                    'maxMessage' => "Le mot de passe ne doit pas dépasser 30 caractères."
                ]),
                new Assert\Regex([
                    'pattern' => '/^(?=.*[A-Z])(?=.*[\W])(?=.*\d)(?!.*\s).{12,30}$/',
                    'message' => "Le mot de passe doit contenir au moins une majuscule, un chiffre, un symbole et ne pas contenir d'espaces."
                ])
            ],
            'first_options' => [
                'label' => 'Votre Mot de Passe',
                'attr' => [
                    'placeholder' => "Choisissez votre mot de passe",
                    'class' => 'form-control registration-input'
                ],
                'hash_property_path' => 'password'
            ],
            'second_options' => [
                'label' => 'Confirmez votre mot de passe',
                'attr' => [
                    'placeholder' => "Confirmez votre mot de passe",
                    'class' => 'form-control registration-input'
                ]
            ],
            'invalid_message' => "Les mots de passe ne correspondent pas.",
            'mapped' => false,
        ])
        
        ->add('firstname', TextType::class, [
            'label' => "Votre Prénom",
            'constraints' => [
                new Length([
                    'min' => 3,
                    'max' => 30
                ]),
                new Assert\NotBlank([
                    'message' => "L'adresse email est obligatoire."
                ]),
            ],
            'attr' => [
                'placeholder' => "Indiquez votre prénom",
                'class' => 'form-control registration-input'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label' => "Votre Nom",
            'constraints' => [
                new Length([
                    'min' => 3,
                    'max' => 30
                ]),
                new Assert\NotBlank([
                    'message' => "L'adresse email est obligatoire."
                ]),
            ],
            'attr' => [
                'placeholder' => "Indiquez votre nom",
                'class' => 'form-control registration-input'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Valider",
            'attr' => [
                'class' => 'btn btn-success registration-btn'
            ]
        ]);
    }    
    //! ** options ** !//
    //* -> Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
        /*
            - configureOptions : Méthode qui permet de définir les options du formulaire.
            - OptionsResolver $resolver` : Objet utilisé pour configurer les options (comme l'entité à laquelle le formulaire est lié).
        */
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class, 
                        // `User::class` : Le formulaire est lié à l'entité `User`.
                    'fields' => 'email' 
                        // On impose que le champ `email` soit unique.
                ])
            ],
            'data_class' => User::class,
                /*
                    - `data_class` : L'entité à laquelle le formulaire est lié, ici `User`.
                    - Cela signifie que les champs du formulaire sont mappés directement aux propriétés de l'entité `User`.
                */
        ]);
            /*
                - constraints : Ajoute une contrainte UniqueEntity pour s’assurer que l’email est unique dans la base de données.
                - entityClass : Spécifie que l’entité User est concernée.
                - fields: 'email' : Le champ email doit être unique.
                - data_class: User::class : Associe le formulaire à l’entité User, ce qui permet de lier automatiquement les champs aux propriétés de l’entité.
            */
    }

}
/*
!Explications supplémentaires

    * buildForm() :
        - Champ email :
            - Utilise EmailType pour valider automatiquement que l'utilisateur saisit une adresse email correcte.
            - attr : Définit des attributs HTML pour afficher un texte indicatif avec placeholder.

    * Champ plainPassword (mot de passe avec confirmation) :
        - RepeatedType : Demande à l'utilisateur de saisir le mot de passe deux fois pour confirmation.
        - constraints : Définit des contraintes de longueur minimum et maximum.
        - hash_property_path : Indique que le mot de passe doit être haché avant d'être stocké.
        - mapped à false : Ce champ n'est pas directement lié à une propriété de l'entité User; il sera géré manuellement lors de l'inscription.

    * Champs firstname et lastname :
        - TextType pour des champs de texte simples.
        - constraints impose une longueur minimale et maximale.

    * Bouton submit :
        - SubmitType crée un bouton de soumission, stylisé avec la classe Bootstrap btn btn-success.

    * configureOptions() :
        - Associe le formulaire à l’entité User via data_class, permettant de lier chaque champ aux propriétés de l’entité.
        - UniqueEntity : Contrainte qui garantit que l'email est unique dans la base de données.
    
    * Résumé
    Cette classe de formulaire RegisterUserType gère l'inscription d'un utilisateur, avec validation des données saisies, confirmation du mot de passe, et vérification de l'unicité de l'email. Elle utilise plusieurs types de champs et contraintes de validation pour garantir l'intégrité des données et sécuriser l'inscription.
*/