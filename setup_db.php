<?php
$host = 'localhost';
$user = 'root';
$pass = 'ReagaN23#';

try {
    $conn = @new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        echo "Failed with ReagaN23#, trying empty password...\n";
        $pass = '';
        $conn = @new mysqli($host, $user, $pass);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    echo "Connected successfully! Password used: '" . $pass . "'\n";

    // Update config files if password is empty
    if ($pass === '') {
        $dbConfigPath = __DIR__ . '/backend/config/database.php';
        $envPath = __DIR__ . '/backend/.env';
        $liveConfigPath = __DIR__ . '/backend/config/live_config.php';
        
        // We will just report for now
        echo "Note: The database has an empty password but config files use 'ReagaN23#'.\n";
    }

    $conn->query("DROP DATABASE IF EXISTS `salem-dominion-ministries`");
    $conn->query("CREATE DATABASE `salem-dominion-ministries`");
    $conn->select_db("salem-dominion-ministries");
    
    $sql = file_get_contents(__DIR__ . '/backend/database_structure.sql');
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "Database imported successfully.\n";
    } else {
        echo "Error importing database: " . $conn->error . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
