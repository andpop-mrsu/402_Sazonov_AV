<?php

namespace App;

use App\HotelRoom;

class Economy implements HotelRoom
{
    private static $price = 1000;
    private static $description = "Эконом";

    public function getDescription()
    {
        return "Класс: " . self::$description;
    }
    public function getPrice()
    {
        return self::$price;
    }
}