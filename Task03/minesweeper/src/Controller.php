<?php 
	namespace sazonov_da\minesweeper\Controller;

use function sazonov_da\minesweeper\View\showGame;
use function sazonov_da\minesweeper\Model\createVars;
use function sazonov_da\minesweeper\Model\createCellsArray;
use function sazonov_da\minesweeper\Model\isBomb;
use function sazonov_da\minesweeper\Model\openArea;
use function sazonov_da\minesweeper\Model\setFlag;

function gameLoop()
{
    global $cellsArray, $lostGame, $openedCellsCount;
    $turnCount = 1;
    while (true) {
        showGame($turnCount);
        $turnCount++;

        $inputString = \cli\prompt(
            "Введите координаты x, y ячейки через "
            . "запятую без пробела.\nЕсли хотите "
            . "установить флаг в ячейку, то введите "
            . "F после ввода координат"
        );
        $inputArray = explode(',', $inputString);
        if (
            !isset($inputArray[0]) || !isset($inputArray[1])
            || preg_match('/^[0-9]{1}$/', $inputArray[0]) == 0
            || preg_match('/^[0-9]{1}$/', $inputArray[1]) == 0
        ) {
            \cli\line("Неверно введены данные! Попробуйте еще раз");
            $turnCount--;
        } else {
            if (
                isset($inputArray[2])
                && ($inputArray[2] == 'F' || $inputArray[2] == 'f')
            ) {
                setFlag($inputArray[0], $inputArray[1]);
            } else {
                if (isBomb($inputArray[0], $inputArray[1])) {
                    showGame($turnCount);
                    \cli\line("GAME OVER");
                    break;
                } else {
                    openArea($inputArray[0], $inputArray[1]);
                    if ($openedCellsCount == count($cellsArray) * count($cellsArray[0])) {
                        showGame($turnCount);
                        \cli\line("CONGRATULATIONS! YOU WON");
                        break;
                    }
                }
            }
        }
    }
}

function startGame()
{
    createVars();
    createCellsArray();
    gameLoop();
}