@echo off
title Iniciar Libreria JILS
echo ==============================================
echo   Iniciando Punto de Venta Librerias JILS...
echo ==============================================
echo.

docker compose up -d
if errorlevel 1 (
    echo.
    echo Error al iniciar el proyecto.
    pause
    exit /b 1
)

echo.
echo ==============================================
echo   Listo! Abriendo el sistema en tu navegador...
echo ==============================================
timeout /t 3 >nul

start http://127.0.0.1:8000
start http://127.0.0.1:8081
