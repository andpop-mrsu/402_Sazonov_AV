<?php

namespace sazonov_da\minesweeper\Model;

function createDatabase()
{
    $db = new \SQLite3('gamedb.db');

    $gamesInfoTable = "CREATE TABLE gamesInfo(
        idGame INTEGER PRIMARY KEY,
        dateGame DATE,
        gameTime TIME,
        playerName TEXT,
        dimension INTEGER,
        bombsCount INTEGER,
        gameResult TEXT
    )";
    $db->exec($gamesInfoTable);

    $concreteGameTable = "CREATE TABLE concreteGame(
        idGame INTEGER,
        gameTurn INTEGER,
        coordinates TEXT,
        result TEXT
    )";
    $db->exec($concreteGameTable);

    $bombsInfoTable = "CREATE TABLE bombsInfo(
        idGame INTEGER,
        bombCoordinates TEXT
    )";
    $db->exec($bombsInfoTable);
}

function openDatabase()
{
    if (!file_exists("gamedb.db")) {
        createDatabase();
    } else {
        $db = new \SQLite3('gamedb.db');
    }
}

function readCfgFile()
{
    if (!file_exists("config.cfg")) {
        exit("Config файл отстутствует!\n");
    }
    $configFile = file("config.cfg");
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

function readFromDb($id)
{
    $gameDatabase = new \SQLite3('gamedb.db');
    $query = "SELECT dimension, bombsCount FROM gamesInfo";
    $result = $gameDatabase->query($query);
    $row = $result->fetchArray();
    $dimension = $row[0];
    $bombsCount = $row[1];
    define("DIMENSION", (int)$dimension);
    define("BOMBS_COUNT", (int)$bombsCount);
}

function getVars($id)
{
    readFromDb($id);
    openDatabase();

    $cellsArray = array();
    $bombsArray = array();
    $openedCellsCount = 0;
}

function createVars()
{
    readCfgFile();
    openDatabase();

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

function createBombsArrayFromDb($id)
{
    global $bombsArray;
    $gameDatabase = new \SQLite3('gamedb.db');
    $query = "SELECT * FROM bombsInfo WHERE idGame = '$id'";
    $result = $gameDatabase->query($query);
    $i = 0;
    while ($row = $result->fetchArray()) {
        $temp = explode(',', $row[1]);
        $x = $temp[0];
        $y = $temp[1];
        $bombsArray[$i] = array('x' => $x, 'y' => $y);
        $i++;
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

function createCellsArray($identifier, $id)
{
    global $cellsArray, $bombsArray;
    for ($i = 0; $i < DIMENSION; $i++) {
        for ($j = 0; $j < DIMENSION; $j++) {
            $cellsArray[$i][$j] = array('opened' => false, 'marked' => false,
                                        'isbomb' => false, 'nearbycount' => 0);
        }
    }

    if ($identifier == "new") {
        createBombsArray(0);
    } elseif ($identifier == "replay") {
        createBombsArrayFromDb($id);
    }
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

function insertInfo()
{
    $gameDatabase = new \SQLite3('gamedb.db');

    date_default_timezone_set("Europe/Moscow");

    $dateGame = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");
    $playerName = getenv("username");
    $dimension = DIMENSION;
    $bombsCount = BOMBS_COUNT;
    $gameResult = "Не окончена";

    $query = "INSERT INTO gamesInfo(
        dateGame,
        gameTime, 
        playerName,
        dimension,
        bombsCount,
        gameResult
    ) VALUES (
        '$dateGame',
        '$gameTime', 
        '$playerName',
        '$dimension',
        '$bombsCount',
        '$gameResult' 
    )";

    $gameDatabase->exec($query);
}

function getGameId()
{
    $gameDatabase = new \SQLite3('gamedb.db');
    $query = "SELECT idGame 
    FROM gamesInfo 
    ORDER BY idGame DESC LIMIT 1";
    $result = $gameDatabase->querySingle($query);
    define("GAME_ID", $result);
}

function insertBombsToDb()
{
    global $bombsArray;
    $gameDatabase = new \SQLite3('gamedb.db');
    $gameId = GAME_ID;
    for ($i = 0; $i < BOMBS_COUNT; $i++) {
        $coordinates = $bombsArray[$i]['x'] . "," . $bombsArray[$i]['y'];
        $query = "INSERT INTO bombsInfo(
            idGame,
            bombCoordinates
        ) VALUES(
            '$gameId',
            '$coordinates'
        )";
        $gameDatabase->exec($query);
    }
}

function insertTurnInfo($turn, $turnResult, $x, $y)
{
    $gameDatabase = new \SQLite3('gamedb.db');
    $gameId = GAME_ID;
    $coordinates = $x . "," . $y;
    $query = "INSERT INTO concreteGame(
        idGame,
        gameTurn,
        coordinates,
        result
    ) VALUES (
        '$gameId',
        '$turn',
        '$coordinates',
        '$turnResult'
    )";
    $gameDatabase->exec($query);
}