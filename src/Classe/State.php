<?php
//! Class State pour gérer les différents etats d'une commande pour l'envoi de mail, grace à une constate (STATE)
namespace App\Classe;

class State
{
    public const STATE = [
        '3' => [
            'label' => 'En cours de préparation',
            'email_subject' => 'Commande en cours de préparation',
            'email_template' => 'order_state_3.html',
        ],
        '4' => [
            'label' => 'Expédiée',
            'email_subject' => 'Commande expédiée',
            'email_template' => 'order_state_4.html',
        ],
        '5' => [
            'label' => 'Annulée',
            'email_subject' => 'Commande annulée',
            'email_template' => 'order_state_5.html',
        ],
        ];
            /*
                public const :
                    - public : Rendre la constante accessible partout où la classe State est utilisée.
                    - const : Déclare une constante en PHP. Contrairement à une variable, une constante est immuable (sa valeur ne peut pas changer après sa déclaration).
                    - STATE : Nom de la constante, utilisé pour stocker les données relatives aux états.
            */
}
//! Structure de la constate (STATE)
/*
* La constante STATE est un tableau associatif qui contient plusieurs états. Chaque état est défini par une clé (les numéros 3, 4 et 5) et une valeur associée, qui est elle-même un tableau contenant les informations suivantes :

    - Clé : '3', '4', '5' : Ces clés représentent les différents états possibles d'une commande. Chaque clé est une chaîne de caractères correspondant à un identifiant d'état.

    - Valeurs associées : Chaque état est un tableau contenant trois entrées :
        - 'label' :
            - Décrit l'état sous une forme lisible par un utilisateur.
            - Par exemple, 'En cours de préparation' pour l'état '3'.
        - 'email_subject' :
            - Contient le sujet de l'email qui sera envoyé à l'utilisateur lorsque cet état sera atteint.
            - Par exemple, 'Commande en cours de préparation' pour l'état '3'.
        -'email_template' :
            - Indique le nom du fichier contenant le template HTML de l'email qui sera utilisé pour notifier cet état.
            - Par exemple, 'order_state_3.html' pour l'état '3'.
*/