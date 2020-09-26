<?php namespace sazonov_da\minesweeper\Controller;
    use function sazonov_da\minesweeper\View\showGame;

    function startGame() {
        echo "Game started".PHP_EOL;
        showGame();
    }
?> 