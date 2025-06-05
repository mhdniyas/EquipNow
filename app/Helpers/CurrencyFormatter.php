<?php

namespace App\Helpers;

class CurrencyFormatter
{
    public static function format($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}
