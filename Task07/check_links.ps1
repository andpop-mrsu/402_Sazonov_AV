Get-Item "C:\Users\$env:UserName\Desktop\*.lnk" | ForEach-Object {
    $shell = New-Object -COM WScript.Shell
    $shortcut = $shell.CreateShortcut($_.FullName)
    $destination = "C:\Users\$env:UserName\Desktop\BadLinks\"
    If (!(Test-Path $shortcut.TargetPath)) { 
        If (!(Test-Path $destination)) { 
        New-Item -Path $destination -ItemType Directory
        } 
    Move-Item -Path $_.FullName -Destination $destination+$_.Name 
    }
}