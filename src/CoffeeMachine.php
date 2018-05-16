<?php

namespace CoffeeMachine;

class CoffeeMachine
{
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

    public function __construct()
    {
        $this->extraHotDrinkOrdered = false;
    }

    public function make(string $input) : Order
    {
        $orderParts = explode(":", $input);

        $this->makeDrink($orderParts[0]);

        if ($this->drink->equals(Drink::NO_DRINK())) {
            return $this->getOrderForNoDrink($orderParts[1]);
        }

        $this->initialize($orderParts);

        return $this->getValidatedOrder();
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

    private function getOrderForNoDrink(string $message) : Order
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
        return ((int)$number > 0) ? $number : 0;
    }

    private function getValidatedOrder() : Order
    {
        if ($this->cashier->isEnoughMoney()) {
            return new Order($this->drink, $this->orderedSugarNumber, $this->extraHotDrinkOrdered);
        }
        $order = new Order(Drink::NO_DRINK());
        $order->setMessage($this->cashier->getMissingMoneyMessage());

        return $order;
    }
}
