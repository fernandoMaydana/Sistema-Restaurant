@echo off
title Actualizar Sistema Restaurante
color 0A
echo ========================================================
echo        ACTUALIZANDO SISTEMA RESTAURANTE
echo ========================================================
echo.

echo [1/3] Descargando ultimas actualizaciones desde GitHub...
git pull origin main

echo.
echo [2/3] Ejecutando migraciones de Base de Datos...
php artisan migrate --force

echo.
echo [3/3] Limpiando cache de vistas y configuracion...
php artisan view:clear
php artisan config:clear
php artisan cache:clear

echo.
echo ========================================================
echo   ¡ACTUALIZACION COMPLETADA EXITOSAMENTE!
echo ========================================================
echo Presione cualquier tecla para cerrar esta ventana...
pause > nul
