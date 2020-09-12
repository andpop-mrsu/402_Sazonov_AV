@echo off
setlocal enabledelayedexpansion
chcp 65001>nul

echo.
echo create table if not exists logs(User varchar(10), Date text default current_timestamp); | sqlite3 logs.db
echo insert into logs values('%USERNAME%', datetime('now', 'localtime')); | sqlite3 logs.db

echo Имя программы: %~nx0
echo|<nul set /p="Количество запусков: "
echo select count(*) from logs; | sqlite3 logs.db
echo|<nul set /p="Первый запуск: "
echo select Date from logs order by Date asc limit 1; | sqlite3 logs.db

echo.
echo select * from logs; | sqlite3 -table logs.db
echo.

pause