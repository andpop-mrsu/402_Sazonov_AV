<?php 
	namespace sazonov_da\minesweeper\View;
	
function showGame($turnCount)
{
    global $cellsArray , $bombsArray;
    \cli\line(sprintf('%20s', "ХОД №" . $turnCount));
    $line = sprintf('%2s', ' ');
    for ($i = 0; $i < MAX_X; $i++) {
        $line .= sprintf('%2s', $i);
    }
    \cli\line($line);
    $line = '';

    for ($i = 0; $i < MAX_Y; $i++) {
        $line .= sprintf('%2s', $i);
        for ($j = 0; $j < MAX_X; $j++) {
            if ($cellsArray[$i][$j]['opened'] == true) {
                if ($cellsArray[$i][$j]['marked'] == true) {
                    $line .= sprintf('%2s', 'F');
                } else {
                    if ($cellsArray[$i][$j]['isbomb'] == true) {
                        $line .= sprintf('%2s', '*');
                    } else {
                        if ($cellsArray[$i][$j]['nearbycount'] == 0) {
                            $line .= sprintf('%2s', '-');
                        } else {
                            $line .= sprintf(
                                '%2s',
                                $cellsArray[$i][$j]['nearbycount']
                            );
                        }
                    }
                }
            } else {
                $line .= sprintf('%2s', '.');
            }
        }
        \cli\line($line);
        $line = '';
    }
}