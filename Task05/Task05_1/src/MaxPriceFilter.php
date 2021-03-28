<?php

namespace App;

class MaxPriceFilter implements ProductFilteringStrategy
{
    private int $maxPrice;

    public function __construct(int $maxPrice)
    {
        $this -> maxPrice = $maxPrice;
    }

    public function filter(array $collection)
    {
        $result = array();
        $price;
        foreach ($collection as $elem) {
            if (isset($elem -> discount)) {
                $price = $elem -> price * (1 - $elem -> discount / 100);
            } else {
                $price = $elem -> price;
            }
            if ($price <= $this -> maxPrice) {
                $result[] = $elem;
            }
        }

        return $result;
    }
}