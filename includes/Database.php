<?php
/**
 * Database Class
 * Singleton pattern for database connection and operations
 * 
 * @package FMWA
 * @version 1.0.0
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset = 'utf8mb4';
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        // Load configuration
        if (file_exists(__DIR__ . '/../config.php')) {
            require_once __DIR__ . '/../config.php';
            $this->host = DB_HOST;
            $this->dbname = DB_NAME;
            $this->username = DB_USER;
            $this->password = DB_PASS;
        } else {
            throw new Exception('Configuration file not found');
        }
        
        $this->connect();
    }
    
    /**
     * Get singleton instance
     * 
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => true,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get PDO instance
     * 
     * @return PDO
     */
    public function getPDO() {
        return $this->pdo;
    }
    
    /**
     * Execute a query and return all results
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    /**
     * Execute a query and return single result
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return object|null
     */
    public function fetch($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Fetch failed: " . $e->getMessage());
        }
    }
    
    /**
     * Insert data into table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int Last insert ID
     */
    public function insert($table, $data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }
    
    /**
     * Update data in table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause
     * @param array $whereParams WHERE parameters
     * @return int Number of affected rows
     */
    public function update($table, $data, $where, $whereParams = []) {
        try {
            $set = [];
            foreach (array_keys($data) as $column) {
                $set[] = "{$column} = :{$column}";
            }
            $setClause = implode(', ', $set);
            
            $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            
            foreach ($whereParams as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }
    
    /**
     * Delete data from table
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params WHERE parameters
     * @return int Number of affected rows
     */
    public function delete($table, $where, $params = []) {
        try {
            $sql = "DELETE FROM {$table} WHERE {$where}";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Delete failed: " . $e->getMessage());
        }
    }
    
    /**
     * Find record by ID
     * 
     * @param string $table Table name
     * @param int $id Record ID
     * @return object|null
     */
    public function find($table, $id) {
        return $this->fetch("SELECT * FROM {$table} WHERE id = ?", [$id]);
    }
    
    /**
     * Get all records from table
     * 
     * @param string $table Table name
     * @param string $orderBy ORDER BY clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array
     */
    public function all($table, $orderBy = 'id DESC', $limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$table} ORDER BY {$orderBy}";
        
        if ($limit !== null) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->query($sql);
    }
    
    /**
     * Count records in table
     * 
     * @param string $table Table name
     * @param string $where WHERE clause (optional)
     * @param array $params WHERE parameters
     * @return int
     */
    public function count($table, $where = null, $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->fetch($sql, $params);
        return (int) $result->count;
    }
    
    /**
     * Check if record exists
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params WHERE parameters
     * @return bool
     */
    public function exists($table, $where, $params = []) {
        return $this->count($table, $where, $params) > 0;
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->pdo->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->pdo->rollBack();
    }
    
    /**
     * Execute raw SQL
     * 
     * @param string $sql SQL query
     * @return bool
     */
    public function execute($sql) {
        try {
            return $this->pdo->exec($sql) !== false;
        } catch (PDOException $e) {
            throw new Exception("Execute failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get last insert ID
     * 
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Prevent cloning of instance
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization of instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
