<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\BooksList;
use App\Book;

class BooksListTest extends TestCase
{
    public function testAddAndCount() {
        $book = new Book();
        $booksList = new BooksList();
        $booksList->add($book);
        $this->assertSame(1, $booksList->count());
    }

    public function testGet() {
        $book = new Book();
        $booksList = new BooksList();
        $book->
        setTitle("PHP forever")->
        setAuthors(" Gates B.", " Smith J.")->
        setPublisher("O'Reily")->
        setYear(2020);

        $booksList-> add($book);
        $this -> assertInstanceOf(Book::class, $booksList -> get(1));
    }

    public function testStore() {
        $book = new Book();
        $booksList = new BooksList();

        $book->setTitle("PHP forever")->
        setAuthors(" Gates B.", " Smith J.")->
        setPublisher("O'Reily")->
        setYear(2020);

        $booksList->add($book);
        $this->assertSame(null, $booksList->store("output"));
    }

    public function testLoad() {
        $booksList = new BooksList();
        $booksList->load("output");

        $this->assertSame(1, $booksList->count());
        $this->assertInstanceOf(Book::class, $booksList->get(1));
        $this->assertSame("Файл fileName не найден!", $booksList->load("fileName"));
    }
}