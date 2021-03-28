<?php

namespace App;

interface ProductFilteringStrategy
{
    public function filter(array $collection);
}