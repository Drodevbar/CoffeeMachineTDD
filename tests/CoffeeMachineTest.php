<?php

namespace CoffeeMachine\Tests;

use CoffeeMachine\CoffeeMachine;
use CoffeeMachine\Drink;
use CoffeeMachine\Services\BeverageQuantityChecker;
use CoffeeMachine\Services\EmailNotifier;
use Mockery;
use PHPUnit\Framework\TestCase;

class CoffeeMachineTest extends TestCase
{
    /**
     * @var CoffeeMachine
     */
    private $coffeeMachine;

    public function setUp()
    {
        $beverageQuantityChecker = Mockery::mock(BeverageQuantityChecker::class);
        $beverageQuantityChecker
            ->shouldReceive("isEmpty")
            ->once()
            ->andReturnFalse();

        $emailNotifier = Mockery::mock(EmailNotifier::class);
        $emailNotifier
            ->shouldReceive("notifyMissingDrink")
            ->never();

        $this->coffeeMachine = new CoffeeMachine($beverageQuantityChecker, $emailNotifier);
    }

    /**
     * @test
     */
    public function itMakesTeaWithOneSugarAndAStick()
    {
        $order = $this->coffeeMachine->make("T:1:0.4");

        $this->assertEquals(Drink::TEA(), $order->getDrink());
        $this->assertEquals(1, $order->getOrderedSugarNumber());
        $this->assertEquals(true, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesChocolateWithNoSugarAndNoStick()
    {
        $order = $this->coffeeMachine->make("H::0.5");

        $this->assertEquals(Drink::CHOCOLATE(), $order->getDrink());
        $this->assertEquals(0, $order->getOrderedSugarNumber());
        $this->assertEquals(false, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itMakesCoffeeWithTwoSugarsAndAStick()
    {
        $order = $this->coffeeMachine->make("C:2:0.6");

        $this->assertEquals(Drink::COFFEE(), $order->getDrink());
        $this->assertEquals(2, $order->getOrderedSugarNumber());
        $this->assertEquals(true, $order->isStickIncluded());
    }

    /**
     * @test
     */
    public function itForwardsMessage()
    {
        $order = $this->coffeeMachine->make("M:Hello-World!");

        $this->assertEquals("Hello-World!", $order->getMessage());
    }

    /**
     * @test
     */
    public function itDoesNotMakeDrinkForNotEnoughMoneyInserted()
    {
        $order = $this->coffeeMachine->make("C:0:0.2");

        $this->assertEquals(Drink::NO_DRINK(), $order->getDrink());
        $this->assertContains("0.4", $order->getMessage());
    }

    /**
     * @test
     */
    public function itMakesOrangeJuice()
    {
        $order = $this->coffeeMachine->make("O:0:0.6");

        $this->assertEquals(Drink::ORANGE_JUICE(), $order->getDrink());
    }

    /**
     * @test
     */
    public function itMakesExtraHotCoffeeWithNoSugar()
    {
        $order = $this->coffeeMachine->make("Ch:0:0.6");

        $this->assertEquals(Drink::COFFEE(), $order->getDrink());
        $this->assertEquals(true, $order->isExtraHot());
        $this->assertEquals(0, $order->getOrderedSugarNumber());
    }

    /**
     * @test
     */
    public function itMakesExtraHotChocolateWithOneSugarAndAStick()
    {
        $order = $this->coffeeMachine->make("Hh:1:0.5");

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
        $this->coffeeMachine->make("Oh:0:0.7");
    }

    /**
     * @test
     */
    public function itMakesReportForOneTea()
    {
        $this->coffeeMachine->make("T:0:0.4");

        $this->assertEquals(0.4, $this->coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("T:1", $this->coffeeMachine->getRegistry()->getReport());
    }

    /**
     * @test
     */
    public function itMakesReportForTwoTeas()
    {
        $this->coffeeMachine->make("T:0:0.4");
        $this->coffeeMachine->make("T:0:0.4");

        $this->assertEquals(0.8, $this->coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("T:2", $this->coffeeMachine->getRegistry()->getReport());
    }

    /**
     * @test
     */
    public function itMakesReportForThreeTeasAndChocolateAndOrangeJuice()
    {
        $this->coffeeMachine->make("T:0:0.4");
        $this->coffeeMachine->make("T:0:0.4");
        $this->coffeeMachine->make("H:0:0.5");
        $this->coffeeMachine->make("O:0:0.6");

        $this->assertEquals(1.9, $this->coffeeMachine->getRegistry()->getBalance());
        $this->assertContains("T:2", $this->coffeeMachine->getRegistry()->getReport());
        $this->assertContains("H:1", $this->coffeeMachine->getRegistry()->getReport());
        $this->assertContains("O:1", $this->coffeeMachine->getRegistry()->getReport());
    }

    /**
     * @test
     */
    public function itIndicatesTheShortageForTeaAndSendsEmailNotificationToTheOwner()
    {
        $beverageQuantityChecker = Mockery::mock(BeverageQuantityChecker::class);
        $beverageQuantityChecker
            ->shouldReceive("isEmpty")
            ->with(Drink::TEA())
            ->once()
            ->andReturnTrue();

        $emailNotifier = Mockery::mock(EmailNotifier::class);
        $emailNotifier
            ->shouldReceive("notifyMissingDrink")
            ->with(Drink::TEA())
            ->once();

        $outOfResourcesCoffeeMachine = new CoffeeMachine($beverageQuantityChecker, $emailNotifier);

        $order = $outOfResourcesCoffeeMachine->make("T:0:1");

        $this->assertEquals(Drink::NO_DRINK(), $order->getDrink());
        $this->assertEquals("Out of resources", $order->getMessage());
    }
}
