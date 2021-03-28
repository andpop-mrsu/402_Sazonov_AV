<?php

namespace App;

interface PaymentAdapterInterface
{
    public function collectMoney($amount);
}