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
        /*
            - public : Indique que cette méthode est accessible depuis l'extérieur de la classe.
            - function send() : Déclare une méthode nommée send, qui est responsable de l'envoi des emails.
            - ($to_email, $to_name, $subject, $template, $vars = null) :
                - $to_email : L'adresse email du destinataire.
                - $to_name : Le nom du destinataire.
                - $subject : L'objet de l'email.
                - $template : Le nom du fichier contenant le contenu HTML de l'email.
                - $vars = null : Un tableau facultatif contenant des variables dynamiques à insérer dans le template d'email.
        */
    {   
        //* -> 1. Recuperation du template
        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template);
            /*
                - file_get_contents() : Fonction PHP permettant de lire le contenu d'un fichier et de le retourner sous forme de chaîne de caractères.
                - dirname(__DIR__) : Permet d'obtenir le chemin du répertoire parent (un niveau au-dessus du répertoire actuel).
                - '/Mail/'.$template : Définit le chemin vers le fichier template situé dans le répertoire Mail, et dont le nom est passé via la variable $template.
            */

        //* -> 2. Recuperation des variables facultatives 
        if ($vars) {
            foreach($vars as $key=>$var) {
                $content = str_replace('{'.$key.'}', $var, $content);
            }
            /*
                - if ($vars) : Vérifie si des variables dynamiques ont été passées à la méthode.
                - foreach($vars as $key=>$var) : Parcourt le tableau $vars, où chaque clé ($key) correspond au nom d'une variable à remplacer, et chaque valeur ($var) est la valeur à insérer.
                - str_replace('{'.$key.'}', $var, $content) :
                    - str_replace() : Fonction PHP qui remplace toutes les occurrences d'une chaîne de caractères par une autre.
                    - '{'.$key.'}' : Correspond à la variable à chercher dans le template (par exemple {name}).
                    - $var : Valeur à insérer à la place de la variable.
                    - $content : Contenu du template modifié avec les variables dynamiques.
            */
        }

        //* -> 3. Creation d'un client Mailjet
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true,['version' => 'v3.1']);
            /*
                - new Client(...) : Instancie un nouvel objet de la classe Client pour se connecter à l'API Mailjet.
                - $_ENV['MJ_APIKEY_PUBLIC'] et $_ENV['MJ_APIKEY_PRIVATE'] : Ces variables environnementales contiennent respectivement la clé publique et la clé privée de l'API Mailjet.
                - true : Indique que la connexion doit être sécurisée (HTTPS).
                - ['version' => 'v3.1'] : Spécifie la version de l'API Mailjet utilisée (v3.1 dans ce cas)
            */
        
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
            /*
                - $body : Tableau PHP contenant toutes les informations nécessaires pour envoyer un email via l'API Mailjet.
                - Messages : Contient un tableau d'un ou plusieurs messages à envoyer.
                - From : Informations sur l'expéditeur de l'email :
                    - 'Email' : Adresse email de l'expéditeur.
                    - 'Name' : Nom de l'expéditeur.
                - To : Tableau contenant les informations du ou des destinataires.
                    - 'Email' : Adresse email du destinataire.
                    - 'Name' : Nom du destinataire.
                - "TemplateID" : Identifiant unique d'un template Mailjet déjà défini dans votre compte Mailjet.
                - "TemplateLanguage" : Indique que des variables dynamiques dans le template doivent être remplacées.
                - 'Subject' : Objet de l'email.
                - 'Variables' : Tableau contenant des variables dynamiques à injecter dans le template. Ici, on passe la variable content, qui contient le contenu HTML avec les variables remplacées.
            */

        //* -> 4. Construction du corps de l'email
        $mj->post(Resources::$Email, ['body' => $body]);
            /*
                - $mj->post(...) : Envoie une requête POST à l'API Mailjet.
                - Resources::$Email : Ressource de l'API Mailjet utilisée pour l'envoi d'emails.
                - ['body' => $body] : Corps de la requête, qui contient toutes les informations nécessaires pour l'email.
            */
    }
}