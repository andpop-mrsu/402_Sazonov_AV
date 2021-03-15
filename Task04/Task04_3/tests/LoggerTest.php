<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\BooksList;
use App\Books;
use App\FileLogger;
use App\DBLogger;

class LoggerTest extends TestCase
{
    public function testLog()
    {
        $fileLogger = new FileLogger('logs');
        $booksList = new BooksList($fileLogger);
        $this -> assertTrue(file_exists("./logs/logs.txt"));
        $sizeLogsTxt = sizeof(file("./logs/logs.txt"));
        $book1 = new Books();
        $book2 = new Books();
        $book3 = new Books();
        $booksList->add($book1);
        $booksList->add($book2);
        $booksList->add($book3);
        $this->assertSame($sizeLogsTxt + 3, sizeof(file("./logs/logs.txt")));

        $dbLogger = new DbLogger('logs');
        $booksList1 = new BooksList($dbLogger);
        $this -> assertTrue(file_exists("./logs/logs.db"));
        $booksList1->add($book1);
        $booksList1->add($book2);
        $booksList1->add($book3);
    }
}