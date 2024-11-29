<?php
//? Formulaire pour l'adresse de livraison et le transporteur
/*
************************************************************
!           NAMESSPACE ET IMPORT DE CLASSES                *
************************************************************
*/

namespace App\Form;
    /*
        - Namespace : App\Form : Indique que cette classe est un formulaire situé dans le répertoire src/Form de l'application Symfony.
    */

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
    /*
        - Importations nécessaires :
            - Address et Carrier : Entités utilisées dans ce formulaire pour les champs basés sur les données des adresses et des transporteurs.
            - EntityType : Type de champ permettant de sélectionner des entités depuis la base de données.
            - AbstractType : Classe de base pour tous les formulaires Symfony.
            - SubmitType : Champ pour le bouton de validation.
            - FormBuilderInterface : Interface utilisée pour ajouter des champs au formulaire.
            - OptionsResolver : Permet de configurer des options pour le formulaire.
    */

/*
************************************************************
!                   IMPORTATION DE CLASS                   *
************************************************************
*/
class OrderType extends AbstractType
{

/*
************************************************************
!                   METHODES ET FONCTIONS                  *
************************************************************
*/  
    //! ** buildForm() ** !//
    //* Construction du formulaire pour valider l'adresse et le transporteur
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class,[
                'label' => "Choisissez votre adresse de livraison",
                'required' => true,
                'class' => Address::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'label_html' => true
            ])
            ->add('carriers', EntityType::class,[
                'label' => "Choisissez votre transporteur",
                'required' => true,
                'class' => Carrier::class,
                'expanded' => true,
                'label_html' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => 'w-100 btn btn-success'
                ]
            ])
        ;
    }
        /*
            addresses :
                - Type : EntityType.
                - 'class' => Address::class : Spécifie que ce champ est lié à l'entité Address.
                - 'choices' => $options['addresses'] : Définit les adresses disponibles pour ce champ en se basant sur l'option addresses passée lors de la création du formulaire.
                - 'expanded' => true : Affiche les adresses sous forme de boutons radio plutôt que dans un menu déroulant.
                - 'label_html' => true : Autorise l'affichage du HTML dans le label (utile pour un label enrichi, comme une mise en forme ou une image).
            
            carriers :
                Type : EntityType.
                'class' => Carrier::class : Spécifie que ce champ est lié à l'entité Carrier.
                'expanded' => true : Affiche les transporteurs sous forme de boutons radio.
                'label_html' => true : Autorise le HTML dans les labels pour les transporteurs.

            submit :
                Type : SubmitType.
                'label' => "Valider" : Texte affiché sur le bouton.
                'attr' => ['class' => 'w-100 btn btn-success'] : Ajoute des classes CSS au bouton : w-100 : Largeur de 100% (class Bootstrap).
                btn btn-success : Bouton vert stylé avec Bootstrap.
        */

    //! ** configureOptions ** !//
    //* Permet de passer des options au formulaire.
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
        /*
            - 'addresses' : Option personnalisée permettant de spécifier les adresses disponibles dans le champ addresses.
        */
}
/*
! Résumé
    * Le formulaire OrderType est conçu pour gérer la première étape du processus de commande :
        - Adresse de livraison : Sélectionnée parmi les adresses de l'utilisateur.
        - Transporteur : Sélectionné parmi les options de transport disponibles.
        - Validation : Le bouton "Valider" soumet le formulaire.

    *Ce formulaire est modulable grâce à l'option addresses qui permet de fournir dynamiquement les adresses liées à l'utilisateur connecté. Les champs sont affichés de manière ergonomique avec des boutons radio pour les adresses et les transporteurs.
*/
