$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false
$excel.DisplayAlerts = $false
$wb = $excel.Workbooks.Open('C:\Users\2qist\Downloads\2025 CO2 Emission- BMI calculation.xlsx', $false, $true)

$sheet = $wb.Sheets.Item('02_CO2 Emission by Building')
for ($r = 15; $r -le 35; $r++) {
    $row = @()
    for ($c = 1; $c -le 14; $c++) {
        $cell = $sheet.Cells.Item($r, $c)
        $val = $cell.Value2
        $f = $cell.Formula
        if ($f -match '^=') {
            $row += "$val [$f]"
        } else {
            $row += "$val"
        }
    }
    Write-Output "ROW $r : " + ($row -join ' | ')
}

$wb.Close($false)
$excel.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null
