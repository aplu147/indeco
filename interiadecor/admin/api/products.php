<?php
require_once '../../../includes/config.php';

// Check if request is AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(403);
    die('Forbidden');
}

// Check authentication
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    die('Unauthorized');
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    $db->beginTransaction();
    
    // Handle different actions
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'add':
            if (!$auth->isAdmin() && !$auth->isEditor()) {
                throw new Exception('Permission denied');
            }
            
            $data = [
                'category_id' => $_POST['category_id'] ?? null,
                'name' => trim($_POST['name'] ?? ''),
                'slug' => generateSlug($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'specifications' => trim($_POST['specifications'] ?? ''),
                'status' => $auth->isAdmin() ? 'published' : 'pending',
                'created_by' => $_SESSION['user_id']
            ];
            
            // Validate required fields
            if (empty($data['name']) {
                throw new Exception('Product name is required');
            }
            
            if (empty($data['category_id'])) {
                throw new Exception('Category is required');
            }
            
            // Handle file upload
            if (!empty($_FILES['featured_image']['name'])) {
                $upload = uploadFile($_FILES['featured_image'], 'products');
                if (isset($upload['error'])) {
                    throw new Exception($upload['error']);
                }
                $data['featured_image'] = $upload['path'];
                
                // Generate thumbnail
                $thumbnailPath = str_replace('.', '_thumb.', $upload['path']);
                generateThumbnail(UPLOAD_DIR . $upload['path'], UPLOAD_DIR . $thumbnailPath, 300, 300);
            }
            
            // Handle gallery images
            $galleryImages = [];
            if (!empty($_FILES['gallery_images']['name'][0])) {
                foreach ($_FILES['gallery_images']['name'] as $key => $name) {
                    if (!empty($name)) {
                        $file = [
                            'name' => $name,
                            'type' => $_FILES['gallery_images']['type'][$key],
                            'tmp_name' => $_FILES['gallery_images']['tmp_name'][$key],
                            'error' => $_FILES['gallery_images']['error'][$key],
                            'size' => $_FILES['gallery_images']['size'][$key]
                        ];
                        
                        $upload = uploadFile($file, 'products/gallery');
                        if (isset($upload['error'])) {
                            continue; // Skip failed uploads
                        }
                        $galleryImages[] = $upload['path'];
                    }
                }
                $data['gallery_images'] = json_encode($galleryImages);
            }
            
            // Insert product
            $fields = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            
            $db->query("INSERT INTO products ($fields) VALUES ($values)");
            foreach ($data as $key => $value) {
                $db->bind(":$key", $value);
            }
            
            if (!$db->execute()) {
                throw new Exception('Failed to add product');
            }
            
            $productId = $db->lastInsertId();
            
            // If editor submitted, create revision record and notification
            if ($auth->isEditor()) {
                $revisionData = json_encode([
                    'id' => $productId,
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'specifications' => $data['specifications'],
                    'featured_image' => $data['featured_image'] ?? '',
                    'gallery_images' => $galleryImages
                ]);
                
                $db->query("INSERT INTO content_revisions (content_type, content_id, revision_data, status, created_by) 
                            VALUES ('product', :content_id, :revision_data, 'pending', :user_id)");
                $db->bind(':content_id', $productId);
                $db->bind(':revision_data', $revisionData);
                $db->bind(':user_id', $_SESSION['user_id']);
                $db->execute();
                
                // Notify admin
                addNotification(1, 'New Product Submission', 
                    "A new product '{$data['name']}' has been submitted for approval by {$_SESSION['user_fullname']}", 
                    "admin/approvals/pending.php");
            }
            
            $response['success'] = true;
            $response['message'] = $auth->isAdmin() ? 'Product added successfully' : 'Product submitted for approval';
            $response['redirect'] = $auth->isAdmin() ? 'products/list.php' : 'dashboard.php';
            break;
            
        case 'edit':
            $productId = $_POST['id'] ?? 0;
            
            // Get existing product
            $db->query('SELECT * FROM products WHERE id = :id');
            $db->bind(':id', $productId);
            $product = $db->single();
            
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            // Check permissions
            if (!$auth->isAdmin() && ($auth->isEditor() && $product->created_by != $_SESSION['user_id'])) {
                throw new Exception('Permission denied');
            }
            
            $data = [
                'category_id' => $_POST['category_id'] ?? $product->category_id,
                'name' => trim($_POST['name'] ?? $product->name),
                'slug' => generateSlug($_POST['name'] ?? $product->name),
                'description' => trim($_POST['description'] ?? $product->description),
                'specifications' => trim($_POST['specifications'] ?? $product->specifications),
                'status' => $auth->isAdmin() ? ($_POST['status'] ?? $product->status) : 'pending'
            ];
            
            // Validate required fields
            if (empty($data['name'])) {
                throw new Exception('Product name is required');
            }
            
            if (empty($data['category_id'])) {
                throw new Exception('Category is required');
            }
            
            // Handle file upload
            if (!empty($_FILES['featured_image']['name'])) {
                $upload = uploadFile($_FILES['featured_image'], 'products');
                if (isset($upload['error'])) {
                    throw new Exception($upload['error']);
                }
                $data['featured_image'] = $upload['path'];
                
                // Generate thumbnail
                $thumbnailPath = str_replace('.', '_thumb.', $upload['path']);
                generateThumbnail(UPLOAD_DIR . $upload['path'], UPLOAD_DIR . $thumbnailPath, 300, 300);
                
                // Delete old image if exists
                if (!empty($product->featured_image)) {
                    @unlink(UPLOAD_DIR . $product->featured_image);
                    @unlink(UPLOAD_DIR . str_replace('.', '_thumb.', $product->featured_image));
                }
            } else {
                $data['featured_image'] = $product->featured_image;
            }
            
            // Handle gallery images
            $galleryImages = json_decode($product->gallery_images, true) ?: [];
            
            // Handle deleted images
            $existingImages = $_POST['existing_images'] ?? [];
            $galleryImages = array_filter($galleryImages, function($image) use ($existingImages) {
                return in_array($image, $existingImages);
            });
            
            // Handle new uploads
            if (!empty($_FILES['gallery_images']['name'][0])) {
                foreach ($_FILES['gallery_images']['name'] as $key => $name) {
                    if (!empty($name)) {
                        $file = [
                            'name' => $name,
                            'type' => $_FILES['gallery_images']['type'][$key],
                            'tmp_name' => $_FILES['gallery_images']['tmp_name'][$key],
                            'error' => $_FILES['gallery_images']['error'][$key],
                            'size' => $_FILES['gallery_images']['size'][$key]
                        ];
                        
                        $upload = uploadFile($file, 'products/gallery');
                        if (isset($upload['error'])) {
                            continue; // Skip failed uploads
                        }
                        $galleryImages[] = $upload['path'];
                    }
                }
            }
            $data['gallery_images'] = json_encode($galleryImages);
            
            // If admin is editing, update directly
            if ($auth->isAdmin()) {
                $setClause = implode(', ', array_map(function($key) {
                    return "$key = :$key";
                }, array_keys($data)));
                
                $db->query("UPDATE products SET $setClause, approved_by = :approved_by WHERE id = :id");
                foreach ($data as $key => $value) {
                    $db->bind(":$key", $value);
                }
                $db->bind(':approved_by', $_SESSION['user_id']);
                $db->bind(':id', $productId);
                
                if (!$db->execute()) {
                    throw new Exception('Failed to update product');
                }
                
                $response['success'] = true;
                $response['message'] = 'Product updated successfully';
                $response['redirect'] = 'products/list.php';
            } 
            // If editor is editing, create revision
            else {
                $revisionData = json_encode([
                    'id' => $productId,
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'specifications' => $data['specifications'],
                    'featured_image' => $data['featured_image'],
                    'gallery_images' => $galleryImages,
                    'status' => 'pending'
                ]);
                
                $db->query("INSERT INTO content_revisions (content_type, content_id, revision_data, status, created_by) 
                            VALUES ('product', :content_id, :revision_data, 'pending', :user_id)");
                $db->bind(':content_id', $productId);
                $db->bind(':revision_data', $revisionData);
                $db->bind(':user_id', $_SESSION['user_id']);
                $db->execute();
                
                // Notify admin
                addNotification(1, 'Product Update Submission', 
                    "Product '{$data['name']}' has been updated and submitted for approval by {$_SESSION['user_fullname']}", 
                    "admin/approvals/pending.php");
                
                $response['success'] = true;
                $response['message'] = 'Product changes submitted for approval';
                $response['redirect'] = 'dashboard.php';
            }
            break;
            
        case 'delete':
            if (!$auth->isAdmin()) {
                throw new Exception('Permission denied');
            }
            
            $productId = $_POST['id'] ?? 0;
            
            // Get product to delete
            $db->query('SELECT * FROM products WHERE id = :id');
            $db->bind(':id', $productId);
            $product = $db->single();
            
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            // Delete associated images
            if (!empty($product->featured_image)) {
                @unlink(UPLOAD_DIR . $product->featured_image);
                @unlink(UPLOAD_DIR . str_replace('.', '_thumb.', $product->featured_image));
            }
            
            $galleryImages = json_decode($product->gallery_images, true) ?: [];
            foreach ($galleryImages as $image) {
                @unlink(UPLOAD_DIR . $image);
            }
            
            // Delete product
            $db->query('DELETE FROM products WHERE id = :id');
            $db->bind(':id', $productId);
            
            if (!$db->execute()) {
                throw new Exception('Failed to delete product');
            }
            
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully';
            break;
            
        case 'update-status':
            if (!$auth->isAdmin()) {
                throw new Exception('Permission denied');
            }
            
            $productId = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? 'published';
            
            $db->query('UPDATE products SET status = :status, approved_by = :approved_by WHERE id = :id');
            $db->bind(':status', $status);
            $db->bind(':approved_by', $_SESSION['user_id']);
            $db->bind(':id', $productId);
            
            if (!$db->execute()) {
                throw new Exception('Failed to update product status');
            }
            
            $response['success'] = true;
            $response['message'] = 'Product status updated successfully';
            break;
            
        case 'approve-revision':
            if (!$auth->isAdmin()) {
                throw new Exception('Permission denied');
            }
            
            $revisionId = $_POST['id'] ?? 0;
            
            // Get revision
            $db->query('SELECT * FROM content_revisions WHERE id = :id');
            $db->bind(':id', $revisionId);
            $revision = $db->single();
            
            if (!$revision) {
                throw new Exception('Revision not found');
            }
            
            $revisionData = json_decode($revision->revision_data, true);
            
            // Update the content
            switch ($revision->content_type) {
                case 'product':
                    $setClause = implode(', ', array_map(function($key) {
                        return "$key = :$key";
                    }, array_keys($revisionData)));
                    
                    $db->query("UPDATE products SET $setClause, approved_by = :approved_by, status = :status WHERE id = :id");
                    foreach ($revisionData as $key => $value) {
                        $db->bind(":$key", $value);
                    }
                    $db->bind(':approved_by', $_SESSION['user_id']);
                    $db->bind(':status', 'published');
                    $db->bind(':id', $revision->content_id);
                    
                    if (!$db->execute()) {
                        throw new Exception('Failed to approve product changes');
                    }
                    break;
                    
                default:
                    throw new Exception('Invalid content type');
            }
            
            // Update revision status
            $db->query('UPDATE content_revisions SET status = "approved", reviewed_by = :reviewed_by WHERE id = :id');
            $db->bind(':reviewed_by', $_SESSION['user_id']);
            $db->bind(':id', $revisionId);
            $db->execute();
            
            // Notify editor
            if ($revision->created_by != $_SESSION['user_id']) {
                addNotification($revision->created_by, 'Content Approved', 
                    "Your changes to the {$revision->content_type} have been approved by {$_SESSION['user_fullname']}", 
                    "admin/{$revision->content_type}s/list.php");
            }
            
            $response['success'] = true;
            $response['message'] = 'Changes approved successfully';
            break;
            
        case 'reject-revision':
            if (!$auth->isAdmin()) {
                throw new Exception('Permission denied');
            }
            
            $revisionId = $_POST['id'] ?? 0;
            $notes = trim($_POST['notes'] ?? '');
            
            // Update revision status
            $db->query('UPDATE content_revisions SET status = "rejected", reviewed_by = :reviewed_by, revision_notes = :notes WHERE id = :id');
            $db->bind(':reviewed_by', $_SESSION['user_id']);
            $db->bind(':notes', $notes);
            $db->bind(':id', $revisionId);
            $db->execute();
            
            // Get revision to notify creator
            $db->query('SELECT created_by, content_type FROM content_revisions WHERE id = :id');
            $db->bind(':id', $revisionId);
            $revision = $db->single();
            
            // Notify editor
            if ($revision && $revision->created_by != $_SESSION['user_id']) {
                addNotification($revision->created_by, 'Content Rejected', 
                    "Your changes to the {$revision->content_type} have been rejected by {$_SESSION['user_fullname']}. Notes: $notes", 
                    "admin/{$revision->content_type}s/list.php");
            }
            
            $response['success'] = true;
            $response['message'] = 'Changes rejected successfully';
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);