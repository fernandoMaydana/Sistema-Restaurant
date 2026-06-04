@echo off
:: Cambiar al directorio donde se encuentra este archivo .bat
cd /d "%~dp0"

echo ===================================================
echo   Iniciando Servidores - Sistema Restaurante
echo ===================================================
echo.

:: Iniciar el servidor local de Laravel (PHP Artisan)
echo [1/1] Iniciando PHP Artisan Serve...
start "Laravel Server" cmd /k "php artisan serve --host 0.0.0.0"

:: Abrir el navegador en el proyecto
echo.
echo Abriendo el navegador en http://192.168.0.7:8000 ...
start http://192.168.0.7:8000

echo.
echo ===================================================
echo Servidores en ejecucion.
echo Ya puedes cerrar esta ventana principal.
echo ===================================================
echo.
pause

