@echo off
title Salem Dominion Ministries - PWA Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo         PWA SERVER
echo ========================================
echo.

REM Kill any existing processes
taskkill /f /im python.exe 2>nul
taskkill /f /im php.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting PWA Server...
echo Server: http://localhost:8080
echo Document Root: %CD%\live
echo.
echo This server includes:
echo   - PWA Manifest (manifest.json)
echo   - Service Worker (sw.js)
echo   - Offline Page (offline.html)
echo   - All Icons and Assets
echo   - Progressive Web App Features
echo   - Installable on Mobile/Desktop
echo   - Offline Functionality
echo   - Push Notifications
echo   - Background Sync
echo   - App Shortcuts
echo.
echo Press Ctrl+C to stop server
echo.

REM Start Python HTTP server
cd /d "%CD%\live"
start /B python -m http.server 8080

pause
