<?php

namespace App;

class Vector
{
    private float $x;
    private float $y;
    private float $z;

    public function __construct($x, $y, $z)
    {
        if (!(is_numeric($x) && is_numeric($y) && is_numeric($z))) {
            echo "Ошибка! Значения данного вектора недопустимы!";
            exit();
        }

        $this->x = (float)$x;
        $this->y = (float)$y;
        $this->z = (float)$z;
    }

    public function add(Vector $vector): Vector
    {
        $newX = $this->x + $vector->x;
        $newY = $this->y + $vector->y;
        $newZ = $this->z + $vector->z;

        return new Vector($newX, $newY, $newZ);
    }

    public function sub(Vector $vector): Vector
    {
        $newX = $this->x - $vector->x;
        $newY = $this->y - $vector->y;
        $newZ = $this->z - $vector->z;

        return new Vector($newX, $newY, $newZ);
    }

    public function product($number): Vector
    {
        $newX = $number * $this->x;
        $newY = $number * $this->y;
        $newZ = $number * $this->z;

        return new Vector($newX, $newY, $newZ);
    }

    public function scalarProduct(Vector $vector): float
    {
        $newX = $this->x * $vector->x;
        $newY = $this->y * $vector->y;
        $newZ = $this->z * $vector->z;

        return $newX + $newY + $newZ;
    }

    public function vectorProduct(Vector $vector): Vector
    {
        $newX = $this->z * $vector->y - $this->y * $vector->z;
        $newY = $this->x * $vector->z - $this->z * $vector->x;
        $newZ = $this->y * $vector->x - $this->x * $vector->y;

        return new Vector($newX, $newY, $newZ);
    }

    public function __toString(): string{
        return "(" . $this->x . ";" . $this->y . ";" . $this->z . ")";
    }
}