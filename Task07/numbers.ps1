function Show-Date_Info {
"Show-Date_Info"
$date = Get-Date
"Сегодня: "+$date.ToString("dd.mm.yyyy")
$day = $date.Day
Invoke-RestMethod http://numbersapi.com/$day
$month = $date.Month
Invoke-RestMethod http://numbersapi.com/$month
$year = $date.Year
Invoke-RestMethod http://numbersapi.com/$year
}

Show-Date_Info