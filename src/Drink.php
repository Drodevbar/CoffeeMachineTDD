<?php

namespace CoffeeMachine;

use MyCLabs\Enum\Enum;

/**
 * @method static Drink TEA()
 * @method static Drink COFFEE()
 * @method static Drink CHOCOLATE()
 * @method static Drink NO_DRINK()
 * @method static Drink ORANGE_JUICE()
 */
class Drink extends Enum
{
    private const TEA = "T";
    private const COFFEE = "C";
    private const CHOCOLATE = "H";
    private const ORANGE_JUICE = "O";
    private const NO_DRINK = "M";
}
