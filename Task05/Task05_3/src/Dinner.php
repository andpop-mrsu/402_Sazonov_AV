<?php

namespace App;

use App\HotelRoom;
use App\AdditionalServices;

class Dinner extends AdditionalServices
{
    private static $price = 800;

    public function getDescription()
    {
        return $this -> hotelRoom -> getDescription() . ", ужин";
    }

    public function getPrice()
    {
        return $this -> hotelRoom -> getPrice() + self::$price;
    }
}