import { addMines, clickCell, addFlag, dataBase } from "./Model.js";
import { grid, fieldDimension } from "./index.js";

export const generateGrid = () => {
  grid.innerHTML="";

  for (let i=0; i<fieldDimension; i++) {
    let row = grid.insertRow(i);
  
    for (let j=0; j<fieldDimension; j++) {
  
      let cell = row.insertCell(j);
        
      cell.onclick = function() { clickCell(this); };
        
      cell.oncontextmenu = function() {addFlag(this) };

      let mine = document.createAttribute("data-mine"); 
      mine.value = "false"; 
  
      cell.setAttributeNode(mine);
    }
  }
  addMines();
}

export const showResults = (games) => {
  let tableResult = "";

  games.forEach((result, index) => {
  console.log("result", result);
    tableResult += 
    ` <div>
        <span>Игра №${index}</span>
        <span>Имя игрока: ${result.userName}</span>
        <span>Дата игры: ${result.dateGame}</span>
        <span>Количество ячеек: ${result.fieldDimension}</span>
        <span>Количество мин: ${result.numberOfMines}</span>
        <span>Расположение мин: ${showLocationMines(result.arrangementOfMines)}</span>
      </div>
    `;
  });

  document.getElementById("result").innerHTML = tableResult;
}

const showLocationMines = (arrangementOfMines) => {
  let locationMines = "";

  arrangementOfMines.forEach(item => {
    locationMines += `${item} `
  });

  return locationMines;
}



  