@echo off
echo Starting Stable Production Server...
echo.

REM Kill any existing processes on ports 4176 and 5176
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :4176') do taskkill /f /pid %%a 2>nul
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :5176') do taskkill /f /pid %%a 2>nul

REM Wait a moment for processes to terminate
timeout /t 2 /nobreak >nul

REM Start stable production preview server
cd /d "c:\xampp\htdocs\salem-dominion-ministries\frontend"
echo Starting Stable Server on http://localhost:4176
echo.
echo Server will be accessible at:
echo - Local: http://localhost:4176
echo - Network: http://192.168.1.10:4176
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start the preview server
npx vite preview --host 0.0.0.0 --port 4176

pause
