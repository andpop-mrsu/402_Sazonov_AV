<?php

namespace App\Test;

use App\Vector;

function runTest()
{
    $FirstVector = new Vector(3.5, 4, 6);
    echo "First Vector = " . $FirstVector . "\n"; // (3.5; 4; 6)
    
    $SecondVector = new Vector(3, 8 -5);
    echo "Second Vector = " . $SecondVector . "\n"; // (3; 8; -5)

    $vectorAddition = $FirstVector->add($SecondVector);
    $vectorDifference = $FirstVector->sub($SecondVector);
    $vectorNumberProduct = $FirstVector->product(3);
    $scalarProduct = $FirstVector->scalarProduct($SecondVector);
    $vectorProduct = $FirstVector->vectorProduct($SecondVector);

    echo "Сумма векторов\n";
    echo $vectorAddition; // (6.5; 12; 1)

    echo "\nРазность векторов\n";
    echo $vectorDifference; // (0.5; -4; 11)

    echo "\nПроизведение вектора на число\n";
    echo $vectorNumberProduct; // (10.5; 12; 18)

    echo "\nСкалярное произведение векторов\n";
    echo $scalarProduct; // 12.5
    
    echo "\nВекторное произведение\n";
    echo $vectorProduct; // (-68; 35.5; 16)
}