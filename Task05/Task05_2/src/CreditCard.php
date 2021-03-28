<?php

namespace App;

class CreditCard
{
    private int $cardNumber;
    private string $validity;

    public function __construct(string $cardNumber, string $validity)
    {
        $this -> cardNumber = $cardNumber;
        $this -> validity = $validity;
    }

    public function authorizeTransaction()
    {
        return "Authorization code:";
    }
}