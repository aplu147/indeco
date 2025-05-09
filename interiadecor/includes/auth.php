<?php
class Auth {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    // Register a new user (admin only)
    public function register($data) {
        // Check if user exists
        $this->db->query('SELECT id FROM users WHERE username = :username OR email = :email');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        
        $row = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return false; // User already exists
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $this->db->query('INSERT INTO users (username, email, password, role, full_name) 
                         VALUES (:username, :email, :password, :role, :full_name)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':full_name', $data['full_name']);
        
        return $this->db->execute();
    }
    
    // Login user
    public function login($username, $password) {
        $this->db->query('SELECT * FROM users WHERE username = :username OR email = :username');
        $this->db->bind(':username', $username);
        
        $row = $this->db->single();
        
        if ($row && password_verify($password, $row->password)) {
            $_SESSION['user_id'] = $row->id;
            $_SESSION['user_role'] = $row->role;
            $_SESSION['user_username'] = $row->username;
            $_SESSION['user_fullname'] = $row->full_name;
            
            // Update last login
            $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
            $this->db->bind(':id', $row->id);
            $this->db->execute();
            
            return true;
        }
        
        return false;
    }
    
    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Check user role
    public function isAdmin() {
        return ($this->isLoggedIn() && $_SESSION['user_role'] === 'admin');
    }
    
    // Check user role
    public function isEditor() {
        return ($this->isLoggedIn() && $_SESSION['user_role'] === 'editor');
    }
    
    // Get user data
    public function getUser($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Logout user
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
    
    // Password reset request
    public function requestReset($email) {
        // Implementation for password reset
    }
    
    // Reset password
    public function resetPassword($token, $password) {
        // Implementation for password reset
    }
}

$auth = new Auth();