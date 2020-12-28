let infoBlock = document.getElementById('info');
let gameBlock = document.getElementById('game');
let headerBlock = document.getElementById('header');

export let redrawCell = (element, picture) => {
    element.classList.add(picture);
}

export let drawField = (element, n) => {
    unsetClass(gameBlock, 'hidden');
    setClass(infoBlock, 'hidden');
    setClass(headerBlock, 'hidden');
    unsetClass(gameBlock, 'replayMode');
    let html = "";
    for(let i = 0; i < n; i++) {
        html += "<tr>";
        for(let j = 0; j < n; j++) {
            html += "<td class = 'cell'></td>";
        }
    }
    element.innerHTML = html;
}

export let drawGamesInfoTable = (element, array) => {
    setClass(gameBlock, 'hidden');
    unsetClass(infoBlock, 'hidden');
    unsetClass(headerBlock, 'hidden');
    headerBlock.innerHTML = "<h2>Информация об играх</h2>";
    let html = "<tr>";
    html += "<th>id игры</th>";
    html += "<th>Имя игрока</th>";
    html += "<th>Дата игры</th>";
    html += "<th>Размерность поля</th>";
    html += "<th>Количество бомб</th>";
    html += "<th>Статус игры</th>";
    html += "</tr>";
    for(let i = 0; i < array.length; i++) {
        html += "<tr>";
        html += "<td>" + array[i]['gameId'] + "</td>";
        html += "<td>" + array[i]['username'] + "</td>";
        html += "<td>" + array[i]['date'] + "</td>";
        html += "<td>" + array[i]['dimension'] + "</td>";
        html += "<td>" + array[i]['bombsCount'] + "</td>";
        html += "<td>" + array[i]['gameStatus'] + "</td>";
        html += "</tr>";
    }
    element.innerHTML = html;
}

export let drawConcreteGameTable = (element, array, gameId) => {
    unsetClass(gameBlock, 'hidden');
    unsetClass(infoBlock, 'hidden');
    unsetClass(headerBlock, 'hidden');
    setClass(gameBlock, 'replayMode');
    headerBlock.innerHTML = "<h2>Информация об игре с id = " + gameId + "</h2>";
    let html = "<tr>";
    html += "<th>Номер хода</th>";
    html += "<th>Координаты ячейки</th>";
    html += "<th>Результат хода</th>";
    html += "</tr>";
    for(let i = 0; i < array.length; i++) {
        html += "<tr>";
        html += "<td>" + array[i]['turnNumber'] + "</td>";
        html += "<td>" + array[i]['coordinates'] + "</td>";
        html += "<td>" + array[i]['gameStatus'] + "</td>";
        html += "</tr>";
    }
    element.innerHTML = html;
}

export let changeGameButtonPicture = (element, picture) => {
    element.classList.remove(picture);
}

export let drawNumberInCell = (element, number) => {
    element.innerHTML = number.toString();
    element.classList.add('number');
}

export let deleteFlag = (element) => {
    element.classList.remove('flag');
}

let setClass = (element, className) => {
    element.classList.add(className);
}

let unsetClass = (element, className) => {
    element.classList.remove(className);
}
