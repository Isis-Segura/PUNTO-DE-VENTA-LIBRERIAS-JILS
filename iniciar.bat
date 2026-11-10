@echo off
title Iniciar Libreria JILS
echo ==============================================
echo   Iniciando Punto de Venta Librerias JILS...
echo ==============================================
echo.

docker compose up -d --build
if errorlevel 1 (
    echo.
    echo ERROR: No se pudieron iniciar los contenedores.
    echo Revisa que Docker Desktop este iniciado y que los puertos necesarios esten disponibles.
    pause
    exit /b 1
)

echo.
echo ==============================================
echo   Contenedores iniciados. Abriendo el sistema...
echo ==============================================
timeout /t 3 >nul

start http://127.0.0.1:8000
start http://127.0.0.1:8081
