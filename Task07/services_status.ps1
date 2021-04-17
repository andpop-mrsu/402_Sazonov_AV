Get-Service | ForEach-Object { If ( $_.status -Eq "Stopped" ) {
    Write-Host $_.status $_.name -Foregroundcolor red
    } Else {
        Write-Host $_.status $_.name -Foregroundcolor green
    }
}