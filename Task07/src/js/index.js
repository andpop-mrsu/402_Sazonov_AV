import { generateGrid } from "./View.js";
import { getResultGame } from "./Model.js";

export const grid = document.getElementById("grid");

const resetButton = document.getElementById("reset");
const startButton = document.getElementById("start");
const showResultsButton = document.getElementById("showResults");

const gameBlock = document.getElementById("game");
const initForm = document.getElementById("init-form");

export let userName, fieldDimension, numberOfMines;

const startGame = () => {
    generateGrid();
}


resetButton.onclick = () => {
    gameBlock.style = "display: none";
    initForm.style = "display: block";
}

startButton.onclick = () => {
    userName = document.getElementById("nameUser").value;
    fieldDimension = +document.getElementById("fieldDimension").value;
    numberOfMines = +document.getElementById("numberOfMines").value;
    
    if(userName && fieldDimension && numberOfMines) {
        initForm.style = "display: none"
        gameBlock.style = "display: block";

        startGame();
    } else {       
        const errorBlock = document.getElementById("show-error");

        errorBlock.style = "display: block"
    }
}

showResultsButton.onclick = () => {
    getResultGame();
}
