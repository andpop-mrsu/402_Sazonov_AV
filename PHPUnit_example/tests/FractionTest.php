<?php

namespace App\Tests;

use \PHPUnit\Framework\TestCase;
use App\Fraction;

class FractionTest extends TestCase
{
    public function testReduction()
    {
        $p = new Fraction(40, 100);
        $this->assertSame(2, $p->getNumer());
        $this->assertSame(5, $p->getDenom());
    }

    public function testTextRepresentation()
    {
        $p = new Fraction(10, 3);
        $this->assertSame("3'1/3", $p->__toString());
    }
}


