@echo off
title Detener Sistema Restaurante
color 0C
echo ===================================================
echo   Deteniendo Servidores - Sistema Restaurante
echo ===================================================
echo.

echo Deteniendo servidor en puerto 8000...

:: 1. Detener por puerto 8000 usando PowerShell (funciona en cualquier idioma de Windows)
powershell -NoProfile -ExecutionPolicy Bypass -Command "Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue | ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }" >nul 2>&1

:: 2. Detener cualquier proceso php que este corriendo artisan serve
powershell -NoProfile -ExecutionPolicy Bypass -Command "Get-CimInstance Win32_Process -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like '*artisan serve*' } | ForEach-Object { Stop-Process -Id $_.ProcessId -Force -ErrorAction SilentlyContinue }" >nul 2>&1

echo.
echo [OK] El servidor del Sistema Restaurante se ha detenido correctamente.
echo.
timeout /t 2 >nul


