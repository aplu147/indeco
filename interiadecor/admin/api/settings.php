<?php
require_once '../../../includes/config.php';

header('Content-Type: application/json');

// Check authentication
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'update':
            $settingsToUpdate = $_POST['settings'] ?? [];
            
            if (empty($settingsToUpdate)) {
                throw new Exception('No settings provided');
            }
            
            foreach ($settingsToUpdate as $key => $value) {
                $settings->updateSetting($key, $value);
            }
            
            $response['success'] = true;
            $response['message'] = 'Settings updated successfully';
            break;
            
        case 'upload-logo':
            if (empty($_FILES['logo']['name'])) {
                throw new Exception('No file uploaded');
            }
            
            $upload = uploadFile($_FILES['logo'], 'settings');
            if (isset($upload['error'])) {
                throw new Exception($upload['error']);
            }
            
            // Update setting
            $settings->updateSetting('site_logo', $upload['path']);
            
            $response['success'] = true;
            $response['message'] = 'Logo uploaded successfully';
            $response['path'] = $upload['path'];
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);