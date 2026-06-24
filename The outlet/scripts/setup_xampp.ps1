# Copy The Outlet project into XAMPP htdocs for local hosting.
$ErrorActionPreference = "Stop"

$source = Split-Path $PSScriptRoot -Parent

$htdocs = "C:\xampp\htdocs\labelloom"

if (-not (Test-Path "C:\xampp\htdocs")) {
    Write-Host "XAMPP not found at C:\xampp" -ForegroundColor Red
    Write-Host "Install XAMPP from https://www.apachefriends.org/ then run this script again."
    exit 1
}

Write-Host "Copying project to $htdocs ..."
if (Test-Path $htdocs) {
    Remove-Item $htdocs -Recurse -Force
}
New-Item -ItemType Directory -Path $htdocs -Force | Out-Null
Copy-Item -Path (Join-Path $source "*") -Destination $htdocs -Recurse -Force

$uploads = Join-Path $htdocs "uploads"
if (-not (Test-Path $uploads)) {
    New-Item -ItemType Directory -Path $uploads | Out-Null
}

Write-Host ""
Write-Host "Done. Next steps:" -ForegroundColor Green
Write-Host "  1. Start Apache and MySQL in XAMPP Control Panel"
Write-Host "  2. Import database.sql in phpMyAdmin (database name: labelloom)"
Write-Host "  3. Open http://localhost/labelloom/"
Write-Host "  4. Diagrams PDF: http://localhost/labelloom/docs/diagrams/The Outlet_Design_Diagrams.pdf"
