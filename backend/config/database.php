<?php
class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $port;
    private $conn;
    
    public function __construct() {
        $this->loadEnv(__DIR__ . '/../.env');

        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
        $this->database = getenv('DB_NAME') ?: 'salem_dominion_ministries';
        $this->charset = getenv('DB_CHARSET') ?: 'utf8mb4';
        $this->port = getenv('DB_PORT') ?: 3306;

        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        
        // Set charset to utf8mb4
        $this->conn->set_charset($this->charset);
    }

    private function loadEnv($envPath) {
        if (!file_exists($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (strpos($line, '=') === false) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (strlen($value) > 1 && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                $value = substr($value, 1, -1);
            }

            if (getenv($name) === false) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result === false) {
            return ['success' => false, 'error' => $stmt->error];
        }
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        
        return ['success' => true, 'data' => $data];
    }
    
    public function insert($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            return ['success' => false, 'error' => $this->conn->error];
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if ($stmt->error) {
            $stmt->close();
            return ['success' => false, 'error' => $stmt->error];
        }
        
        $insertId = $this->conn->insert_id;
        $stmt->close();
        
        return ['success' => true, 'insert_id' => $insertId];
    }
    
    public function update($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            return ['success' => false, 'error' => $this->conn->error];
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if ($stmt->error) {
            $stmt->close();
            return ['success' => false, 'error' => $stmt->error];
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        return ['success' => true, 'affected_rows' => $affectedRows];
    }
    
    public function delete($sql, $params = []) {
        return $this->update($sql, $params);
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
