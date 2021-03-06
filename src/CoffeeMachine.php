<?php

namespace CoffeeMachine;

use CoffeeMachine\Services\BeverageQuantityChecker;
use CoffeeMachine\Services\EmailNotifier;

class CoffeeMachine
{
    /**
     * @var BeverageQuantityChecker
     */
    private $beverageQuantityChecker;

    /**
     * @var EmailNotifier
     */
    private $emailNotifier;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Cashier
     */
    private $cashier;

    /**
     * @var Drink
     */
    private $drink;

    /**
     * @var int
     */
    private $orderedSugarNumber;

    /**
     * @var float
     */
    private $moneyInserted;

    /**
     * @var bool
     */
    private $extraHotDrinkOrdered;

    public function __construct(BeverageQuantityChecker $checker, EmailNotifier $notifier)
    {
        $this->registry = new Registry();
        $this->beverageQuantityChecker = $checker;
        $this->emailNotifier = $notifier;
        $this->extraHotDrinkOrdered = false;
    }

    public function make(string $input) : Order
    {
        $orderParts = explode(":", $input);

        $this->makeDrink($orderParts[0]);

        if ($this->drink->equals(Drink::NO_DRINK())) {
            return $this->getOrderWithoutDrinkWithMessage($orderParts[1]);
        }

        $this->initialize($orderParts);

        return $this->makeOrder();
    }

    public function getRegistry() : Registry
    {
        return $this->registry;
    }

    private function makeDrink(string $drinkType) : void
    {
        $this->drink = new Drink($drinkType[0]);

        if ($this->isDrinkExtraHot($drinkType)) {
            if ($this->drink->equals(Drink::ORANGE_JUICE())) {
                throw new \LogicException("An orange juice can not be hot");
            }
            $this->extraHotDrinkOrdered = true;
        }
    }

    private function isDrinkExtraHot(string $drinkType) : bool
    {
        return (isset($drinkType[1]) && $drinkType[1] === 'h');
    }

    private function getOrderWithoutDrinkWithMessage(string $message) : Order
    {
        $order = new Order(Drink::NO_DRINK());
        $order->setMessage($message);

        return $order;
    }

    private function initialize(array $orderParts) : void
    {
        $this->orderedSugarNumber = $this->getSugarNumber($orderParts[1]);
        $this->moneyInserted = (float) $orderParts[2];
        $this->cashier = new Cashier($this->drink, $this->moneyInserted);
    }

    private function getSugarNumber(string $number) : int
    {
        return ((int) $number > 0) ? $number : 0;
    }

    private function makeOrder() : Order
    {
        if ($this->beverageQuantityChecker->isEmpty($this->drink)) {
            $this->emailNotifier->notifyMissingDrink($this->drink);

            return $this->getOrderWithoutDrinkWithMessage("Out of resources");
        }
        if ($this->cashier->isEnoughMoney()) {
            $this->registry->addToRegistry($this->drink, $this->cashier);

            return new Order($this->drink, $this->orderedSugarNumber, $this->extraHotDrinkOrdered);
        }
        return $this->getOrderWithoutDrinkWithMessage($this->cashier->getMissingMoneyMessage());
    }
}
