<?php

namespace App;

use App\PaymentAdapterInterface;
use App\CreditCard;

class CreditCardAdapter implements PaymentAdapterInterface
{
    private $adaptee;

    public function __construct(CreditCard $adaptee)
    {
        $this -> adaptee = $adaptee;
    }

    public function collectMoney($amount)
    {
        return $this -> adaptee -> authorizeTransaction();
    }
}