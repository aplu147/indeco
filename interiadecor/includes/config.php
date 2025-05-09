<?php
/**
 * Interia Decor - Complete Configuration File
 */

// Prevent direct access
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__DIR__));
}

// ========================
// 1. Basic Configuration
// ========================
define('SITE_NAME', 'Interia Decor');
define('SITE_URL', 'http://localhost/interiadecor');
define('ADMIN_URL', SITE_URL . '/admin');
date_default_timezone_set('Asia/Dhaka');

// ========================
// 2. Error Reporting
// ========================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ========================
// 3. Database Configuration
// ========================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'database_schema');
define('DB_CHARSET', 'utf8mb4');

// Initialize database connection
try {
    $db = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ========================
// 4. File Path Configuration
// ========================
define('UPLOAD_DIR', ROOT_DIR . '/public/assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/public/assets/uploads/');

// ========================
// 5. Session Configuration
// ========================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// ========================
// 6. Core Functions
// ========================
require_once ROOT_DIR . '/includes/functions.php';
require_once ROOT_DIR . '/includes/auth.php';

// Initialize authentication
$auth = new Auth($db);

// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");