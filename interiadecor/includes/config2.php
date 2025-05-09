<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'database_schema');

// Site Configuration
define('SITE_NAME', 'Interia Decor');
define('SITE_URL', 'http://localhost/interiadecor');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', 'public/assets/uploads/');

// Timezone
date_default_timezone_set('Asia/Dhaka');

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session Configuration
ini_set('session.cookie_lifetime', 86400); // 1 day
ini_set('session.gc_maxlifetime', 86400); // 1 day
session_start();

// Default Settings
$defaultSettings = [
    'theme_primary' => '#0C4B62',
    'theme_secondary' => '#529A44',
    'theme_accent' => '#E77624',
    'theme_background' => '#DFEDEE',
    'theme_text' => '#333333',
    'theme_light' => '#ffffff',
    'theme_dark' => '#222222',
    'site_logo' => '',
    'site_favicon' => '',
    'contact_phone' => '+8801614044001',
    'contact_email' => 'theinteriadecorbd@gmail.com',
    'contact_address' => 'Sayed Burhan Uddin (R) Road, Nowagaon, Mendibug, Sylhet, Bangladesh',
    'facebook_url' => 'https://facebook.com/interiadecorbd',
    'facebook_app_id' => '',
    'facebook_app_secret' => '',
    'default_meta_title' => 'Interia Decor - Interior Design Solutions',
    'default_meta_description' => 'Professional interior design services in Sylhet, Bangladesh',
    'default_meta_keywords' => 'interior design, decor, Sylhet, Bangladesh'
];

echo "Config file loaded successfully!";
exit;
?>

// Include other required files
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/auth.php';
