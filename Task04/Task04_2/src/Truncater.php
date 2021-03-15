<?php

namespace App;

class Truncater
{
    private static array $defaultOptions = array("separator" => "...", "length" => 100);
    private $options = array();
    public function __construct(array $options = null)
    {
        if ($options) {
            $this -> options = $options + self::$defaultOptions;
        } else {
            $this -> options = self::$defaultOptions;
        }
    }

    public function truncate(string $str, array $options = null)
    {
        if ($options) {
            $options = $options + $this -> options;
        } else {
            $options = $this -> options;
        }
        if (strlen($str) <= $options["length"]) {
            return $str;
        }
        return substr($str, 0, $options["length"]) . $options["separator"];
    }
}