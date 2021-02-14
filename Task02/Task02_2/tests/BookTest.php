<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Book;

class BookTest extends TestCase
{

    public function testTextRepresentation() {
        $book = new Book();
        $book->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);

        $this->assertSame(
            "Id: 1" . "\n" .
            "Название: PHP forever" . "\n" .
            "Автор1: Gates B." . "\n" .
            "Автор2: Smith J." . "\n" .
            "Издательство: O'Reily" . "\n" .
            "Год: 2020",
            $book -> __toString()
        );
    }

    public function testGetFuntions() {
        $book = new Book();
        $book->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $this->assertSame("PHP forever", $book->getTitle());
        $this->assertSame("Автор1: Gates B." . "\n" . "Автор2: Smith J." . "\n", $book->getAuthors());
        $this->assertSame("O'Reily", $book->getPublisher());
        $this->assertSame(2020, $book->getYear());
    }
}