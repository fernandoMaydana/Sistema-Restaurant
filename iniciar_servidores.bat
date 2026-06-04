@echo off
:: Cambiar al directorio donde se encuentra este archivo .bat
cd /d "%~dp0"

echo ===================================================
echo   Iniciando Servidores - Sistema Restaurante
echo ===================================================
echo.

:: 1. Verificar e iniciar MySQL si no está corriendo
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe" >NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL ya esta en ejecucion.
) else (
    if exist "C:\xampp\mysql_start.bat" (
        echo [1/3] Iniciando MySQL desde XAMPP...
        start "MySQL (XAMPP)" cmd /c "C:\xampp\mysql_start.bat"
        :: Esperar 3 segundos para asegurar que MySQL levante antes de que Laravel intente conectar
        timeout /t 3 /nobreak >nul
    ) else (
        echo [ALERTA] No se encontro MySQL en C:\xampp. Asegurate de iniciarlo manualmente.
    )
)

:: 2. Iniciar el servidor local de Laravel (PHP Artisan)
echo [2/3] Iniciando PHP Artisan Serve...
start "Laravel Server" cmd /k "php artisan serve --host 0.0.0.0"

:: 3. Iniciar el servidor de desarrollo de Vite
echo [3/3] Iniciando NPM Run Dev (Vite)...
start "Vite Server" cmd /k "npm run dev"

echo.
echo ===================================================
echo Servidores y Base de Datos listos.
echo Ya puedes cerrar esta ventana principal.
echo ===================================================
echo.
pause

