<?php
//! Controller qui va gérer le changement de pwd de l'user
    //! symfony console make:form
        //! Nom du controller : PasswordUserType
            //! Nom de l'entité lié : User 
                // ! created: src/From/PasswordUserType.php


/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
    /*
        - namespace : Définit l’espace de noms App\Form, indiquant que cette classe fait partie des formulaires de l’application.
        - User : Entité User à laquelle le formulaire est lié.
        - AbstractType : Classe de base pour tous les formulaires Symfony.
        - FormBuilderInterface : Interface pour construire les champs du formulaire.
        - Length : Classe pour appliquer une contrainte de validation sur la longueur du mot de passe.
        - OptionsResolver : Permet de configurer les options du formulaire.
        - SubmitType, PasswordType, RepeatedType : Types de champs spécifiques dans le formulaire (bouton de soumission, champs de mot de passe, champ répété).
        - FormEvent et FormEvents : Gestion des événements de formulaire (pour ajouter un écouteur d’événements).
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class PasswordUserType extends AbstractType
    /*
        - class : Mot-clé pour déclarer une classe.
        - PasswordUserType : Nom de la classe, qui représente un formulaire de modification de mot de passe.
        - extends AbstractType : Hérite de AbstractType, ce qui permet de créer et de configurer un formulaire Symfony.
    */

{
    /*
    ************************************************************
    !   Construction du builder avec les champs et options     *
    ************************************************************
    */

    //! ** Formulaire ** !//
    //* -> Methode pour créer un formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
        /*
            - public : La méthode est publique, accessible de l’extérieur.
            - buildForm : Nom de la méthode, utilisée pour construire le formulaire en ajoutant des champs.
            - FormBuilderInterface $builder : Paramètre pour construire et configurer les champs du formulaire.
            - array $options : Paramètre contenant les options du formulaire (ici, utilisé pour le hachage de mot de passe).
            - : void : La méthode ne retourne rien.
        */

    {
        $builder
            /*
                - `$builder` : L'objet `FormBuilderInterface` qui construit le formulaire.
            */
            //! ** pwd actuel ** !//
            //* -> Champs pour le pwd actuel
            ->add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel", 
                'attr' => [
                    'placeholder' => "Entrez votre mot de passe actuel" 
                ],
                'mapped' => false, 
            ])
                /*
                    - add() : Méthode pour ajouter un champ au formulaire.
                    - 'actualPassword' : Nom du champ dans le formulaire pour le mot de passe actuel.
                    - PasswordType::class : Type de champ pour un mot de passe (masque les caractères saisis).
                    - label : Étiquette du champ, affichée pour guider l’utilisateur.
                    - `attr` : Attributs HTML supplémentaires pour ce champ. Ils permettent de définir des propriétés comme le placeholder ou les classes CSS.
                    - placeholder : Texte indicatif dans le champ pour aider l’utilisateur.
                    - mapped: false : Indique que ce champ n’est pas lié directement à une propriété de l’entité User (il sert uniquement à vérifier le mot de passe actuel).
                */

            //! ** nouveau pws + repetition ** !//
            //* -> Champs pour le nouveau pwd
            ->add('plainPassword', RepeatedType::class,[

                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4, 
                            // `min` : Longueur minimale requise pour le mot de passe (ici, 4 caractères).
                        'max' => 30, 
                            // `max` : Longueur maximale autorisée pour le mot de passe (ici, 30 caractères).
                    ])
                ],

                'first_options' => [
                    'label' => 'Votre nouveau mot de passe', 
                        // `label` : Étiquette affichée à côté du champ pour guider l'utilisateur.
                    'attr' => [
                        'placeholder' => "Choisissez votre nouveau mot de passe" 
                        // `placeholder` : Texte indicatif affiché dans le champ avant que l'utilisateur ne saisisse son nouveau mot de passe.
                    ],
                    'hash_property_path' => 'password'
                ],

                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe', 
                        // `label` : Texte visible à côté du second champ pour confirmer le mot de passe.
                    'attr' => [
                        'placeholder' => "Confirmez votre nouveau mot de passe" 
                        // `placeholder` : Texte d’indication dans le champ avant que l’utilisateur ne confirme son nouveau mot de passe.
                    ]
                ],

                'mapped' => false, 
            ])
                /*
                    - 'plainPassword' : Nom du champ pour le nouveau mot de passe.
                    - RepeatedType::class : Type de champ répétitif pour une saisie en double (confirmation).
                        - type : Définit le champ comme PasswordType pour masquer la saisie.
                        - constraints : Contraintes de validation, ici pour une longueur entre 4 et 30 caractères.
                        - first_options : Options spécifiques pour le premier champ.
                            - label et placeholder : Indiquent l’intitulé et le texte indicatif pour la première saisie du nouveau mot de passe.
                            - hash_property_path : Propriété pour hacher le mot de passe avant de l’enregistrer (garantissant la sécurité).
                        - second_options : Options pour le champ de confirmation.
                            - label et placeholder : Étiquette et texte indicatif pour la confirmation du mot de passe.
                        - mapped: false : Ce champ n’est pas directement lié à l’entité User pour éviter un enregistrement automatique ; il sera géré manuellement.
                */

            //! ** submit button ** !//
            //* -> Champ de soumission pour envoyer le formulaire
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour mon mot de passe", 
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
                /*
                    - submit : Champ de soumission pour envoyer le formulaire.
                    - label : Texte affiché sur le bouton.
                    - class : Attribut pour styliser le bouton avec Bootstrap (btn btn-success).
                */

            //? explication : On ajoute un ecouteur (addEventListener) c'est à dire : des qu'il se passe quelque chose à une moment precis, tu fasse quelque chose
                    //? le moment : FormEvents::SUBMIT
                    //? ce qu'il faut faire (comparer les pwd) : function (FormEvent $event) { ce que je veux faire }
            //* -> Ajout d'un ecouteur d'événement pour verifier le pwd lors de la soumission
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /*
                    - addEventListener() : Méthode qui permet d'ajouter un écouteur d'événements pour vérifier le mot de passe lors de la soumission.
                    - FormEvents::SUBMIT : Déclenche l’événement lorsque le formulaire est soumis, c'est le moment ou on veux declencher l'écoute (faire un control +clic gauche pour voir les types d'event dans la class)
                */

                //* -> On recup le mdp saisie par l'user et le comparer au mdp en bdd (dans l'entité)
                $form = $event->getForm(); 
                    /*
                        - $form : variable qui va contenir de formulaire recupéré depuis l'event $event. cette fonction reprensent l'objet formulaire complet
                        - $event : c'est l'objet de type FormEvent. de l'event declenché lors de la soumussion du formulaire. il se situe dans les parametre de la methode
                        - $event->getForm() : Récupère le formulaire soumis lorsque l'evenement est declenché et donc des les stocké dans la variable $form
                    */

                //* -> il nous faut la valeur data dans les options de l'user pour stocker dans la variable $user le mdp qu'il vient de rentrer dans 'actualPassword'

                //* -> Il faut le pwd passé dans le formulaire
                $user = $form->getConfig()->getOptions()['data'];
                    /*
                        - $user : Récupère les données de l’utilisateur actuel à partir des options du formulaire.
                        - $form : objet formualire crée a partir de $form = $event->getForm(); 
                        - getConfig() : Methode pour recuperer la config du formulaire (il y a diverses information : dd($user)) elle permet d'acceder aux informations nottament passé lors de la realisation du formulaire
                        - getOption() : Methode pour recuperer TOUTES LES OPTIONS qui on été definis lors de la creation du formulaire et d'autres données specifiques lié à l'entitié (dd($user) checker les options)
                        - ['data'] : On accede à l'option precise appelée data. Elle contient les données passées au formulaire. dans ce cas le mot de passe actuelle de l'user
                    */

                //* -> Il faut que l'on hache le pdw rentré dans le formulaire pour le comparer a celui en bdd
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                    /*
                        - $passwordHasher : Récupère le hachage pour vérifier le mot de passe.
                    */

                //? https://symfony.com/doc/current/security/passwords.html#hashing-the-password 
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );
                if(!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme. Verifiez votre saisie."));
                }
            })
                /*
                    - isPasswordValid() : Vérifie si le mot de passe saisi correspond à celui en base.
                    - addError() : Ajoute une erreur au champ actualPassword si le mot de passe actuel est incorrect, affichant un message pour l’utilisateur.
                */
    ;
    }

    //! ** options du formulaire ** !//
    //* -> Methode utilisé pour definir les options du formulaire ( notament l'entité a laquelle il est lié)
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
                /*
                    - data_class: User::class : Associe le formulaire à l’entité User, ce qui permet à Symfony de lier automatiquement les champs aux propriétés de l’entité.
                */
            //? https://symfony.com/doc/current/security/passwords.html#hashing-the-password
            'passwordHasher' => null,
                /*
                    - passwordHasher: null : Définit une option passwordHasher par défaut à null, qui sera configurée pour le hachage des mots de passe si nécessaire.
                */
        ]);
    }
}

