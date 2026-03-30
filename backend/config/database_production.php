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
        // Production Database Configuration
        // Update these values with your hosting platform details
        $this->host = 'localhost'; // or your hosting database host
        $this->username = 'salemdominionmin_db'; // your hosting database username
        $this->password = 'BAqC3nfhKmsFmkhMqCb8'; // your hosting database password
        $this->database = ' salem_dominion_ministriess'; // your database name
        $this->charset = 'utf8mb4';
        $this->port = 3306;

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
