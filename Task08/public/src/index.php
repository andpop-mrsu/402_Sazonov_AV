<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->withRedirect('./index.html', 301);
});

$app->get('/games', function ($request, $response) {
    $gamesInfo = json_encode(listGames()); 
    $response->getBody()->write($gamesInfo);
    return $response;
});

$app->get('/games/{id}', function ($request, $response, array $args) {
    $gameId = $args['id'];
    $turnsInfo = concreteGameTurns($gameId);
    $gameInfo = concreteGameInfo($gameId);
    $bombsInfo = concreteGameBombs($gameId);
    $responseBody = json_encode($turnsInfo . $gameInfo . $bombsInfo);
    $response->getBody()->write($responseBody);
    return $response;
});

$app->post('/games', function ($request, $response) {
    $db = openDatabase();
    $gameId = getGameId();
    $requestBody = json_decode($request->getBody());
    $arrayBody = explode(";", $requestBody);
    
    for ($i = 0; $i < count($arrayBody) - 2; $i++) {
        $turnInfo = explode("|", $arrayBody[$i]);
        writeTurnInfo($gameId, $turnInfo);
    }

    $gameInfo = explode("|", $arrayBody[count($arrayBody) - 2]);
    insertGameInfo($gameInfo);

    $bombsInfo = explode("|", $arrayBody[count($arrayBody) - 1]);
    insertBombsInfo($gameId, $bombsInfo);

    $response->write('Данные записаны');
    return $response;
});

$app->run();

function openDatabase()
{
    if (!file_exists("./../db/gamedb.db")) {
        $db = new \SQLite3('./../db/gamedb.db');
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
        } else {
            $db = new \SQLite3('./../db/gamedb.db');
        }
    return $db;
}

function getGameId()
{
    $gameDatabase = new \SQLite3('./../db/gamedb.db');
    $query = "SELECT idGame 
    FROM gamesInfo 
    ORDER BY idGame DESC LIMIT 1";
    $result = $gameDatabase->querySingle($query);
    if (is_null($result)) {
        return 1;
    }
    return $result + 1;
}

function writeTurnInfo($id, $info)
{
    $turn = $info[0];
    $coordinates = $info[1];
    $turnResult = $info[2];

    $gameDatabase = new \SQLite3('./../db/gamedb.db');
    $query = "INSERT INTO concreteGame(
        idGame,
        gameTurn,
        coordinates,
        result
    ) VALUES (
        '$id',
        '$turn',
        '$coordinates',
        '$turnResult'
    )";
    $gameDatabase->exec($query);
}

function insertGameInfo($info)
{
    $playerName = $info[0];
    $dimension = $info[1];
    $bombsCount = $info[2];
    $gameResult = $info[3];

    $gameDatabase = new \SQLite3('./../db/gamedb.db');

    date_default_timezone_set("Europe/Moscow");
    
    $dateGame = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");

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

function insertBombsInfo($id, $info)
{
    $gameDatabase = new \SQLite3('./../db/gamedb.db');
    for ($i = 0; $i < count($info); $i++) {
        $coordinatesArray = explode("_", $info[$i]);
        $coordinates = $coordinatesArray[0] . "," . $coordinatesArray[1];
        $query = "INSERT INTO bombsInfo(
            idGame,
            bombCoordinates
        ) VALUES(
            '$id',
            '$coordinates'
        )";
        $gameDatabase->exec($query);
    }
}

function listGames()
{
    $gameDatabase = openDatabase();
    $result = $gameDatabase->query("SELECT * FROM gamesInfo");
    $gamesInfo = "";
    while ($row = $result->fetchArray()) {
        for ($i = 0; $i < 7; $i++) {
            $gamesInfo .= $row[$i] . "|";
        }
        $gamesInfo = substr($gamesInfo, 0, -1);
        $gamesInfo .= ";";
    }
    return $gamesInfo;
}

function concreteGameTurns($id)
{
    $gameDatabase = openDatabase();
    $turnsInfo = "";
    $query = "SELECT 
            gameTurn, 
            coordinates, 
            result 
            FROM concreteGame 
            WHERE idGame='$id'";
    $gameTurns = $gameDatabase->query($query);
    while ($gameTurnsRow = $gameTurns->fetchArray()) {
        for ($i = 0; $i < 3; $i++) {
            $turnsInfo .= $gameTurnsRow[$i] . "|";
        }
        $turnsInfo = substr($turnsInfo, 0, -1);
        $turnsInfo .= ";";
    }
    return $turnsInfo;
}

function concreteGameInfo($id)
{
    $gameDatabase = openDatabase();
    $gameInfo = "";
    $query = "SELECT *
        FROM gamesInfo 
        WHERE idGame='$id'"; 
    $game = $gameDatabase->query($query);
    while ($gameRow = $game->fetchArray()) {
        for ($i = 0; $i < 7; $i++) {
            $gameInfo .= $gameRow[$i] . "|";
        }
    }
    $gameInfo = substr($gameInfo, 0, -1);
    return $gameInfo . ";";
}

function concreteGameBombs($id)
{
    $gameDatabase = openDatabase();
    $bombsInfo = "";
    $query = "SELECT 
        bombCoordinates
        FROM bombsInfo 
        WHERE idGame='$id'"; 
    $bombs = $gameDatabase->query($query);
    while ($bombsRow = $bombs->fetchArray()) {
        $bombsInfo .= $bombsRow[0] . "|";
    }
    $bombsInfo = substr($bombsInfo, 0, -1);
    return $bombsInfo;
}
