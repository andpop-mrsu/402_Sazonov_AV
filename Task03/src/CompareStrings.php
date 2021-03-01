<?php

namespace App;

use App\Stack;

function compareStrings(string $str1, string $str2)
{
    $stack1 = new Stack();
    $stack2 = new Stack();

    for ($i = 0; $i < strlen($str1); $i++) {
        if ($str1[$i] == "#") {
            $stack1->pop();
        } else {
            $stack1->push($str1[$i]);
        }
    }

    for ($i = 0; $i < strlen($str2); $i++) {
        if ($str2[$i] == "#") {
            $stack2->pop();
        } else {
            $stack2->push($str2[$i]);
        }
    }

    if ($stack1->__toString() == $stack2->__toString()) {
        return true;
    } else {
        return false;
    }
}