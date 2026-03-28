@echo off
title Salem Dominion Ministries - Live Server Fixed
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         LIVE SERVER - FIXED VERSION
echo ========================================
echo.

REM Kill any existing processes
taskkill /f /im php.exe 2>nul
taskkill /f /im node.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting Fixed Live Server...
echo Server: http://localhost:8080
echo Document Root: %CD%\live
echo.
echo This server handles:
echo   - Static file serving
echo   - API proxy to backend
echo   - SPA routing
echo   - CORS headers
echo.
echo Press Ctrl+C to stop server
echo.

REM Start the fixed server
cd /d "%CD%\live"
start /B php -S 0.0.0.0:8080

pause
