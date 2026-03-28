@echo off
title Salem Dominion Ministries - Live Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         LIVE SERVER STARTUP
echo ========================================
echo.

REM Kill any existing processes
taskkill /f /im php.exe 2>nul
taskkill /f /im node.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting PHP Built-in Server...
echo Server: http://localhost:4176
echo Document Root: %CD%\live
echo.
echo Press Ctrl+C to stop server
echo.

REM Start PHP built-in server
cd /d "%CD%\live"
php -S localhost:4176

pause
