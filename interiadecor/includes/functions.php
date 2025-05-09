<?php
// Sanitize input data
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Redirect to another page
function redirect($page) {
    header('Location: ' . SITE_URL . '/' . $page);
    exit();
}

// Generate slug from string
function generateSlug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Upload file with validation
function uploadFile($file, $dir = 'uploads', $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) {
    $targetDir = UPLOAD_DIR . $dir . '/';
    
    // Create directory if not exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    $mimeType = mime_content_type($file['tmp_name']);
    
    // Check if file is allowed
    if (!in_array($mimeType, $allowedTypes)) {
        return ['error' => 'File type not allowed'];
    }
    
    // Check file size (max 5MB)
    if ($file['size'] > 5000000) {
        return ['error' => 'File too large (max 5MB)'];
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $dir . '/' . $fileName];
    } else {
        return ['error' => 'Error uploading file'];
    }
}

// Get setting value
function getSetting($key) {
    global $db;
    $db->query('SELECT setting_value FROM settings WHERE setting_key = :key');
    $db->bind(':key', $key);
    $result = $db->single();
    return $result ? $result->setting_value : null;
}

// Add notification
function addNotification($userId, $title, $message, $link = null) {
    global $db;
    $db->query('INSERT INTO notifications (user_id, title, message, link) 
               VALUES (:user_id, :title, :message, :link)');
    $db->bind(':user_id', $userId);
    $db->bind(':title', $title);
    $db->bind(':message', $message);
    $db->bind(':link', $link);
    return $db->execute();
}

// Get user notifications
function getUserNotifications($userId, $limit = 5) {
    global $db;
    $db->query('SELECT * FROM notifications 
               WHERE user_id = :user_id AND is_read = 0 
               ORDER BY created_at DESC 
               LIMIT :limit');
    $db->bind(':user_id', $userId);
    $db->bind(':limit', $limit);
    return $db->resultSet();
}

// Mark notification as read
function markNotificationAsRead($notificationId) {
    global $db;
    $db->query('UPDATE notifications SET is_read = 1 WHERE id = :id');
    $db->bind(':id', $notificationId);
    return $db->execute();
}

// Generate thumbnail
function generateThumbnail($sourcePath, $targetPath, $width = 300, $height = 300) {
    $info = getimagesize($sourcePath);
    $mime = $info['mime'];
    
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    $thumb = imagecreatetruecolor($width, $height);
    
    // Preserve transparency for PNG and GIF
    if ($mime == 'image/png' || $mime == 'image/gif') {
        imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }
    
    // Resize
    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
    
    // Save
    switch ($mime) {
        case 'image/jpeg':
            return imagejpeg($thumb, $targetPath, 90);
        case 'image/png':
            return imagepng($thumb, $targetPath, 9);
        case 'image/gif':
            return imagegif($thumb, $targetPath);
        default:
            return false;
    }
}