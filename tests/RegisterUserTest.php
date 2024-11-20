<?php
//? https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework
//! composer require --dev symfony/test-pack :  Installe les outils de test de Symfony.
    //! symfony console make:test : Génère un fichier de test.
        //! WebTestCase : Car on va lancer un comportement comme un navigateur pour analyser les comportement sans besoin de JS
            //! php bin/phpunit : Exécute tous les tests définis dans le projet et affiche les résultats.
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    /*
        * 1- Créer un faux client (navigateur) de point vers une URL
        * 2- Remplir les champs de mon formulaire d'inscription
        * 3- Est ce que tu peux regarrder si dans ma page j'ai le message (alerte) suivant : Votre compte à bien été créé. Veuillez vous connecter
    */

    public function testSomething(): void
    {
        // 1-
        $client = static::createClient();
            // -> ceci nous permet d'emuler le comportement d'un navigateur (c'est le client)
        $client->request('GET', '/inscription');

        // 2- (firstname, lastname, email, password,)
        $client->submitForm( 'Valider',[
            'register_user[email]' => 'julie@example.fr',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]'=> '123456',
            'register_user[firstname]' => 'Julie',
            'register_user[lastname]' => 'Doe',
        ]);

        // 2.5 Follow : Suivre la redirection et la tester
            $this->assertResponseRedirects();
            $client->followRedirect();
        // 3-
        $this->assertSelectorExists('div:contains("Votre compte à bien été créé. Veuillez vous connecter")');
    }
}
