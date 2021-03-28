<?php

namespace App;

use App\HotelRoom;
use App\AdditionalServices;

class DedicatedInternet extends AdditionalServices
{
    private static $price = 100;

    public function getDescription()
    {
        return $this -> hotelRoom -> getDescription() . ", выделенный Интернет";
    }

    public function getPrice()
    {
        return $this -> hotelRoom -> getPrice() + self::$price;
    }
}