/*
!Explications supplémentaires :
    * Namespace et Imports :
        - namespace organise la classe PasswordUserType dans le dossier App\Form.
        - Les use importent des classes pour créer et configurer les champs, valider les données et gérer les événements de formulaire.

    * Méthode buildForm() :
        - Construit les champs du formulaire :
            - actualPassword : Champ de mot de passe pour vérifier le mot de passe actuel. Il est défini comme mapped => false car il ne sera pas automatiquement lié à l'entité User.
            - plainPassword : Champ de nouveau mot de passe. Utilise RepeatedType pour demander deux saisies identiques. Il est également non mappé, car le mot de passe sera haché avant de l'associer à l'utilisateur.
        - Bouton de soumission submit : Un bouton pour soumettre le formulaire, avec une classe CSS pour le style.
    
    * Gestionnaire d'événements (addEventListener) :
        - Vérification du mot de passe actuel : L'écouteur de l'événement FormEvents::SUBMIT utilise isPasswordValid() pour vérifier que le mot de passe actuel correspond à celui en base.
        - En cas d'erreur, un message est ajouté au champ actualPassword.

    * Méthode configureOptions() :
        - Associe le formulaire à l'entité User via data_class.
        - Définit l'option passwordHasher pour être utilisée lors de la vérification et du hachage du mot de passe.

    * Résumé
        La classe PasswordUserType crée un formulaire pour modifier le mot de passe d'un utilisateur. Elle vérifie le mot de passe actuel, demande un nouveau mot de passe et sa confirmation, puis applique des contraintes de longueur. Le formulaire n'est pas directement mappé à l'entité User pour le mot de passe afin de pouvoir hacher les données manuellement. Un message d'erreur est généré si le mot de passe actuel est incorrect.
*/