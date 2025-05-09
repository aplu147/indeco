<?php
require_once '../../../includes/config.php';

header('Content-Type: application/json');

// Check authentication
if (!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'add':
            // Service creation logic
            break;
            
        case 'edit':
            // Service editing logic
            break;
            
        case 'delete':
            if (!$auth->isAdmin()) {
                throw new Exception('Permission denied');
            }
            
            $id = $_POST['id'] ?? 0;
            // Delete service logic
            $response['success'] = true;
            $response['message'] = 'Service deleted successfully';
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);