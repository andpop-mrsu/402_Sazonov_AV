<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Economy;
use App\Standard;
use App\Luxury;
use App\DedicatedInternet;
use App\AdditionalSofa;
use App\FoodDelivery;
use App\Dinner;
use App\BreakfastBuffet;

class DecoratorTest extends TestCase
{
    public function testDecorator()
    {
        $hotelRoom1 = new Luxury();
        $hotelRoom1 = new AdditionalSofa($hotelRoom1);
        $hotelRoom1 = new DedicatedInternet($hotelRoom1);
        $hotelRoom1 = new Dinner($hotelRoom1);
        $hotelRoom1 = new FoodDelivery($hotelRoom1);
        $this -> assertSame(
            "Класс: Люкс, дополнительный диван, выделенный Интернет, ужин, доставка еды в номер",
            $hotelRoom1 -> getDescription()
        );
        $this -> assertSame(4700, $hotelRoom1 -> getPrice());
        $hotelRoom2 = new Economy();
        $this -> assertSame("Класс: Эконом", $hotelRoom2 -> getDescription());
        $this -> assertSame(1000, $hotelRoom2 -> getPrice());
    }
}