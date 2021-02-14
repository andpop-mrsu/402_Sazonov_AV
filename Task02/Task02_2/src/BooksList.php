<?php

namespace App;

use App\Book;

class BooksList
{
    private $books = array();

    public function add(Book $book) {
        array_push($this->books, $book);
    }

    public function count(): int {
        return count($this->books);
    }

    public function get(int $n): Book {
        return $this->books[$n - 1];
    }

    public function store(string $fileName) {
        file_put_contents($fileName, serialize($this->books));
    }

    public function load(string $fileName) {
        if (!file_exists($fileName)) {
            return "Файл " . $fileName . " не найден!";
        }

        $this->books = unserialize(file_get_contents($fileName));
    }
}