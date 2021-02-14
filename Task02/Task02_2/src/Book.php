<?php

namespace App;

class Book
{
    private static $lastId = 1;

    private $id;
    private $title;
    private $authors;
    private $publisher;
    private $year;

    public function __construct() {
        $this->id = self::$lastId++;
    }

    public function setTitle(string $title) {
        $this->title = $title;

        return $this;
    }

    public function setAuthors(string ...$authors) {
        foreach ($authors as $author) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function setPublisher(string $publisher) {
        $this->publisher = $publisher;

        return $this;
    }

    public function setYear(int $year) {
        $this->year = $year;

        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAuthors(): string {
        return $this->convertAuthors($this->authors);
    }

    public function getPublisher(): string {
        return $this->publisher;
    }

    public function getYear(): int {
        return $this->year;
    }

    private function convertAuthors($authors) {
        $arrayToStringAuthors = '';

        for ($i = 0; $i < count($authors); $i++) {
            $arrayToStringAuthors = $arrayToStringAuthors . "Автор" . ($i + 1) . ":" . $authors[$i] . "\n";
        }

        return $arrayToStringAuthors;
    }

    public function __toString(): string {
        $authors = $this->convertAuthors($this -> authors);
        return ("Id: {$this->id}" . "\n" .
        "Название: {$this->title}" . "\n" .
        $authors  .
        "Издательство: {$this->publisher}" . "\n" .
        "Год: {$this->year}");
    }
}