$ExcelObj = New-Object -COM Excel.Application
$ExcelWorkBook = $ExcelObj.Workbooks.Add()
$ExcelWorkSheet = $ExcelWorkBook.Worksheets.Item(1)
$ExcelWorkSheet.Cells.Item(2,2) = "Привет от PowerShell"
$ExcelWorkSheet.Cells.Item(2,2).Font.Italic = $true
$ExcelWorkSheet.Cells.Item(2,2).Font.size=12
$ExcelWorkSheet.Columns.Item(2).ColumnWidth=25
$ExcelWorkBook.SaveAs("C:\Users\$env:UserName\Desktop\"+$env:UserName+"_"+$env:ComputerName+".xlsx")
$ExcelWorkBook.close($true)