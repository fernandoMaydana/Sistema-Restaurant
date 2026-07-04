@echo off
echo ===================================================
echo   Deteniendo Servidores - Sistema Restaurante
echo ===================================================
echo.

:: 1. Detener el servidor Laravel (PHP)
echo Deteniendo Laravel Server...
taskkill /f /im php.exe >nul 2>&1

echo.
echo [OK] El servidor de Laravel se ha detenido correctamente.
echo.
timeout /t 3
