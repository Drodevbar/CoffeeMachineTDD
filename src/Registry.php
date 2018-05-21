<?php

namespace CoffeeMachine;

class Registry
{
    /**
     * @var float
     */
    private $balance;

    /**
     * @var array
     */
    private $report;

    public function __construct()
    {
        $this->balance = 0.0;
        $this->report = [
            Drink::TEA()->getValue() => 0,
            Drink::COFFEE()->getValue() => 0,
            Drink::CHOCOLATE()->getValue() => 0,
            Drink::ORANGE_JUICE()->getValue() => 0
        ];
    }

    public function addToRegistry(Drink $drink, Cashier $cashier)
    {
        $this->report[$drink->getValue()]++;

        $this->balance += $cashier->getPriceForDrink($drink);
    }

    public function getReport() : string
    {
        $message = "";

        foreach ($this->report as $type => $amount) {
            $message .= "{$type}:{$amount}";
        }

        return $message;
    }

    public function getBalance() : float
    {
        return $this->balance;
    }
}
