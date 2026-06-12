@echo off
echo ===================================================
echo   Deteniendo Servidores - Sistema Restaurante
echo ===================================================
echo.

:: Cerrar procesos de PHP (Artisan)
taskkill /f /im php.exe >nul 2>&1

echo [OK] El servidor se ha detenido correctamente.
echo.
timeout /t 3
