@echo off
:: Cambiar al directorio donde se encuentra este archivo .bat
cd /d "%~dp0"

echo ===================================================
echo   Iniciando Servidores - Sistema Restaurante
echo ===================================================
echo.

:: Iniciar el servidor local de Laravel (PHP Artisan)
echo [1/2] Iniciando PHP Artisan Serve...
start "Laravel Server" cmd /k "php artisan serve --host 0.0.0.0"

:: Iniciar el servidor de desarrollo de Vite
echo [2/2] Iniciando NPM Run Dev (Vite)...
start "Vite Server" cmd /k "npm run dev"

echo.
echo ===================================================
echo Servidores iniciados en ventanas separadas.
echo Ya puedes cerrar esta ventana principal.
echo ===================================================
echo.
pause
