<?php

namespace sazonov_da\minesweeper\View;

use function sazonov_da\minesweeper\Model\findSymbol;

function showGamesInfo($row)
{
    \cli\line(
        "ID: $row[0]\nДата: $row[1] $row[2]\nИмя игрока: $row[3]\nРазмерность: "
        . "$row[4]\nКол-во бомб: $row[5]\nСтатус игры: $row[6]"
    );
}

function showTurnInfo($row)
{
    \cli\line("Номер хода: $row[0]; координаты: $row[1]; статус: $row[2]");
}

function showGame($turnCount)
{
    \cli\line(sprintf('%20s', "ХОД №" . $turnCount));
    $line = sprintf('%2s', ' ');
    for ($i = 0; $i < DIMENSION; $i++) {
        $line .= sprintf('%2s', $i);
    }
    \cli\line($line);
    $line = '';

    for ($i = 0; $i < DIMENSION; $i++) {
        $line .= sprintf('%2s', $i);
        for ($j = 0; $j < DIMENSION; $j++) {
            $line .= findSymbol($i, $j);
        }
        \cli\line($line);
        $line = '';
    }
}