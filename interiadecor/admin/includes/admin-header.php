<?php
// Check if config is loaded
if (!defined('SITE_URL')) {
    require_once dirname(__DIR__, 2) . '/includes/config.php';
}

// Check authentication
if (!$auth->isLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

// Check admin/editor access
if (!$auth->isAdmin() && !$auth->isEditor()) {
    $auth->logout();
    header('Location: ' . ADMIN_URL . '/login.php');
    exit;
}

// Set default page title
$pageTitle = $pageTitle ?? 'Interia Decor Admin';

// Start output buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars(getSetting('site_favicon') ?? 'favicon.ico') ?>" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body">
    <!-- Admin Wrapper -->
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="<?= ADMIN_URL ?>">
                    <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars(getSetting('site_logo') ?? 'logo.png') ?>" alt="<?= SITE_NAME ?>">
                </a>
            </div>
            
            <!-- Sidebar content continues... -->
            <!-- [Rest of your existing sidebar HTML] -->
            
        </div>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Navigation -->
            <nav class="admin-topnav">
                <!-- [Your existing topnav HTML] -->
            </nav>
            
            <!-- Main Content Area -->
            <div class="admin-content">
                <!-- Page Header -->
                <div class="page-header mb-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
                            <!-- [Rest of your breadcrumb HTML] -->
                        </div>
                    </div>
                </div>
                
                <!-- Page Content -->
                <div class="page-content">