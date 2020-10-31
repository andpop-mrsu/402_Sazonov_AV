<?php

namespace sazonov_da\minesweeper\Model;

use RedBeanPHP\R as R;

function readCfgFile()
{
    $filePath = dirname(__FILE__) . "/../bin/config.cfg";
    if (!file_exists($filePath)) {
        exit("Config-файл отсутствует!\n");
    }
    $configFile = file($filePath);
    $fieldNames = array(0 => "DIMENSION", 1 => "BOMBS_COUNT");
    $checker = 0;
    for ($i = 0; $i < count($configFile); $i++) {
        $tempArray = explode(' ', $configFile[$i]);
        $name = $tempArray[0];
        $value = $tempArray[1];

        if (in_array($name, $fieldNames)) {
            define($name, (int)$value);
            $checker++;
        } else {
            exit("Неверный config файл!\n");
        }
    }

    if ($checker != 2) {
        exit("Неверный config файл!\n");
    }
}

function createVars()
{
    readCfgFile();

    R::setup("sqlite:gamedb.db");

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
        $randX = rand(0, DIMENSION - 1);
        $randY = rand(0, DIMENSION - 1);
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
    global $cellsArray, $bombsArray;
    for ($i = 0; $i < DIMENSION; $i++) {
        for ($j = 0; $j < DIMENSION; $j++) {
            $cellsArray[$i][$j] = array('opened' => false, 'marked' => false,
                                        'isbomb' => false, 'nearbycount' => 0);
        }
    }

    createBombsArray(0);
    insertBombsToDb($bombsArray);
    deployBombs();
}

function isBomb($x, $y)
{
    global $cellsArray, $lostGame;
    if (
        $cellsArray[$x][$y]['isbomb'] == true
        && $cellsArray[$x][$y]['marked'] == false
    ) {
        $cellsArray[$x][$y]['opened'] = true;
        return true;
    }
    return false;
}

function openSurroundedCells($x, $y)
{
    global $cellsArray;
    if (
        isset($cellsArray[$x])
        && isset($cellsArray[$x][$y])
    ) {
        openArea($x, $y);
    }
}

function openArea($x, $y)
{
    global $openedCellsCount, $cellsArray;
    if (
        $cellsArray[$x][$y]['opened'] == false
        && $cellsArray[$x][$y]['marked'] == false
    ) {
        $cellsArray[$x][$y]['opened'] = true;
        $openedCellsCount += 1;
        if ($cellsArray[$x][$y]['nearbycount'] != 0) {
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
    if ($cellsArray[$x][$y]['marked'] == false) {
        if ($cellsArray[$x][$y]['opened'] == false) {
            $cellsArray[$x][$y]['marked'] = true;
            $cellsArray[$x][$y]['opened'] = true;
            $openedCellsCount++;
        }
    } else {
            $cellsArray[$x][$y]['marked'] = false;
            $cellsArray[$x][$y]['opened'] = false;
            $openedCellsCount--;
    }
}

function findSymbol($x, $y)
{
    global $cellsArray;
    if ($cellsArray[$y][$x]['opened'] == true) {
        if ($cellsArray[$y][$x]['marked'] == true) {
            return sprintf('%2s', 'F');
        }

        if ($cellsArray[$y][$x]['isbomb'] == true) {
            return sprintf('%2s', '*');
        }

        if ($cellsArray[$y][$x]['nearbycount'] == 0) {
            return sprintf('%2s', '-');
        } else {
            return sprintf(
                '%2s',
                $cellsArray[$y][$x]['nearbycount']
            );
        }
    } else {
        return sprintf('%2s', '.');
    }
}

function insertInfo($playerName)
{
    date_default_timezone_set("Europe/Moscow");

    $dateGame = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");
    $gameResult = "Не окончена";

    $newRow = R::dispense('gamesinfo');

    $newRow->dategame = $dateGame;
    $newRow->gametime = $gameTime;
    $newRow->playername = $playerName;
    $newRow->dimension = DIMENSION;
    $newRow->bombscount = BOMBS_COUNT;
    $newRow->gameResult = $gameResult;

    R::store($newRow);
}

function getGameId()
{
    $result = R::getInsertID();
    define("GAME_ID", $result);
}

function insertBombsToDb()
{
    global $bombsArray;
    for ($i = 0; $i < BOMBS_COUNT; $i++) {
        $coordinates = $bombsArray[$i]['x'] . "," . $bombsArray[$i]['y'];

        $newRow = R::dispense('bombsinfo');

        $newRow->idgame = GAME_ID;
        $newRow->bombcoordinates = $coordinates;

        R::store($newRow);
    }
}

function insertTurnInfo($turn, $turnResult, $x, $y)
{
    $coordinates = $x . "," . $y;
    $newRow = R::dispense('concretegame');

    $newRow->idgame = GAME_ID;
    $newRow->gameturn = $turn;
    $newRow->coordinates = $coordinates;
    $newRow->result = $turnResult;

    R::store($newRow);
}

function updateDatabase($gameResult)
{
    $row = R::load('gamesinfo', GAME_ID);
    $row->gameresult = $gameResult;
    R::store($row);
}

function getGamesInfo()
{
    R::setup("sqlite:gamedb.db");

    $gamesArray = array();

    $result = R::findAll('gamesinfo');

    foreach ($result as $row) {
        array_push($gamesArray, $row);
    }
    return $gamesArray;
}

function idExists($id)
{
    R::setup("sqlite:gamedb.db");
    $count = R::count("gamesinfo", " id = ? ", [$id]);
    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function getTurnsInfo($id)
{
    $turnsArray = array();

    $gameTurns = R::find("concretegame", " idgame = ? ", [$id]);
    foreach ($gameTurns as $gameTurnsRow) {
        array_push($turnsArray, $gameTurnsRow);
    }
    return $turnsArray;
}