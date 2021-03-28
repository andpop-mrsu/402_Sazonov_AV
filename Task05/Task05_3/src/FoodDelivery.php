<?php

namespace App;

use App\HotelRoom;
use App\AdditionalServices;

class FoodDelivery extends AdditionalServices
{
    private static $price = 300;

    public function getDescription()
    {
        return $this -> hotelRoom -> getDescription() . ", доставка еды в номер";
    }

    public function getPrice()
    {
        return $this -> hotelRoom -> getPrice() + self::$price;
    }
}