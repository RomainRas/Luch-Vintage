<?php
//? https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework
//! composer require --dev symfony/test-pack :  Installe les outils de test de Symfony.
    //! symfony console make:test : Génère un fichier de test.
        //! TestCase : Classe de base fournie par PHPUnit, utilisée pour créer des classes de test, fournit des méthodes comme `assertTrue`, `assertEquals`, etc., pour vérifier que le code fonctionne comme prévu.
            //! php bin/phpunit : Exécute tous les tests définis dans le projet et affiche les résultats.
// namespace App\Tests;

// use PHPUnit\Framework\TestCase;
//     // Importation de la classe TestCase depuis PHPUnit, un framework de tests pour PHP.

// class ExempleTest extends TestCase
//     // ExempleTest : Nom de la classe de test. Les noms de classes de test finissent souvent par Test pour identifier facilement les classes destinées aux tests.
//     // extends TestCase : Héritage de la classe TestCase de PHPUnit. Cela permet d'utiliser les méthodes de PHPUnit, comme assertTrue, pour réaliser les assertions dans les tests.
// {
//     public function testSomething(): void
//         // testSomething : Nom de la méthode de test. En PHPUnit, il est conventionnel de commencer les méthodes de test par test pour indiquer qu'il s'agit de tests.
//         // : void : Indique que la méthode ne retourne aucune valeur.

//     {
//         $param = true;
//             // $param : Variable qui contient une valeur à tester.

//         $this->assertTrue($param);
//             // $this : Fait référence à l'instance actuelle de la classe ExempleTest.
//             // assertTrue : Méthode fournie par TestCase qui vérifie si la valeur de $param est true.
//                 // Assertion : Dans un test, une assertion est utilisée pour vérifier que le code fonctionne comme attendu.
//             // $param : Valeur testée par assertTrue. Si $param est bien true, le test réussit ; sinon, il échoue.
//     }
// }
