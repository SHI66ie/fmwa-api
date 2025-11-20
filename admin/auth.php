<?php
/**
 * Authentication Class for FMWA Admin Panel
 * Handles user login, logout, session management, and security
 */

class Auth {
    private $db;
    private $session_timeout = 3600; // 1 hour
    
    public function __construct($database) {
        $this->db = $database;
        $this->startSession();
    }
    
    /**
     * Start secure session
     */
    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > $this->session_timeout)) {
            $this->logout();
        }
        
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Authenticate user login
     */
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, username, password, role, status FROM users WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                $_SESSION['login_time'] = time();
                
                // Log successful login
                $this->logActivity($user['id'], 'login', 'User logged in successfully');
                
                // Update last login
                $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                return true;
            }
            
            // Log failed login attempt
            $this->logActivity(null, 'login_failed', "Failed login attempt for username: $username");
            return false;
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Get current user info
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];
    }
    
    /**
     * Check if user has required role
     */
    public function hasRole($required_role) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $user_role = $_SESSION['role'];
        
        // Role hierarchy: admin > editor > user
        $roles = ['user' => 1, 'editor' => 2, 'admin' => 3];
        
        return isset($roles[$user_role]) && 
               isset($roles[$required_role]) && 
               $roles[$user_role] >= $roles[$required_role];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        if ($this->isLoggedIn()) {
            $this->logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }
        
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Log user activity
     */
    private function logActivity($user_id, $action, $description) {
        try {
            $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $user_id,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
        } catch (Exception $e) {
            error_log("Activity log error: " . $e->getMessage());
        }
    }
    
    /**
     * Require login - redirect if not authenticated
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
    
    /**
     * Require specific role - show error if insufficient permissions
     */
    public function requireRole($role) {
        $this->requireLogin();
        
        if (!$this->hasRole($role)) {
            http_response_code(403);
            die('Access denied. Insufficient permissions.');
        }
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
