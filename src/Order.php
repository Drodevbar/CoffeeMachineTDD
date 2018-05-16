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
     * @var bool
     */
    private $extraHot;

    /**
     * @var string
     */
    private $message;

    public function __construct(Drink $drink, int $orderedSugarNumber = 0, bool $extraHot = false)
    {
        $this->drink = $drink;
        $this->orderedSugarNumber = $orderedSugarNumber;
        $this->extraHot = $extraHot;
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

    public function isExtraHot() : bool
    {
        return $this->extraHot;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function getMessage() : string
    {
        return $this->message;
    }
}
