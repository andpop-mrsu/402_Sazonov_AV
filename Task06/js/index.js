import { generateGrid } from "./View.js";

export const grid = document.getElementById("grid");
const resetButton = document.getElementById("reset");

const startGame = () => {
    generateGrid();
}

startGame();

resetButton.onclick = () => {
    startGame();
} 