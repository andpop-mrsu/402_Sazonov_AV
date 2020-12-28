import { createGameField, openCell, setFlag, initializeDatabase, getGames, startReplay } from './Model.js';

const gameField = document.getElementById("gameField");
const gameButton = document.getElementById("gameButton");
const startButton = document.getElementById("startGame");
const finishButton = document.getElementById("finishGame");
const gamesButton = document.getElementById("showGames");
const replayButton = document.getElementById("showReplay");
const playingField = document.getElementById("playingField");
const initForm = document.getElementById("initForm");

const startGame = () => {
    const userName = document.getElementById("nameUser").value;
    const fieldDimension = +document.getElementById("fieldDimension").value;
    const numberOfMines = +document.getElementById("numberOfMines").value;

    if(userName && fieldDimension && numberOfMines) {
        playingField.style="display: block";
        
        createGameField(fieldDimension, numberOfMines, userName);
    } 
}

const getReplay = () => {
    let gameId;
    gameId = +prompt("Введите id игры");
    startReplay(gameId);
}

window.onload = initializeDatabase;

startButton.onclick = () => {
    startButton.style = "display: none";
    finishButton.style = "display: block";
    initForm.style = "display: none";

    startGame();  
} 

gameButton.onclick = () => {
    startGame();
} 

gamesButton.onclick = () => {
    getGames();
}

replayButton.onclick = () => {
    getReplay();
} 

finishButton.onclick = () => {
    startButton.style = "display: block";
    finishButton.style = "display: none";
    initForm.style = "display: block";
    playingField.style="display: none"
}

gameField.onclick = (event) => {
    let td = event.target;

    if (td.tagName != 'TD') return;

    openCell(td, false);
}

gameField.oncontextmenu = (event) => {
    let td = event.target;

    if (td.tagName != 'TD') return;

    setFlag(td, false);
    return false;
}
