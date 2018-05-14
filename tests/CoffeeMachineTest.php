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
        $tea = $this->makeOrder("T:1:0");

        $this->assertEquals(Drink::TEA(), $tea->getDrink());
        $this->assertEquals(1, $tea->getOrderedSugarNumber());
        $this->assertEquals(true, $tea->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesChocolateWithNoSugarAndNoStick()
    {
        $chocolate = $this->makeOrder("H::");

        $this->assertEquals(Drink::CHOCOLATE(), $chocolate->getDrink());
        $this->assertEquals(0, $chocolate->getOrderedSugarNumber());
        $this->assertEquals(false, $chocolate->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesCoffeeWithTwoSugarsAndAStick()
    {
        $coffee = $this->makeOrder("C:2:0");

        $this->assertEquals(Drink::COFFEE(), $coffee->getDrink());
        $this->assertEquals(2, $coffee->getOrderedSugarNumber());
        $this->assertEquals(true, $coffee->isStickIncluded());
    }

    /**
     * @test
     */
    public function itForwardsMessage()
    {
        $message = $this->makeOrder("M:Hello-World!");

        $this->assertEquals("Hello-World!", $message->getMessage());
    }

    private function makeOrder(string $order) : Order
    {
        $coffeeMachine = new CoffeeMachine();

        return $coffeeMachine->make($order);
    }
}
