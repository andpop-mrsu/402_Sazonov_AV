import { redrawCell, drawField, changeGameButtonPicture, drawNumberInCell, deleteFlag, drawGamesInfoTable, drawConcreteGameTable } from './View.js';

let gameField = document.getElementById("gameField");
let infoField = document.getElementById("gameInfo");
let gameButton = document.getElementById('gameButton');

let user;
let cells;
let bombsArray;
let openCount;
let lostGame;
let wonGame;
let flagCount;
let bombs;
let turnNumber;

let gameInfo = "";

let contains = (array, element) => {
    for (let i of array) {
        if (i === element) {
            return true;
        }
    }
    return false;
}

let randomBombs = (n, bombsCount, position) => {
	let x, y;
	for (let i = position; i < bombsCount; i++) {
		x = Math.floor(Math.random() * n);
		y = Math.floor(Math.random() * n);
		let concreteBomb = x.toString() + "_" + y.toString();
		if (!contains(bombsArray, concreteBomb)) {
            bombsArray.push(concreteBomb);
        } else {
			randomBombs(n, bombsCount, i);
			break;
		}
	}
	if (bombsArray.length === bombsCount) {
        return;
    }
}

let setNearbyCount = (bombCoordinateX, bombCoordinateY) => {
    for (let i = bombCoordinateX - 1; i <= bombCoordinateX + 1; i++) {
        for (let j = bombCoordinateY - 1; j <= bombCoordinateY + 1; j++) {
            if (typeof cells[j] != "undefined" && typeof cells[j][i] != "undefined") {
                cells[j][i]['nearbycount'] +=1;
            }
        }
    }
}

let deployBombs = (bombsCount) => {
	for (let i = 0; i < bombsCount; i++){
		let coordinates = bombsArray[i].split("_");
        let x = coordinates[0];
        let y = coordinates[1];
		cells[parseInt(y)][parseInt(x)]['isbomb'] = 1;
		setNearbyCount(parseInt(x), parseInt(y));
	}
}

export let createGameField = (n, bombsCount, username) => {
    if (lostGame === 1)
        changeGameButtonPicture(gameButton, 'lost');

    if (wonGame === 1)
        changeGameButtonPicture(gameButton, 'won');

    openCount = 0;
    lostGame = 0;
    wonGame = 0;
    flagCount = 0;
    turnNumber = 0;
    bombs = bombsCount;
    user = username;
    
	cells = [];
    bombsArray = [];
    
	for (let i = 0; i < n; i++) {
	 	let concreteCell = [];
	 	for (let j = 0; j < n; j++) {
            concreteCell.push({'opened': 0, 'isbomb': 0, 'nearbycount': 0, 'marked': 0});
 		}
        cells.push(concreteCell);
    }


    drawField(gameField, n);
    randomBombs(n, bombsCount, 0);
    deployBombs(bombsCount);
}

export let setFlag = (cell, replayMode) => {
    let x = cell.cellIndex;
    let y = cell.parentNode.rowIndex;

	if (cells[x][y]['marked'] === 0) {
        if (flagCount === bombs) {
            return;
        }
		if (cells[x][y]['opened'] === 0) {
			cells[x][y]['marked'] = 1;
            cells[x][y]['opened'] = 1;
            turnNumber++;
            flagCount++;
            openCount++;
            writeTurnInfo('Поставлен флаг', turnNumber, x, y, replayMode);
            redrawCell(cell, 'flag');
		}
	} else {
		if (cells[x][y]['opened'] != 0) {
			cells[x][y]['marked'] = 0;
            cells[x][y]['opened'] = 0;
            turnNumber++;
            flagCount--;
            openCount--;
            writeTurnInfo('Убран флаг', turnNumber, x, y, replayMode);
            deleteFlag(cell);
		}
    }

    if (openCount === cells.length * cells[0].length) {
        makeChangesForWonGame(replayMode);
    }
}

let openSurroundedCells = (x, y) => {
    for (let i = x - 1; i <= x + 1; i++) {
        for (let j = y - 1; j <= y + 1; j++) {
            if (typeof cells[i] != "undefined" && typeof cells[i][j] != "undefined") {
                let cell = gameField.rows[j].cells[i];
                openArea(cell);
            }
        }
    }        
}

let openArea = (cell) => {
    let x = cell.cellIndex;
    let y = cell.parentNode.rowIndex;
	if (cells[x][y]['opened'] === 0 && cells[x][y]['marked'] === 0) {
		cells[x][y]['opened'] = 1;
        openCount++;
        
		if (cells[x][y]['nearbycount'] != 0){
			drawNumberInCell(cell, cells[x][y]['nearbycount']);
			return;
        } else {
        redrawCell(cell, 'empty');
        }
		
        openSurroundedCells(x, y);

    } else {
        return;
    }
}

let makeChangesForLostGame = (cell, replayMode) => {
    lostGame = 1;
    writeGameInfo(user, cells[0].length, bombsArray.length, "Игра проиграна");   
    
    for (let i = 0; i < bombsArray.length; i++) {
        let coordinates = bombsArray[i].split("_");
        let x = coordinates[0];
        let y = coordinates[1];
        let bombCell = gameField.rows[x].cells[y];

        if (cells[y][x]['marked'] != 1) {
            redrawCell(bombCell, 'bomb');
        } else {
            redrawCell(bombCell, 'defused');
        }

    }
    redrawCell(cell, 'boom');
    redrawCell(gameButton, 'lost');
    createFetch(replayMode);
}

