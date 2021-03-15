<?php

namespace App;

use App\Logger;

class DBLogger extends Logger
{
    public function log(string $date, string $time, string $description)
    {
        $db = $this -> connectToDB($this -> fileName . ".db");
        $db -> exec("INSERT INTO logs (logDate, logTime, logDescription) VALUES ('$date','$time','$description')");
    }

    private function connectToDB($fileName)
    {
        if (!file_exists("./logs/" . $fileName)) {
            echo "Файла нет";
            $db = new \SQLite3("./logs/" . $fileName);
            $db -> exec("CREATE TABLE logs(logDate DATE, logTime TIME, logDescription TEXT)");
        } else {
            $db = new \SQLite3("./logs/" . $fileName);
        }
        return $db;
    }
}