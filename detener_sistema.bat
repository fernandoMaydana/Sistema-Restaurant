@echo off
echo ===================================================
echo   Deteniendo Servidores - Sistema Restaurante
echo ===================================================
echo.

:: 1. Detener el servidor Laravel (PHP)
echo Deteniendo Laravel Server...
taskkill /f /im php.exe >nul 2>&1

:: 2. Detener MySQL de XAMPP (intento limpio primero, luego forzado)
echo Deteniendo base de datos MySQL...
if exist "C:\xampp\mysql\bin\mysqladmin.exe" (
    "C:\xampp\mysql\bin\mysqladmin.exe" -u root shutdown >nul 2>&1
)
taskkill /f /im mysqld.exe >nul 2>&1

echo.
echo [OK] Todos los servicios se han detenido correctamente.
echo.
timeout /t 3
