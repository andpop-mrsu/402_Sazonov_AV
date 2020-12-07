import { grid, userName, fieldDimension, numberOfMines } from "./index.js";
import { showResults } from "./View.js";

let countFlag = 0;
export let dataBase;
let arrangementOfMines = []; 

const getTransformableTimeValue = (time) => {
  return time > 10 ? time : `0${time}`;
}

const getDateAndTime = () => {
  const date = new Date();
  const month =  getTransformableTimeValue(+date.getMonth());
  const hours =  getTransformableTimeValue(+date.getHours());
  const minutes = getTransformableTimeValue(date.getMinutes());
  const seconds = getTransformableTimeValue(date.getSeconds());

  return `${date.getDate()}. ${month}.${date.getFullYear()} (${hours}:${minutes}:${seconds})`;
}

export const addMines = () => {
    countFlag = 0;
    for (let i=0; i<numberOfMines; i++) {
        const row = Math.floor(Math.random() * fieldDimension);
        const col = Math.floor(Math.random() * fieldDimension);
        const cell = grid.rows[row].cells[col];
        arrangementOfMines.push(`${row + 1}: ${col + 1}`);
        cell.setAttribute("data-mine","true");
    }

    init();
}

export const addFlag = (cell) => {
  if(countFlag === numberOfMines) {
    return;
  }

  if(cell.getAttribute("data-flag") === "true") {

    cell.setAttribute("data-flag", "false");
    cell.className="";
    cell.innerHTML="";
    countFlag--;
    
  } else {
    cell.setAttribute("data-flag", "true");
    cell.className="flag";
    cell.innerHTML="!";
    countFlag++;
  }
}

async function init() {
  dataBase = await idb.openDb('gamesDb', 1, dataBase => {
    dataBase.createObjectStore('games', { autoIncrement: true });
  });

}

 export async function getResultGame() {
  const tx = dataBase.transaction('games');
  const gameStore = tx.objectStore('games');

  let games = await gameStore.getAll();

  showResults(games);
}

async function addGameDataBase(result) {
  let tx = dataBase.transaction('games', 'readwrite');

  const dateGame = getDateAndTime();
  try {
    await tx.objectStore('games').add({
      dateGame,
      userName,
      fieldDimension,
      numberOfMines,
      arrangementOfMines,
      result,
    });

    arrangementOfMines = [];

  } catch(err) {
    if (err.id == 'ConstraintError') {
      alert("Уже существует");
      await addBook();
    } else {
      throw err;
    }
  }
}

export const clickCell = (cell) => {
      let cellRow = cell.parentNode.rowIndex;
      let cellCol = cell.cellIndex;

    if (cell.getAttribute("data-mine") === "true") {
      revealMines("lost");     
    } else if(cell.getAttribute("data-flag") === "true") {
      return;
    } else {
      cell.className="clicked";

      let mineCount=0;
      

      for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,fieldDimension - 1); i++) {
        for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,fieldDimension - 1); j++) {
          if (grid.rows[i].cells[j].getAttribute("data-mine")=="true") mineCount++;
        }
      }
      cell.innerHTML=mineCount;
      if (mineCount === 0) { 
        for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,fieldDimension - 1); i++) {
          for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,fieldDimension - 1); j++) {

            if (grid.rows[i].cells[j].innerHTML=="") {
              clickCell(grid.rows[i].cells[j]);
            }
          }
        }
      }
      checkLevelCompletion();
    }
}

const checkLevelCompletion = () => {
    let levelComplete = true;
    let count = 0;
      for (let i=0; i<fieldDimension; i++) {
        for(let j=0; j<fieldDimension; j++) {

          if ((grid.rows[i].cells[j].getAttribute("data-mine") == "false") && (grid.rows[i].cells[j].innerHTML=="")) {
            levelComplete = false;
          }

          if(grid.rows[i].cells[j].innerHTML=="") {
            count++;
          }
        }
    }
    if (levelComplete) {
      revealMines("won", count);
    }
}

const revealMines = (result, count = 0) => {
    for (let i=0; i<fieldDimension; i++) {
      for(let j=0; j<fieldDimension; j++) {
        let cell = grid.rows[i].cells[j];
        if (cell.getAttribute("data-mine")=="true") {
          cell.className="mine";
          cell.innerHTML="X";
        } 
      }
    }

    if(result === "won") {
      if(count > 1) {
        count--;
      } else {
        alert("Выиграл");
        addGameDataBase("Выиграл");
      }
    } else {
      alert("Проиграл");
      addGameDataBase("Проиграл");
    }
}

