<?php

namespace App;

interface QueueInterface
{
    public function enqueue(mixed ...$elements): void;
    public function dequeue(): mixed;
    public function peek(): mixed;
    public function isEmpty(): bool;
    public function copy(): Queue;
    public function __toString(): string;
}