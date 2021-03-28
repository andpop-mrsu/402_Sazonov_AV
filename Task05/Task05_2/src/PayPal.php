<?php

namespace App;

class PayPal
{
    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this -> login = $login;
        $this -> password = $password;
    }

    public function transfer()
    {
        return "PayPal Success!";
    }
}