<?php

namespace CoffeeMachine\Services;

use CoffeeMachine\Drink;

interface EmailNotifier
{
    public function notifyMissingDrink(Drink $drink) : void;
}
