<?php

namespace Test\VectorTest;

use App\Vector;
use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    public function testAddition()
    {
        $FirstVector = new Vector(3.5, 4, 6);

        $SecondVector = new Vector(3, 8, -5);

        $this->assertSame("(6.5; 12; 1)", $FirstVector->add($SecondVector)->__toString());
    }

    public function testSubtraction()
    {
        $FirstVector = new Vector(3.5, 4, 6);

        $SecondVector = new Vector(3, 8, -5);

        $this->assertSame("(0.5; -4; 11)", $SecondVector->sub($SecondVector)->__toString());
    }

    public function testNumberProduct()
    {
        $FirstVector = new Vector(3.5, 4, 6);

        $this->assertSame("(10.5; 12; 18)", $FirstVector->product(3)->__toString());
    }

    public function testScalarProduct()
    {
        $FirstVector = new Vector(13.5, 4, 6);

        $SecondVector = new Vector(3, 8, -5);

        $this->assertEquals(12.5, $FirstVector->scalarProduct($SecondVector));
    }

    public function testVectorProduct()
    {
        $FirstVector = new Vector(3.5, 4, 6);

        $SecondVector = new Vector(3, 8, -5);

        $this->assertSame("(-68; 35.5; 16)", $FirstVector->vectorProduct($SecondVector)->__toString());
    }
}