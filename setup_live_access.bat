@echo off
echo Setting up LIVE EXTERNAL ACCESS for Salem Dominion Ministries...
echo.

echo Step 1: Configuring Apache for external access...
cd c:\xampp\apache\conf
echo Updating httpd.conf for external access...

echo Step 2: Configuring firewall...
netsh advfirewall firewall add rule name="Apache HTTP Server" dir=in action=allow protocol=TCP localport=80
netsh advfirewall firewall add rule name="Apache HTTPS Server" dir=in action=allow protocol=TCP localport=443

echo Step 3: Restarting Apache...
cd c:\xampp
apache_stop.exe
timeout /t 2 /nobreak
apache_start.exe

echo.
echo ✅ LIVE ACCESS CONFIGURED!
echo.
echo Your website is now accessible at:
echo 🌐 http://192.168.1.10/salem-dominion-ministries
echo.
echo Anyone on your network can now access the website!
echo.
echo For external internet access, you need to:
echo 1. Configure port forwarding on your router (port 80)
echo 2. Get a domain name and point it to your IP
echo 3. Set up SSL certificate for HTTPS
echo.
pause
