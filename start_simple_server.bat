@echo off
title Salem Dominion Ministries - Simple Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         SIMPLE SERVER STARTUP
echo ========================================
echo.

REM Kill any existing PHP processes
taskkill /f /im php.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting PHP Server...
echo Server: http://localhost:8080
echo Document Root: %CD%\live
echo.
echo Press Ctrl+C to stop server
echo.

REM Start PHP built-in server
cd /d "%CD%\live"
start /B php -S localhost:8080

pause
