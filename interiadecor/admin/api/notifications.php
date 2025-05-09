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
        case 'mark-read':
            $id = $_POST['id'] ?? 0;
            
            if (empty($id)) {
                throw new Exception('Invalid notification ID');
            }
            
            markNotificationAsRead($id);
            $response['success'] = true;
            $response['message'] = 'Notification marked as read';
            break;
            
        case 'mark-all-read':
            $db->query('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id');
            $db->bind(':user_id', $_SESSION['user_id']);
            $db->execute();
            
            $response['success'] = true;
            $response['message'] = 'All notifications marked as read';
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);