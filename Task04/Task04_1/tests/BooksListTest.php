<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\BooksList;
use App\Books;

class BooksListTest extends TestCase
{
    public function testAddAndCount()
    {
        $books = new Books();
        $booksList = new BooksList();
        $booksList -> add($books);
        $this -> assertSame(1, $booksList -> count());
    }

    public function testGet()
    {
        $books = new Books();
        $booksList = new BooksList();
        $books ->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $booksList -> add($books);
        $this -> assertInstanceOf(Books::class, $booksList -> get(1));
    }

    public function testStore()
    {
        $books = new Books();
        $booksList = new BooksList();
        $books ->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $booksList -> add($books);
        $this -> assertSame(null, $booksList -> store("output"));
    }

    public function testLoad()
    {
        $booksList = new BooksList();
        $booksList -> load("output");
        $this -> assertSame(1, $booksList -> count());
        $this -> assertInstanceOf(Books::class, $booksList -> get(1));
        $this -> assertSame("Файл fileName не существует!", $booksList -> load("fileName"));
    }

    public function testCurrentAndKey()
    {
        $books1 = new Books();
        $books2 = new Books();
        $books3 = new Books();
        $booksList = new BooksList();
        $books1 ->
        setTitle("PHP forever") ->
        setAuthors(" Gates B.", " Smith J.") ->
        setPublisher("O'Reily") ->
        setYear(2020);
        $books2 ->
        setTitle("JS forever") ->
        setAuthors(" Gath A.", " Raily J.") ->
        setPublisher("O'Colson") ->
        setYear(2010);
        $books3 ->
        setTitle("Python forever") ->
        setAuthors(" Jonson B.", " O'Conner J.") ->
        setPublisher("O'Reily") ->
        setYear(2005);
        $booksList -> add($books1);
        $booksList -> add($books2);
        $booksList -> add($books3);

        $this -> assertSame("Id: 4" . "\n" .
        "Название: PHP forever" . "\n" .
        "Автор1: Gates B." . "\n" .
        "Автор2: Smith J." . "\n" .
        "Издательство: O'Reily" . "\n" .
        "Год: 2020", $booksList -> current() -> __toString());
        $this -> assertSame(4, $booksList -> key());
        return $booksList;
    }
    /**
     * @depends testCurrentAndKey
     */

    public function testNext(BooksList $booksList)
    {
        $booksList -> next();
        $this -> assertSame("Id: 5" . "\n" .
        "Название: JS forever" . "\n" .
        "Автор1: Gath A." . "\n" .
        "Автор2: Raily J." . "\n" .
        "Издательство: O'Colson" . "\n" .
        "Год: 2010", $booksList -> current() -> __toString());
        $booksList -> next();
        $this -> assertSame("Id: 6" . "\n" .
        "Название: Python forever" . "\n" .
        "Автор1: Jonson B." . "\n" .
        "Автор2: O'Conner J." . "\n" .
        "Издательство: O'Reily" . "\n" .
        "Год: 2005", $booksList -> current() -> __toString());

        return $booksList;
    }

    /**
     * @depends testNext
     */

    public function testValidAndRewind(BooksList $booksList)
    {
        $booksList -> next();
        $this -> assertSame(false, $booksList -> valid());
        $booksList -> rewind();
        $this -> assertSame(true, $booksList -> valid());
        $this -> assertSame("Id: 4" . "\n" .
        "Название: PHP forever" . "\n" .
        "Автор1: Gates B." . "\n" .
        "Автор2: Smith J." . "\n" .
        "Издательство: O'Reily" . "\n" .
        "Год: 2020", $booksList -> current() -> __toString());
    }
}