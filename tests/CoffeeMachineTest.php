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
    public function itForwardsMessage()
    {
        $order = $this->makeOrder("M:Hello-World!");

        $this->assertEquals("Hello-World!", $order->getMessage());
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
    public function itMakesOrangeJuice()
    {
        $order = $this->makeOrder("O:0:0.6");

        $this->assertEquals(Drink::ORANGE_JUICE(), $order->getDrink());
    }

    /**
     * @test
     */
    public function itMakesExtraHotCoffeeWithNoSugar()
    {
        $order = $this->makeOrder("Ch:0:0.6");

        $this->assertEquals(Drink::COFFEE(), $order->getDrink());
        $this->assertEquals(true, $order->isExtraHot());
        $this->assertEquals(0, $order->getOrderedSugarNumber());
    }

    /**
     * @test
     */
    public function itMakesExtraHotChocolateWithOneSugarAndAStick()
    {
        $order = $this->makeOrder("Hh:1:0.5");

        $this->assertEquals(Drink::CHOCOLATE(), $order->getDrink());
        $this->assertEquals(true, $order->isExtraHot());
        $this->assertEquals(1, $order->getOrderedSugarNumber());
        $this->assertEquals(true, $order->isStickIncluded());
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function itThrowsExceptionWhenHotOrangeJuiceOrdered()
    {
        $this->makeOrder("Oh:0:0.7");
    }

    /**
     * @test
     */
    public function itMakesReportForOneTea()
    {
        $coffeeMachine = new CoffeeMachine();

        $coffeeMachine->make("T:0:0.4");

        $this->assertEquals(0.4, $coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("tea:1", $coffeeMachine->getRegistry()->getReport());
    }

    /**
     * @test
     */
    public function itMakesReportForTwoTeas()
    {
        $coffeeMachine = new CoffeeMachine();

        $coffeeMachine->make("T:0:0.4");
        $coffeeMachine->make("T:0:0.4");

        $this->assertEquals(0.8, $coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("tea:2", $coffeeMachine->getRegistry()->getReport());
    }

    /**
     * @test
     */
    public function itMakesReportForThreeTeasAndChocolateAndOrangeJuice()
    {
        $coffeeMachine = new CoffeeMachine();

        $coffeeMachine->make("T:0:0.4");
        $coffeeMachine->make("T:0:0.4");
        $coffeeMachine->make("H:0:0.5");
        $coffeeMachine->make("O:0:0.6");

        $this->assertEquals(1.9, $coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("tea:2", $coffeeMachine->getRegistry()->getReport());
        $this->assertContains("chocolate:1", $coffeeMachine->getRegistry()->getReport());
        $this->assertContains("orange_juice:1", $coffeeMachine->getRegistry()->getReport());
    }

    private function makeOrder(string $order) : Order
    {
        $coffeeMachine = new CoffeeMachine();

        return $coffeeMachine->make($order);
    }
}
