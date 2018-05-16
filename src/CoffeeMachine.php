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

    public function make(string $input) : Order
    {
        $orderParts = explode(":", $input);

        $this->drink = new Drink($orderParts[0]);

        if ($this->drink->equals(Drink::NO_DRINK())) {
            return $this->getOrderForNoDrink($orderParts[1]);
        }

        $this->initialize($orderParts);

        return $this->getValidatedOrder();
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
            return new Order($this->drink, $this->orderedSugarNumber);
        }
        $order = new Order(Drink::NO_DRINK());
        $order->setMessage($this->cashier->getMissingMoneyMessage());

        return $order;
    }
}
