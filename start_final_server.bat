@echo off
title Salem Dominion Ministries - Final Live Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         FINAL LIVE SERVER
echo ========================================
echo.

REM Kill any existing processes
taskkill /f /im php.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting Final Live Server...
echo Server: http://localhost:8080
echo Document Root: %CD%\live
echo.
echo This server includes:
echo   - Proper MIME types for all files
echo   - CORS headers for cross-origin requests
echo   - API proxy to backend
echo   - SPA routing for React app
echo   - Static file serving with caching
echo.
echo Press Ctrl+C to stop server
echo.

REM Start PHP built-in server with all interfaces
cd /d "%CD%\live"
start /B php -S 0.0.0.0:8080 -t .

pause
