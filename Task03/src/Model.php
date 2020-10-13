<?php

namespace sazonov_da\minesweeper\Model;

function createVars()
{
    define("MAX_X", 10);
    define("MAX_Y", 10);
    define("BOMBS_COUNT", 10);

    $cellsArray = array();
    $bombsArray = array();
    $openedCellsCount = 0;
}

function contains($array, $x, $y)
{
    if (isset($array)) {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['x'] == $x && $array[$i]['y'] == $y) {
                return true;
            }
        }
    }
    return false;
}

function createBombsArray($position)
{
    global $bombsArray;
    for ($i = 0; $i < BOMBS_COUNT; $i++) {
        $randX = rand(0, MAX_X - 1);
        $randY = rand(0, MAX_Y - 1);
        if (!contains($bombsArray, $randX, $randY)) {
            $bombsArray[$i] = array('x' => $randX, 'y' => $randY);
        } else {
            createBombsArray($i);
            break;
        }
    }
    if (count($bombsArray) == BOMBS_COUNT) {
        return;
    }
}

function deployBombs()
{
    global $cellsArray, $bombsArray;
    for ($i = 0; $i < BOMBS_COUNT; $i++) {
        $x = $bombsArray[$i]['x'];
        $y = $bombsArray[$i]['y'];
        $cellsArray[$y][$x]['isbomb'] = true;
        for ($j = $x - 1; $j <= $x + 1; $j++) {
            for ($k = $y - 1; $k <= $y + 1; $k++) {
                if (isset($cellsArray[$j]) && isset($cellsArray[$k][$j])) {
                    $cellsArray[$k][$j]['nearbycount'] += 1;
                }
            }
        }
    }
}

function createCellsArray()
{
    global $cellsArray;
    for ($i = 0; $i < MAX_Y; $i++) {
        for ($j = 0; $j < MAX_X; $j++) {
            $cellsArray[$i][$j] = array('opened' => false, 'marked' => false,
                                        'isbomb' => false, 'nearbycount' => 0);
        }
    }
    createBombsArray(0);
    deployBombs();
}

function isBomb($x, $y)
{
    global $cellsArray, $lostGame;
    if (
        $cellsArray[$y][$x]['isbomb'] == true
        && $cellsArray[$y][$x]['marked'] == false
    ) {
        $cellsArray[$y][$x]['opened'] = true;
        return true;
    }
    return false;
}

function openSurroundedCells($x, $y)
{
    global $cellsArray;
    if (
        isset($cellsArray[$y])
        && isset($cellsArray[$y][$x])
    ) {
        openArea($x, $y);
    }
}

function openArea($x, $y)
{
    global $openedCellsCount, $cellsArray;
    if (
        $cellsArray[$y][$x]['opened'] == false
        && $cellsArray[$y][$x]['marked'] == false
    ) {
        $cellsArray[$y][$x]['opened'] = true;
        $openedCellsCount += 1;
        if ($cellsArray[$y][$x]['nearbycount'] != 0) {
            return;
        }
    } else {
        return;
    }
    for ($i = $x - 1; $i <= $x + 1; $i++) {
        for ($j = $y - 1; $j <= $y + 1; $j++) {
            openSurroundedCells($i, $j);
        }
    }
}

function setFlag($x, $y)
{
    global $openedCellsCount, $cellsArray;
    if ($cellsArray[$y][$x]['marked'] == false) {
        if ($cellsArray[$y][$x]['opened'] == false) {
            $cellsArray[$y][$x]['marked'] = true;
            $cellsArray[$y][$x]['opened'] = true;
            $openedCellsCount++;
        }
    } else {
            $cellsArray[$y][$x]['marked'] = false;
            $cellsArray[$y][$x]['opened'] = false;
            $openedCellsCount--;
    }
}