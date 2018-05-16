<?php

namespace CoffeeMachine\Tests;

use CoffeeMachine\CoffeeMachine;
use CoffeeMachine\Drink;
use CoffeeMachine\Order;
use PHPUnit\Framework\TestCase;

class CoffeeMachineTest extends TestCase
{
    /**
     * @test
     */
    public function itMakesTeaWithOneSugarAndAStick()
    {
        $order = $this->makeOrder("T:1:0.4");

        $this->assertEquals(Drink::TEA(), $order->getDrink());
        $this->assertEquals(1, $order->getOrderedSugarNumber());
        $this->assertEquals(true, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesChocolateWithNoSugarAndNoStick()
    {
        $order = $this->makeOrder("H::0.5");

        $this->assertEquals(Drink::CHOCOLATE(), $order->getDrink());
        $this->assertEquals(0, $order->getOrderedSugarNumber());
        $this->assertEquals(false, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesCoffeeWithTwoSugarsAndAStick()
    {
        $order = $this->makeOrder("C:2:0.6");

        $this->assertEquals(Drink::COFFEE(), $order->getDrink());
        $this->assertEquals(2, $order->getOrderedSugarNumber());
        $this->assertEquals(true, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itDoesNotMakeDrinkForNotEnoughMoneyInserted()
    {
        $order = $this->makeOrder("C:0:0.2");

        $this->assertEquals(Drink::NO_DRINK(), $order->getDrink());
        $this->assertContains("0.4", $order->getMessage());
    }

    /**
     * @test
     */
    public function itForwardsMessage()
    {
        $order = $this->makeOrder("M:Hello-World!");

        $this->assertEquals("Hello-World!", $order->getMessage());
    }

    private function makeOrder(string $order) : Order
    {
        $coffeeMachine = new CoffeeMachine();

        return $coffeeMachine->make($order);
    }
}
