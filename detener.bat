@echo off
title Detener Libreria JILS
echo ==============================================
echo   Deteniendo Libreria JILS (Docker)...
echo ==============================================
echo.

docker compose down

echo.
echo ==============================================
echo   El proyecto se detuvo correctamente.
echo ==============================================
timeout /t 3 >nul
