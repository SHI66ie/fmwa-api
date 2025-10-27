<?php
/**
 * User Model Class
 * Handles user-related database operations
 * 
 * @package FMWA
 * @version 1.0.0
 */

require_once __DIR__ . '/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get user by ID
     * 
     * @param int $id User ID
     * @return object|null
     */
    public function getById($id) {
        return $this->db->find('users', $id);
    }
    
    /**
     * Get user by username
     * 
     * @param string $username Username
     * @return object|null
     */
    public function getByUsername($username) {
        return $this->db->fetch("SELECT * FROM users WHERE username = ?", [$username]);
    }
    
    /**
     * Get user by email
     * 
     * @param string $email Email address
     * @return object|null
     */
    public function getByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }
    
    /**
     * Authenticate user
     * 
     * @param string $username Username or email
     * @param string $password Password
     * @return object|false User object on success, false on failure
     */
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active'";
        $user = $this->db->fetch($sql, [$username, $username]);
        
        if ($user && password_verify($password, $user->password)) {
            // Update last login
            $this->updateLastLogin($user->id);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Create new user
     * 
     * @param array $data User data
     * @return int User ID
     */
    public function create($data) {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->db->insert('users', $data);
    }
    
    /**
     * Update user
     * 
     * @param int $id User ID
     * @param array $data User data
     * @return int Number of affected rows
     */
    public function update($id, $data) {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    /**
     * Delete user
     * 
     * @param int $id User ID
     * @return int Number of affected rows
     */
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
    
    /**
     * Update last login timestamp
     * 
     * @param int $id User ID
     * @return int Number of affected rows
     */
    public function updateLastLogin($id) {
        return $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }
    
    /**
     * Check if username exists
     * 
     * @param string $username Username
     * @param int $excludeId User ID to exclude (for updates)
     * @return bool
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result->count > 0;
    }
    
    /**
     * Check if email exists
     * 
     * @param string $email Email address
     * @param int $excludeId User ID to exclude (for updates)
     * @return bool
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result->count > 0;
    }
    
    /**
     * Get all users
     * 
     * @param int $limit Number of users
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getAll($limit = null, $offset = 0) {
        return $this->db->all('users', 'created_at DESC', $limit, $offset);
    }
    
    /**
     * Get users by role
     * 
     * @param string $role User role
     * @return array
     */
    public function getByRole($role) {
        return $this->db->query("SELECT * FROM users WHERE role = ? ORDER BY full_name", [$role]);
    }
    
    /**
     * Count users
     * 
     * @param string $status User status (optional)
     * @return int
     */
    public function count($status = null) {
        if ($status) {
            return $this->db->count('users', 'status = ?', [$status]);
        }
        return $this->db->count('users');
    }
    
    /**
     * Change user password
     * 
     * @param int $id User ID
     * @param string $newPassword New password
     * @return int Number of affected rows
     */
    public function changePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->db->update('users', ['password' => $hashedPassword], 'id = ?', [$id]);
    }
    
    /**
     * Update user status
     * 
     * @param int $id User ID
     * @param string $status New status
     * @return int Number of affected rows
     */
    public function updateStatus($id, $status) {
        return $this->db->update('users', ['status' => $status], 'id = ?', [$id]);
    }
}
