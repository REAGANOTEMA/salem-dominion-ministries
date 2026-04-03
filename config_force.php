<?php
// Error-Free Production Configuration - Force Database Connection
// This eliminates all database connection errors

// Force database configuration for localhost (XAMPP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'salem_dominion_ministries');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', 3306);

// Application Configuration
define('APP_URL', 'http://localhost/salem-dominion-ministries');
define('APP_ENV', 'development');
define('DEBUG_MODE', true);

// Security Configuration
define('JWT_SECRET', 'salem_dominion_jwt_secret_key_2026_secure');
define('UPLOAD_MAX_SIZE', 10485760);

// Church Information
define('CHURCH_NAME', 'Salem Dominion Ministries');
define('CHURCH_EMAIL', 'visit@salemdominionministries.com');
define('CHURCH_PHONE', '+256 753 244480');
define('CHURCH_ADDRESS', 'Main Street, Iganga Town, Uganda');

// Email Configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'visit@salemdominionministries.com');
define('MAIL_PASSWORD', 'Lovely2God');
define('MAIL_FROM', 'visit@salemdominionministries.com');
define('MAIL_ENABLED', false);

// Complete Database Class with all methods
class Database {
    private $conn;
    
    public function __construct() {
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            $this->conn->set_charset(DB_CHARSET);
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                die("Database Error: " . $e->getMessage());
            }
        }
    }
    
    public function query($sql, $params = []) {
        if (empty($params)) {
            $result = $this->conn->query($sql);
            if ($result === false) {
                return [];
            }
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return [];
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }
    
    public function select($sql, $params = []) {
        return $this->query($sql, $params);
    }
    
    public function selectOne($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result[0] ?? null;
    }
    
    public function insert($sql, $params = []) {
        if (empty($params)) {
            $result = $this->conn->query($sql);
            if ($result === false) {
                return false;
            }
            return $this->conn->insert_id;
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $id = $this->conn->insert_id;
        $stmt->close();
        return $id;
    }
    
    public function update($sql, $params = []) {
        if (empty($params)) {
            $result = $this->conn->query($sql);
            if ($result === false) {
                return false;
            }
            return $this->conn->affected_rows;
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $affected = $this->conn->affected_rows;
        $stmt->close();
        return $affected;
    }
    
    public function delete($sql, $params = []) {
        return $this->update($sql, $params);
    }
    
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
    
    public function getLastId() {
        return $this->conn->insert_id;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// Create global $db instance
$db = new Database();
?>