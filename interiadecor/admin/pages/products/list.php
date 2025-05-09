<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions
if (!$auth->isLoggedIn() || (!$auth->isAdmin() && !$auth->isEditor())) {
    redirect('admin/login.php');
}

// Set page title
$pageTitle = 'Manage Products';

// Set breadcrumbs
$breadcrumbs = [
    ['url' => 'products/list.php', 'title' => 'Products', 'active' => true]
];

// Set page actions
$pageActions = [
    ['url' => 'products/add.php', 'title' => 'Add Product', 'icon' => 'plus', 'type' => 'primary']
];

// Get products based on user role
if ($auth->isAdmin()) {
    $db->query('SELECT p.*, pc.name as category_name 
               FROM products p 
               JOIN product_categories pc ON p.category_id = pc.id 
               ORDER BY p.created_at DESC');
} else {
    $db->query('SELECT p.*, pc.name as category_name 
               FROM products p 
               JOIN product_categories pc ON p.category_id = pc.id 
               WHERE p.created_by = :user_id 
               ORDER BY p.created_at DESC');
    $db->bind(':user_id', $_SESSION['user_id']);
}

$products = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="products-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product->id ?></td>
                                <td>
                                    <?php if (!empty($product->featured_image)): ?>
                                    <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($product->featured_image) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="table-thumbnail">
                                    <?php else: ?>
                                    <div class="table-thumbnail no-image">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product->name) ?></td>
                                <td><?= htmlspecialchars($product->category_name) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $product->status == 'published' ? 'success' : 
                                        ($product->status == 'pending' ? 'warning' : 'secondary') 
                                    ?>">
                                        <?= ucfirst($product->status) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($product->created_at)) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?= $product->id ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($auth->isAdmin()): ?>
                                        <button class="btn btn-sm btn-<?= $product->status == 'published' ? 'warning' : 'success' ?> status-toggle" 
                                                data-id="<?= $product->id ?>" 
                                                data-status="<?= $product->status == 'published' ? 'draft' : 'published' ?>" 
                                                title="<?= $product->status == 'published' ? 'Unpublish' : 'Publish' ?>">
                                            <i class="fas fa-<?= $product->status == 'published' ? 'eye-slash' : 'eye' ?>"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-product" data-id="<?= $product->id ?>" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$customScripts = ['products.js'];
require_once '../includes/admin-footer.php';
?>