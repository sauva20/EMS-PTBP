$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false
$excel.DisplayAlerts = $false
$wb = $excel.Workbooks.Open('C:\Users\2qist\Downloads\2025 CO2 Emission- BMI calculation.xlsx', $false, $true)

Write-Output "=== SHEET 01 (JANUARY) ==="
$s1 = $wb.Sheets.Item('01_CO2 Emission by Building ')
for ($r = 18; $r -le 32; $r++) {
    $row = @()
    for ($c = 1; $c -le 13; $c++) {
        $cell = $s1.Cells.Item($r, $c)
        $val = $cell.Value2
        $row += "$val"
    }
    Write-Output "S1 R$r : " + ($row -join ' | ')
}

Write-Output "=== SHEET 02 (FEBRUARY) ==="
$s2 = $wb.Sheets.Item('02_CO2 Emission by Building')
for ($r = 18; $r -le 32; $r++) {
    $row = @()
    for ($c = 1; $c -le 13; $c++) {
        $cell = $s2.Cells.Item($r, $c)
        $val = $cell.Value2
        $row += "$val"
    }
    Write-Output "S2 R$r : " + ($row -join ' | ')
}

$wb.Close($false)
$excel.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null
