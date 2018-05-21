<?php

namespace CoffeeMachine\Services;

use CoffeeMachine\Drink;

interface BeverageQuantityChecker
{
    public function isEmpty(Drink $drink) : bool;
}
