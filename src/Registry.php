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
            'tea' => 0,
            'coffee' => 0,
            'chocolate' => 0,
            'orange_juice' => 0
        ];
    }

    public function addToRegistry(Drink $drink, Cashier $cashier)
    {
        switch ($drink) {
            case Drink::TEA():
                $this->report['tea']++;
                break;
            case Drink::COFFEE():
                $this->report['coffee']++;
                break;
            case Drink::CHOCOLATE():
                $this->report['chocolate']++;
                break;
            case Drink::ORANGE_JUICE():
                $this->report['orange_juice']++;
                break;
        }

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
