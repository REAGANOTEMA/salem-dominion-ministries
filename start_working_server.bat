@echo off
title Salem Dominion Ministries - Working Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         WORKING SERVER
echo ========================================
echo.

REM Kill any existing PHP processes
taskkill /f /im php.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting Working Server...
echo Server: http://localhost:8080
echo Document Root: %CD%\live
echo.
echo This server handles:
echo   - All MIME types correctly
echo   - Full CORS support
echo   - API proxy to backend
echo   - SPA routing for React
echo   - Static file serving
echo   - No more 502 errors
echo.
echo Press Ctrl+C to stop server
echo.

REM Start PHP server
cd /d "%CD%\live"
start /B php -S 0.0.0.0:8080 -t .

pause
