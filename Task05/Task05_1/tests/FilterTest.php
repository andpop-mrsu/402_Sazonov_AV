<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Product;
use App\ProductCollection;
use App\ManufacturerFilter;
use App\MaxPriceFilter;

class FilterTest extends TestCase
{
    public function testFilter()
    {
        $p1 = new Product();
        $p1->name = 'Laptop';
        $p1->price = 47999;
        $p1->discount = 44999;
        $p1->manufacturer = 'Apple';

        $p2 = new Product();
        $p2->name = 'Phone';
        $p2->price = 39999;
        $p2->manufacturer = 'Samsung';

        $p3 = new Product();
        $p3->name = 'Computer';
        $p3->price = 75999;
        $p3->manufacturer = 'DNS';


        $collection = new ProductCollection([$p1, $p2, $p3]);
        $resultCollection = $collection->filter(new MaxPriceFilter(40000));
        $this -> assertSame(2, count($resultCollection -> getProductsArray()));
        $resultCollection = $collection->filter(new ManufacturerFilter('Apple'));
        $this -> assertSame(1, count($resultCollection -> getProductsArray()));
        $this -> assertSame('Laptop', $resultCollection -> getProductsArray()[0] -> name);
    }
}