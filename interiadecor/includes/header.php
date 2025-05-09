<?php
require_once 'config.php';

// Get current page title
$pageTitle = $pageTitle ?? 'Interia Decor - Interior Design Solutions';

// Get theme settings
$themeSettings = $settings->getThemeSettings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= getSetting('default_meta_description') ?>">
    <meta name="keywords" content="<?= getSetting('default_meta_keywords') ?>">
    <meta name="author" content="Interia Decor">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= SITE_URL ?>/assets/uploads/<?= getSetting('site_favicon') ?>" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/theme-light.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/parallax.css">
    
    <!-- Dark theme if enabled -->
    <?php if (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark'): ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/theme-dark.css">
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'dark-theme' : '' ?>">
    <!-- Navigation -->
    <?php include 'includes/navigation.php'; ?>
    
    <!-- Main Content -->
    <main>