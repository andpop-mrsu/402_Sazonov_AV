<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\PayPal;
use App\CreditCard;
use App\PayPalAdapter;
use App\CreditCardAdapter;

class PayTest extends TestCase
{
    public function testAdapters()
    {
        $paypal = new PayPal('newClient@mail.ru', 'password');
        $cc = new CreditCard(4817911234567890, "01/23");
        $paypalAdapter = new PayPalAdapter($paypal);
        $ccAdapter = new CreditCardAdapter($cc);
        $this -> assertSame("Authorization code:", $ccAdapter -> collectMoney(15300));
        $this -> assertSame("PayPal Success!", $paypalAdapter -> collectMoney(12900));
    }
}