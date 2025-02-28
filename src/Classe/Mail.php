<?php
//! Cette classe PHP permet d'envoyer des emails personnalisés via l'API Mailjet en utilisant des templates.
/*
*- Lecture du template HTML.
*- Remplacement des variables dynamiques dans le template.
*- Création d'un client Mailjet avec les clés API.
*- Construction du corps de l'email avec les informations nécessaires (expéditeur, destinataire, objet, contenu, etc.).
*- Envoi de l'email via une requête POST à l'API Mailjet.
*/
/*
************************************************************
!           NAMESPACE ET IMPORT DES CLASSES                *
************************************************************
*/
namespace App\Classe;
    /*
        namespace : Définit l'espace de noms pour organiser les classes et éviter les conflits entre classes ayant le même nom dans différentes parties du projet. Ici, la classe Mail appartient à l'espace de noms App\Classe.
    */
use Mailjet\Client;
use Mailjet\Resources;
    /*
        use Mailjet\Client : Indique que la classe Client de la bibliothèque Mailjet est utilisée dans ce script. Cette classe permet d'établir une connexion avec l'API Mailjet.
        use Mailjet\Resources : Permet d'utiliser les constantes définies par Mailjet, comme Resources::$Email, qui correspond à l'envoi d'emails via leur API.
    */
/*
************************************************************
!                 DECLARATION DE CLASSE                    *
************************************************************
*/
class Mail
    /*
        class : Permet de définir une classe en PHP, qui est une structure contenant des propriétés (variables) et des méthodes (fonctions). La classe Mail encapsule toute la logique pour envoyer des emails.
    */
{
/*
************************************************************
!                   METHODES ET R0UTES                     *
************************************************************
*/
    public function send($to_email, $to_name, $subject, $template, $vars = null)
    {   
        //* -> 1. Recuperation du template
        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);

        //* -> 2. Recuperation des variables facultatives 
        if ($vars) {
            foreach($vars as $key=>$var) {
                $content = str_replace('{'.$key.'}', $var, $content);
            }
        }

        //* -> 3. Creation d'un client Mailjet
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true,['version' => 'v3.1']);
        
        //* -> 4. Construction du corps de l'email
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "rascle.romain@outlook.fr",
                        'Name' => "Luch Vintage"
                    ],
                    'To' => [
                        [
                            'Email' => "$to_email",
                            'Name' => "$to_name"
                        ]
                    ],
                    "TemplateID" => 6625692,
                    "TemplateLanguage" => true,
                    'Subject' => "$subject",
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];

        //* -> 4. Construction du corps de l'email
        $mj->post(Resources::$Email, ['body' => $body]);
            /*
                - $mj->post(...) : Envoie une requête POST à l'API Mailjet.
                - Resources::$Email : Ressource de l'API Mailjet utilisée pour l'envoi d'emails.
                - ['body' => $body] : Corps de la requête, qui contient toutes les informations nécessaires pour l'email.
            */
    }
}