<?php

namespace App;

use App\ProductFilteringStrategy;

class ProductCollection
{
    private $collection = array();

    public function __construct($collection)
    {
        $this -> collection = $collection;
    }

    public function getProductsArray()
    {
        return $this -> collection;
    }

    public function filter(ProductFilteringStrategy $filterStrategy): ProductCollection
    {
        $filtrationResult = $filterStrategy -> filter($this -> collection);
        return new ProductCollection($filtrationResult);
    }
}