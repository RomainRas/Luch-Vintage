<?php
//? Formulaire pour ajouter l'adresse de livraison d'un utilisateur
/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Form;
    /*
        - namespace : Définit l’espace de noms du formulaire. Ici, App\Form signifie que cette classe appartient au dossier Form de l'application.
    */
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
    /*
        - App\Entity\User et App\Entity\Address : Entités pour manipuler les données liées au formulaire.
        - AbstractType : Classe de base pour tous les formulaires Symfony.
        - FormBuilderInterface : Permet de construire les champs du formulaire.
        - OptionsResolver : Configure les options du formulaire, comme l’entité associée.
        - Types de champs :
            - TextType : Champ pour des textes simples.
            - SubmitType : Bouton de soumission.
            - CountryType : Champ pour sélectionner un pays dans une liste.
    */


/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
//* -> Cette classe définit un formulaire pour gérer les adresses des utilisateurs.
class AddressUserType extends AbstractType
{



/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    //* -> Construit les champs du formulaire.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
            -buildForm() : Méthode principale pour ajouter des champs au formulaire.
            - Paramètres :
                - $builder : Instance de FormBuilderInterface pour ajouter des champs au formulaire.
                - array $options : Tableau contenant des options supplémentaires configurées via configureOptions.
        */
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Indiquer votre prénom ...'
                ]
            ])
            /*
                - TextType::class : Champ de texte simple.
                - label : Texte affiché à côté ou au-dessus du champ.
                - attr : Attributs HTML supplémentaires. Ici, placeholder affiche une indication dans le champ.
            */
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Indiquer votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Indiquer votre adresse'
                ]
            ] )
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Indiquer votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Indiquer votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays'
            ])
                /*
                    - CountryType::class : Type de champ qui affiche une liste déroulante de tous les pays. Gère automatiquement les codes ISO des pays.
                */
            ->add('phone', TextType::class, [
                'label' => 'Votre téléphone ',
                'attr' => [
                    'placeholder' => 'Indiquer votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Sauvegarder", 
                'attr' => [
                    'class' => 'btn btn-success'
                ]
                /*
                    - SubmitType::class : Bouton pour soumettre le formulaire.
                    - attr : Ajoute une classe CSS (btn btn-success) pour styliser le bouton avec Bootstrap.
                */
            ])
        ;
    }

    //* -> Configure les options globales du formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
        /*
            
            - configureOptions() : Associe le formulaire à l’entité Address.
            - Paramètres :
                - $resolver : Instance d’OptionsResolver pour configurer les options.
            - data_class : Lie le formulaire à l'entité Address. Lorsque le formulaire est soumis, Symfony hydrate automatiquement l'objet Address avec les données du formulaire.
        */
}
/*
!Résumé des fonctionnalités
* Champs de formulaire :
    - Gère les informations personnelles (prénom, nom).
    - Capture les coordonnées (adresse, ville, code postal, pays, téléphone).
    - Bouton de soumission stylisé avec Bootstrap.

* Lien avec l'entité Address :
    - Grâce à l'option data_class, Symfony associe directement les données du formulaire aux propriétés de l'entité.

*Flexibilité et réutilisabilité :
    - Ce formulaire peut être utilisé dans plusieurs contextes : création ou modification d'adresses, gestion des adresses dans le compte utilisateur.
    - Ce formulaire est conçu pour collecter et gérer les informations d'une adresse utilisateur de manière efficace et structurée.
*/
