<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Stack;

class StackTest extends TestCase
{
    public function testPushAndEmpty()
    {
        $stack = new Stack();
        $this->assertSame(true, $stack->isEmpty());
        $stack->push("10000", 1212);
        $this->assertSame(false, $stack->isEmpty());
    }

    public function testTop()
    {
        $stack = new Stack(12, 3, "343", "3434", 45);
        $this->assertSame(45, $stack->top());
    }

    public function testPop()
    {
        $stack1 = new Stack(1, 5, 7, 3, "24453", 22);
        $stack2 = new Stack();
        $this->assertSame(22, $stack1->pop());
        $this->assertSame("24453", $stack1->top());
        $this->assertSame(null, $stack2->pop());
    }

    public function testTextRepresentation()
    {
        $stack = new Stack(5, 4, 3, 2, 1, "abra", 111111);
        $this -> assertSame("[111111->abra->1->2->3->4->5]", $stack->__toString());
    }

    public function testCopy()
    {
        $stack = new Stack(5, 1, 62, 46, "345fdfdfd", 43);
        $newStack = $stack->copy();
        $this->assertInstanceOf(Stack::class, $newStack);
        $this->assertSame(false, $newStack->isEmpty());
        $this->assertSame(43, $newStack->top());
    }
}