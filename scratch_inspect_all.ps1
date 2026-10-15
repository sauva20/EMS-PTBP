$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false
$excel.DisplayAlerts = $false
$wb = $excel.Workbooks.Open('C:\Users\2qist\Downloads\2025 CO2 Emission- BMI calculation.xlsx', $false, $true)

foreach ($name in @('01_CO2 Emission by Building ', '02_CO2 Emission by Building')) {
    Write-Output "========================================"
    Write-Output "SHEET: $name"
    Write-Output "========================================"
    $sheet = $wb.Sheets.Item($name)
    for ($r = 1; $r -le 40; $r++) {
        $row = @()
        for ($c = 1; $c -le 13; $c++) {
            $cell = $sheet.Cells.Item($r, $c)
            $text = $cell.Text
            $f = $cell.Formula
            if ($text -or $f) {
                if ($f -match '^=') {
                    $row += "Col$c = $text [$f]"
                } else {
                    $row += "Col$c = $text"
                }
            }
        }
        if ($row.Count -gt 0) {
            Write-Output "R$r | " + ($row -join ' ; ')
        }
    }
}

$wb.Close($false)
$excel.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($excel) | Out-Null
