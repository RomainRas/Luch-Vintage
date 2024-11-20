<?php
//! Formulaire qui gere l'inscription
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
            // `$builder` : L'objet utilisé pour ajouter les champs au formulaire.
        
        //! ** email ** !//
            //* -> Champ pour l'email
            ->add('email', EmailType::class, [
                'label' => "Votre Adresse Email", 
                'attr' => [
                    'placeholder' => "Indiquez votre adresse email"
                ]
            ])
                /*
                    - add() : Ajoute un champ au formulaire.
                    - 'email' : Nom du champ, lié à la propriété email dans l’entité User.
                    - EmailType::class : Type du champ, spécifique pour les emails, avec une validation de format incluse.
                    - label : Texte affiché à côté du champ, pour guider l’utilisateur.
                    - `attr` : Définit des attributs HTML supplémentaires.
                    - placeholder : Texte indicatif dans le champ.
                */
    
        //! ** pwd et sa confirmation (repetition) ** !//
            //* -> Champs pour le pwd avec confirmation
            ->add('plainPassword', RepeatedType::class,[
                /*
                    - plainPassword : Nom du champ pour le mot de passe, utilisé uniquement dans le formulaire pour la saisie et confirmation du mot de passe.
                    - RepeatedType::class : Type de champ qui demande à l'utilisateur de saisir deux fois la même information (mot de passe ici).
                */

                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        // `Length` : Contrainte de validation imposant une longueur minimale et maximale au champ.
                        'min' => 4, 
                            // `min` : La longueur minimale du mot de passe est de 4 caractères.
                        'max' => 30, 
                            // `max` : La longueur maximale du mot de passe est de 30 caractères.
                    ])
                ],
                    /*
                        - type : Définit le champ comme PasswordType pour masquer les caractères saisis.
                        - constraints : Contrainte de longueur, imposant un minimum de 4 et un maximum de 30 caractères.
                    */

                'first_options' => [
                    'label' => 'Votre Mot de Passe', 
                    'attr' => [
                        'placeholder' => "Choisissez votre mot de passe"
                            // Texte indicatif dans le champ.
                    ],
                    'hash_property_path' => 'password'
                ],
                    /*
                        - first_options : Options pour le premier champ.
                        - label et placeholder : Indiquent le texte de guidage pour la première saisie du mot de passe.
                        - hash_property_path : Permet le hachage du mot de passe.
                    */

                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => "Confirmez votre mot de passe"
                            // Texte indicatif pour la confirmation du mot de passe.
                    ]
                ],
                    /*
                        - second_options : Options pour le second champ, pour la confirmation.
                    */

                'mapped' => false,
                    /*
                        - mapped: false : Ce champ n’est pas directement lié à l’entité User, nécessitant un traitement manuel des données lors de la soumission.
                    */
            ])

            //! ** firstname ** !//
            //* -> Champs pour le prenom
            ->add('firstname', TextType::class, [
                'label' => "Votre Prénom", 
                    // Label affiché à côté du champ de texte.
                'constraints' => [
                    new Length([
                        'min' => 3, 
                            // Longueur minimale pour le prénom.
                        'max' => 30 
                            // Longueur maximale pour le prénom.
                    ])
                ],
                'attr' => [
                    'placeholder' => "Indiquez votre prénom"
                        // Texte indicatif dans le champ.
                ]
            ])
                /*
                    - firstname : Nom du champ pour le prénom de l’utilisateur.
                    - TextType::class : Champ de texte simple.
                    - label et placeholder : Affichent une indication pour guider l’utilisateur.
                    - constraints : Limite la longueur entre 3 et 30 caractères.
                */

            //! ** lastname ** !//
            //* -> Champs pour le nom
            ->add('lastname', TextType::class, [
                'label' => "Votre Nom", 
                    // Texte visible à côté du champ.
                'constraints' => [
                    new Length([
                        'min' => 3, 
                            // Longueur minimale pour le nom.
                        'max' => 30 
                            // Longueur maximale pour le nom.
                    ])
                ],
                'attr' => [
                    'placeholder' => "Indiquez votre nom"
                        // Texte indicatif dans le champ.
                ]
            ])
                /*
                    - lastname : Nom du champ pour le nom de l’utilisateur.
                    - TextType::class : Champ de texte.
                    - label et placeholder : Indiquent à l’utilisateur quoi entrer.
                    - constraints : Limite la longueur entre 3 et 30 caractères.
                */
            
            //! ** validation ** !//
            //* -> Bouton de soumission
            ->add('submit', SubmitType::class, [
                // `submit` : Type de champ pour le bouton de soumission.
                'label' => "Valider", 
                    // Texte affiché sur le bouton.
                'attr' => [
                    'class' => 'btn btn-success'
                        // Classe CSS pour styliser le bouton (ici une classe Bootstrap pour le rendre vert).
                ]
            ]);
                /*
                    - submit : Bouton de soumission pour envoyer le formulaire.
                    - label : Texte affiché sur le bouton.
                    - class : Classe CSS (btn btn-success) pour styliser le bouton avec Bootstrap (vert).
                */
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