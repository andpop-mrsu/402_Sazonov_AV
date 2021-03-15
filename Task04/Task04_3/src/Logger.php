<?php

namespace App;

abstract class Logger
{
    public string $fileName;
    public function __construct(string $fileName)
    {
        $this -> fileName = $fileName;
    }

    abstract public function log(string $date, string $time, string $description);
}