<?php

namespace App;

class ManufacturerFilter implements ProductFilteringStrategy
{
    private string $manufacturer;

    public function __construct(string $manufacturer)
    {
        $this -> manufacturer = $manufacturer;
    }

    public function filter(array $collection)
    {
        $result = array();
        $manufacturer = $this -> manufacturer;
        foreach ($collection as $elem) {
            if ($elem -> manufacturer == $manufacturer) {
                $result[] = $elem;
            }
        }
        return $result;
    }
}