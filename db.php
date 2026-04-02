<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            $this->conn->set_charset(DB_CHARSET);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        // If no parameters, use direct query for compatibility
        if (empty($params)) {
            $result = $this->conn->query($sql);
            if ($result === false) {
                throw new Exception("Query failed: " . $this->conn->error);
            }
            return $result;
        }
        
        // Use prepared statement for parameterized queries
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            elseif (is_string($param)) $types .= 's';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        return $stmt->get_result();
    }

    public function select($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public function selectOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function update($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    public function delete($sql, $params = []) {
        return $this->update($sql, $params);
    }
}

$db = Database::getInstance()->getConnection();
?>