let makeChangesForWonGame = (replayMode) => {
    wonGame = 1;
    writeGameInfo(user, cells[0].length, bombsArray.length, "Игра выиграна");
    for (let i = 0; i < bombsArray.length; i++) {
        let coordinates = bombsArray[i].split("_");
        let x = coordinates[0];
        let y = coordinates[1];
        let bombCell = gameField.rows[x].cells[y];
        redrawCell(bombCell, 'defused');
    }
    redrawCell(gameButton, 'won');
    createFetch(replayMode);
}

export let openCell = (cell, replayMode) => {
	if (lostGame || wonGame) {
        return;
    } else {
        let x = cell.cellIndex;
        let y = cell.parentNode.rowIndex;
        turnNumber++;

		if(cells[x][y]['opened'] != 1) {
			if(cells[x][y]['isbomb'] === 1) {
                writeTurnInfo('Игра проиграна', turnNumber, x, y, replayMode);
                makeChangesForLostGame(cell, replayMode);
			}
			else {
                writeTurnInfo('Открыта область', turnNumber, x, y, replayMode);
                openArea(cell);
            }
        }

		if (openCount === cells.length * cells[0].length) {
            writeTurnInfo('Игра выиграна', turnNumber, x, y, replayMode);
            makeChangesForWonGame(replayMode);
        }
	}
}

async function createFetch(replayMode) 
{
    if (replayMode) {
        return;
    }
        
    await fetch('/games', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(gameInfo)
    });
    gameInfo = "";
}

export async function getGames()
{
    let response = await fetch('/games', {
        method: 'GET'
    });
    let json = await response.json();
    let jsonArray = json.split(";");
    let gamesList = createGamesList(jsonArray);
    drawGamesInfoTable(infoField, gamesList);
}

let createGamesList = (jsonArray) => {
    let gamesList = [];
    for (let i = 0; i < jsonArray.length - 1; i++) {
        let gameInfo = jsonArray[i].split("|");
        gamesList.push(
            {'gameId': gameInfo[0], 
            'username': gameInfo[3], 
            'date': gameInfo[1] + ", " + gameInfo[2], 
            'dimension': gameInfo[4], 
            'bombsCount': gameInfo[5], 
            'gameStatus': gameInfo[6]
        });
    }
    return gamesList;
}

let writeGameInfo = (username, dimension, bombsCount, gameStatus) => {
    gameInfo += username + "|" + dimension + "|" + bombsCount + "|" + gameStatus + ";";
    for (let i = 0; i < bombsArray.length - 1; i++)
    {
        gameInfo += bombsArray[i] + "|";
    }
    gameInfo += bombsArray[bombsArray.length - 1];
}

let writeTurnInfo = (gameStatus, turnNumber, x, y, replayMode) => {
    if (replayMode) {
        return;
    }
    let coordinates = x.toString() + "," + y.toString();
    gameInfo += turnNumber + "|" + coordinates + "|" + gameStatus + ";";
}

let getConcreteGameTurns = (jsonArray) => {
    let concreteGameTurns = [];
    for (let i = 0; i < jsonArray.length - 2; i++) {
        let gameInfo = jsonArray[i].split("|");
        concreteGameTurns.push({
        'turnNumber': gameInfo[0], 
        'coordinates': gameInfo[1], 
        'gameStatus': gameInfo[2], 
        });
    }
    return concreteGameTurns;
}

let getConcreteGameBombs = (bombsString) => {
    let concreteGameBombs = bombsString.split("|");
    for (let i = 0; i < concreteGameBombs.length; i++) {
        concreteGameBombs[i] = concreteGameBombs[i].replace(',', '_');
    }
    return concreteGameBombs;
}

export async function startReplay(gameId) {
    if (lostGame === 1)
        changeGameButtonPicture(gameButton, 'lost');

    if (wonGame === 1)
        changeGameButtonPicture(gameButton, 'won');

    let response = await fetch('/games/' + gameId, {
        method: 'GET'
    });
    let json = await response.json();
    let jsonArray = json.split(";");
    
    let concreteGameTurns = getConcreteGameTurns(jsonArray);
    bombsArray = getConcreteGameBombs(jsonArray[jsonArray.length - 1]);

    let gameInfo = jsonArray[jsonArray.length - 2].split("|");
    let dimension = gameInfo[4];
    let bombsCount = gameInfo[5];

    openCount = 0;
    lostGame = 0;
    wonGame = 0;
    flagCount = 0;
    turnNumber = 0;
    bombs = bombsCount;

	cells = [];
    
	for (let i = 0; i < dimension; i++) {
	 	let concreteCell = [];
	 	for (let j = 0; j < dimension; j++) {
            concreteCell.push({'opened': 0, 'isbomb': 0, 'nearbycount': 0, 'marked': 0});
 		}
        cells.push(concreteCell);
    }

    drawField(gameField, dimension);
    deployBombs(bombsCount);
    createReplay(gameId, concreteGameTurns);
}

async function createReplay(gameId, concreteGameTurns)
{
    for (let i = 0; i < concreteGameTurns.length; i++) {
        let coordinatesArray = concreteGameTurns[i]['coordinates'].split(',');
        let y = coordinatesArray[0];
        let x = coordinatesArray[1];
        let cell = gameField.rows[x].cells[y];
        
        
        if (concreteGameTurns[i]['gameStatus'] === 'Поставлен флаг' || concreteGameTurns[i]['gameStatus'] === 'Убран флаг'){
            setTimeout(setFlag, (i + 1) * 1000, cell, true);
        }
        else {
            if (contains(bombsArray, x + "_" + y) && concreteGameTurns[i]['gameStatus'] === 'Игра выиграна') {
                setTimeout(setFlag, (i + 1) * 1000, cell, true);
            } else {
                setTimeout(openCell, (i + 1) * 1000, cell, true);
            }
        }
    }

    drawConcreteGameTable(infoField, concreteGameTurns, gameId);
}
