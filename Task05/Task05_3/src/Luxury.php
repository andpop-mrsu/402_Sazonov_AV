<?php

namespace App;

class Luxury implements HotelRoom
{
    private static $price = 3000;
    private static $description = "Люкс";

    public function getDescription()
    {
        return "Класс: " . self::$description;
    }

    public function getPrice()
    {
        return self::$price;
    }
}