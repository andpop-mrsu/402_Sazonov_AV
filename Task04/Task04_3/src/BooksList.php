<?php

namespace App;

use App\Books;
use Iterator as Iterator;
use App\Logger;
use App\FileLogger;
use App\DBLogger;

class BooksList implements Iterator
{
    private array $books;
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->logger->log(date("d.m.Y"), date("H:i:s"), "Cоздан новый список книг");
    }

    public function add(Books $book): BooksList
    {
        $this->books[] = $book;
        $this->logger->log(date("d.m.Y"), date("H:i:s"), "В список добавлена новая книга");
        return $this;
    }


    public function count(): int
    {
        return count($this -> books);
    }

    public function get(int $n): Books
    {
        return $this -> books[$n - 1];
    }

    public function store(string $fileName)
    {
        file_put_contents($fileName, serialize($this -> books));
    }

    public function load(string $fileName)
    {
        if (!file_exists($fileName)) {
            return "Файл " . $fileName . " не существует!";
        }

        $this -> books = unserialize(file_get_contents($fileName));
    }

    public function current()
    {
        return current($this -> books);
    }

    public function key()
    {
        return current($this -> books) -> getId();
    }

    public function next()
    {
        return next($this -> books);
    }

    public function rewind()
    {
        reset($this -> books);
    }

    public function valid()
    {
        return $this -> current() !== false;
    }
}