<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Queue;

class QueueTest extends TestCase
{
    public function testEnqueueAndEmpty()
    {
        $queue = new Queue();
        $this->assertSame(true, $queue->isEmpty());
        $queue->enqueue("abc", 999);
        $this->assertSame(false, $queue->isEmpty());
    }

    public function testPeek()
    {
        $queue = new Queue(7, 3, "221", "12093", 22);
        $this->assertSame(7, $queue->peek());
    }

    public function testDequeue()
    {
        $queue1 = new Queue("test", 21, "abc", 22, "qwerty", 89);
        $queue2 = new Queue();
        $this->assertSame("test", $queue1->dequeue());
        $this->assertSame(21, $queue1->peek());
        $this->assertSame(null, $queue2->dequeue());
    }

    public function testTextRepresentation()
    {
        $queue = new Queue(5, 4, 3, 2, 1, "0");
        $this->assertSame("[5<-4<-3<-2<-1<-0]", $queue->__toString());
    }

    public function testCopy()
    {
        $queue = new Queue("qwerty", 33, 99, "tests402", 65);
        $newQueue = $queue->copy();
        $this -> assertInstanceOf(Queue::class, $newQueue);
        $this -> assertSame(false, $newQueue->isEmpty());
        $this -> assertSame("qwerty", $newQueue->peek());
    }
}