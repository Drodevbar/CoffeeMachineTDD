<?php

namespace CoffeeMachine;

class Order
{
    /**
     * @var Drink
     */
    private $drink;

    /**
     * @var int
     */
    private $orderedSugarNumber;

    /**
     * @var string
     */
    private $message;

    public function __construct(Drink $drink, int $orderedSugarNumber = 0)
    {
        $this->drink = $drink;
        $this->orderedSugarNumber = $orderedSugarNumber;
    }

    public function getDrink() : Drink
    {
        return $this->drink;
    }

    public function getOrderedSugarNumber() : int
    {
        return $this->orderedSugarNumber;
    }

    public function isStickIncluded() : bool
    {
        return $this->getOrderedSugarNumber() > 0;
    }

    public function setMessage(string $message) : void
    {
        $this->message = $message;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}
