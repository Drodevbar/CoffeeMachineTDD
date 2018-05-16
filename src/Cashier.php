<?php

namespace CoffeeMachine;

final class Cashier
{
    private const TEA_PRICE = 0.4;
    private const COFFEE_PRICE = 0.6;
    private const CHOCOLATE_PRICE = 0.5;
    private const ORANGE_JUICE_PRICE = 0.6;

    /**
     * @var Drink
     */
    private $drink;

    /**
     * @var float
     */
    private $money;

    /**
     * @var float
     */
    private $orderedDrinkPrice;

    public function __construct(Drink $drink, float $money)
    {
        $this->drink = $drink;
        $this->money = $money;
        $this->orderedDrinkPrice = $this->getDrinkPrice();
    }

    public function isEnoughMoney() : bool
    {
        return ($this->money >= $this->orderedDrinkPrice);
    }

    public function getMissingMoneyMessage() : string
    {
        $missingMoney = $this->orderedDrinkPrice - $this->money;

        if ($missingMoney > 0) {
            return "Missing money: {$missingMoney}";
        }
        throw new \LogicException("No money is missing");
    }

    private function getDrinkPrice() : float
    {
        switch ($this->drink) {
            case Drink::TEA():
                return self::TEA_PRICE;
            case Drink::COFFEE():
                return self::COFFEE_PRICE;
            case Drink::CHOCOLATE():
                return self::CHOCOLATE_PRICE;
            case Drink::ORANGE_JUICE():
                return self::ORANGE_JUICE_PRICE;
        }
    }
}
