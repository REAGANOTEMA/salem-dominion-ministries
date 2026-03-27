@echo off
title Salem Dominion Ministries - Frontend PWA Server
color 0A

echo.
echo ========================================
echo    SALEM DOMINION MINISTRIES
echo      FRONTEND PWA SERVER
echo ========================================
echo.

REM Kill any existing processes
taskkill /f /im node.exe 2>nul
taskkill /f /im python.exe 2>nul

REM Wait for processes to terminate
timeout /t 2 /nobreak >nul

echo Starting Frontend PWA Server...
echo Server: http://localhost:5173
echo Frontend Root: %CD%\frontend
echo.
echo This server includes:
echo   - React Frontend with PWA features
echo   - Service Worker (sw.js)
echo   - PWA Manifest (manifest.json)
echo   - Offline Support
echo   - Push Notifications
echo   - App Installation
echo   - Background Sync
echo   - Cache Management
echo   - Mobile Responsive Design
echo   - TypeScript (Error-Free)
echo.
echo Press Ctrl+C to stop server
echo.

REM Change to frontend directory
cd /d "%CD%\frontend"

REM Start Vite development server
npm run dev

pause
