<?php

namespace App;

abstract class AdditionalServices implements HotelRoom
{
    protected $hotelRoom;

    public function __construct(HotelRoom $hotelRoom)
    {
        $this -> hotelRoom = $hotelRoom;
    }

    abstract public function getDescription();
    abstract public function getPrice();
}