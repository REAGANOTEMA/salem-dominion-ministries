@echo off
echo Importing database structure for Salem Dominion Ministries...

cd c:\xampp\mysql\bin
mysql.exe -u root -pReagaN23# salem-dominion-ministries < "c:\xampp\htdocs\salem-dominion-ministries\backend\database_structure.sql"

echo Database import completed!
pause
