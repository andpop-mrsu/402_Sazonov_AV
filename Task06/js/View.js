import { addMines, clickCell } from "./Model.js";
import { grid } from "./index.js";

export const generateGrid = () => {
    grid.innerHTML="";
    for (let i=0; i<10; i++) {
      let row = grid.insertRow(i);

      for (let j=0; j<10; j++) {

        let cell = row.insertCell(j);

        cell.onclick = function() { clickCell(this); };

        let mine = document.createAttribute("data-mine"); 
        mine.value = "false"; 

        cell.setAttributeNode(mine);
      }
    }
    addMines();
  }