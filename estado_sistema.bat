@echo off
title Estado del Sistema Restaurante
color 0B
echo ===================================================
echo   ESTADO DEL SERVIDOR - SISTEMA RESTAURANTE
echo ===================================================
echo.

powershell -NoProfile -ExecutionPolicy Bypass -Command "if (Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue) { exit 0 } else { exit 1 }" >nul 2>&1

if %errorlevel% equ 0 (
    echo [ESTADO] :: SERVIDOR ACTIVO (Puerto 8000 escuchando)
    echo.
    echo Buscando IP local...
    for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4"') do (
        echo   - Acceso Local: http:%%a:8000
    )
    echo.
) else (
    echo [ESTADO] :: SERVIDOR APAGADO
    echo.
    echo Puedes encenderlo con 'iniciar_sistema.vbs'.
    echo.
)

echo ===================================================
echo Presione cualquier tecla para salir...
pause >nul

