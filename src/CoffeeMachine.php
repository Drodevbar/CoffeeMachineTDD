<?php

namespace CoffeeMachine;

class CoffeeMachine
{
    public function make(string $order) : Order
    {
        $orderParts = explode(":", $order);
        $drink = new Drink($orderParts[0]);

        if ($drink->equals(Drink::NO_DRINK())) {
            return $this->buildEmptyOrder($orderParts[1]);
        }

        $orderedSugarNumber = $this->getSugarNumber($orderParts[1]);

        return new Order($drink, $orderedSugarNumber);
    }

    private function buildEmptyOrder(string $message) : Order
    {
        $order = new Order(Drink::NO_DRINK());
        $order->setMessage($message);

        return $order;
    }

    private function getSugarNumber(string $number) : int
    {
        return ((int)$number > 0) ? $number : 0;
    }
}
