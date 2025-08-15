<?php

namespace App\Tests;

use App\Classe\Cart;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends TestCase
{
    public function testFullQuantityWithMultipleProducts(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $cart = new Cart($requestStack);

        $product1 = new class {
            public function getId() { return 1; }
        };
        $product2 = new class {
            public function getId() { return 2; }
        };

        $cart->add($product1);
        $cart->add($product2);
        $cart->add($product2);

        $this->assertSame(3, $cart->fullQuantity());
    }
}
