<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Books;

class BooksTest extends TestCase
{

    public function testTextRepresentation()
    {
        $books = new Books();
        $books ->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $this -> assertSame(
            "Id: 7" . "\n" .
            "Название: PHP forever" . "\n" .
            "Автор1: Gates B." . "\n" .
            "Автор2: Smith J." . "\n" .
            "Издательство: O'Reily" . "\n" .
            "Год: 2020",
            $books -> __toString()
        );
    }

    public function testGetFuntions()
    {
        $books = new Books();
        $books ->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $this ->  assertSame("PHP forever", $books -> getTitle());
        $this ->  assertSame("Автор1: Gates B." . "\n" . "Автор2: Smith J." . "\n", $books -> getAuthors());
        $this ->  assertSame("O'Reily", $books -> getPublisher());
        $this ->  assertSame(2020, $books -> getYear());
    }
}