import { grid } from "./index.js";

export const addMines = () => {

    for (let i=0; i<20; i++) {
        const row = Math.floor(Math.random() * 10);
        const col = Math.floor(Math.random() * 10);
        const cell = grid.rows[row].cells[col];

        cell.setAttribute("data-mine","true");
    }
}

export const clickCell = (cell) => {

    if (cell.getAttribute("data-mine") === "true") {
      revealMines();
      alert("Game Over");

    } else {
      cell.className="clicked";

      let mineCount=0;
      let cellRow = cell.parentNode.rowIndex;
      let cellCol = cell.cellIndex;

      for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,9); i++) {
        for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,9); j++) {
          if (grid.rows[i].cells[j].getAttribute("data-mine")=="true") mineCount++;
        }
      }
      cell.innerHTML=mineCount;
      if (mineCount==0) { 

        for (let i=Math.max(cellRow-1,0); i<=Math.min(cellRow+1,9); i++) {
          for(let j=Math.max(cellCol-1,0); j<=Math.min(cellCol+1,9); j++) {

            if (grid.rows[i].cells[j].innerHTML=="") clickCell(grid.rows[i].cells[j]);
          }
        }
      }
      checkLevelCompletion();
    }
}

const checkLevelCompletion = () => {
    let levelComplete = true;

      for (let i=0; i<10; i++) {
        for(let j=0; j<10; j++) {

          if ((grid.rows[i].cells[j].getAttribute("data-mine") == "false") && (grid.rows[i].cells[j].innerHTML=="")) levelComplete=false;
        }
    }
    if (levelComplete) {
      alert("You Win!");
      revealMines();
    }
}

const revealMines = () => {
    for (let i=0; i<10; i++) {
      for(let j=0; j<10; j++) {
        let cell = grid.rows[i].cells[j];
        if (cell.getAttribute("data-mine")=="true") {
          cell.className="mine";
          cell.innerHTML="X";
        } 
      }
    }
}