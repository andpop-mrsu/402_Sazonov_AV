<?php

namespace App;

class Vector
{
    private float $CordX;
    private float $CordY;
    private float $CordZ;

    public function __construct(float $CordX, float $CordY, float $CordZ)
    {
        if (!(is_numeric($CordX) && is_numeric($CordY) && is_numeric($CordZ))) {
            echo "Ошибка! Значения данного вектора недопустимы!";
            exit();
        }

        $this->CordX = (float)$CordX;
        $this->CordY = (float)$Cordy;
        $this->CordZ = (float)$Cordz;
    }

    public function add(Vector $vector): Vector
    {
        $newX = $this->CordX + $vector->CordX;
        $newY = $this->CordY + $vector->CordY;
        $newZ = $this->CordZ + $vector->CordZ;

        return new Vector($newX, $newY, $newZ);
    }

    public function sub(Vector $vector): Vector
    {
        $newX = $this->CordX - $vector->CordX;
        $newY = $this->CordY - $vector->CordY;
        $newZ = $this->CordZ - $vector->CordZ;

        return new Vector($newX, $newY, $newZ);
    }

    public function product($number): Vector
    {
        $newX = $number * $this->CordX;
        $newY = $number * $this->CordY;
        $newZ = $number * $this->CordZ;

        return new Vector($newX, $newY, $newZ);
    }

    public function scalarProduct(Vector $vector): float
    {
        $newX = $this->CordX * $vector->CordX;
        $newY = $this->CordY * $vector->CordY;
        $newZ = $this->CordZ * $vector->CordZ;

        $scalar = $newX + $newY + $newZ;
        return $scalar;
    }

    public function vectorProduct(Vector $vector): Vector
    {
        $newX = $this->CordZ * $vector->CordY - $this->CordY * $vector->CordZ;
        $newY = $this->CordX * $vector->CordZ - $this->CordZ * $vector->CordX;
        $newZ = $this->CordY * $vector->CordX - $this->CordX * $vector->CordY;

        return new Vector($newX, $newY, $newZ);
    }

    public function __toString(): string{
        return "(" . $this->CordX . ";" . $this->CordY . ";" . $this->CordZ . ")";
    }
}