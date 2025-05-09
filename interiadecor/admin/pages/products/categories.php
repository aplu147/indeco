<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions - only admin can manage categories
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    redirect('admin/login.php');
}

$pageTitle = 'Product Categories';
$breadcrumbs = [
    ['url' => 'products/list.php', 'title' => 'Products', 'active' => false],
    ['url' => '', 'title' => 'Categories', 'active' => true]
];

// Get all categories
$db->query('SELECT * FROM product_categories ORDER BY name');
$categories = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Add New Category</h5>
            </div>
            <div class="card-body">
                <form id="category-form">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Existing Categories</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category->name) ?></td>
                                <td>
                                    <span class="badge bg-<?= $category->status ? 'success' : 'danger' ?>">
                                        <?= $category->status ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary edit-category" data-id="<?= $category->id ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-category" data-id="<?= $category->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-category-form">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit-status" name="status">
                            <label class="form-check-label" for="edit-status">
                                Active
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add new category
    $('#category-form').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'api/products.php?action=add-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Edit category
    $('.edit-category').click(function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: 'api/products.php?action=get-category',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#edit-id').val(response.data.id);
                    $('#edit-name').val(response.data.name);
                    $('#edit-description').val(response.data.description);
                    $('#edit-status').prop('checked', response.data.status == 1);
                    
                    $('#editCategoryModal').modal('show');
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Update category
    $('#edit-category-form').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'api/products.php?action=update-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Delete category
    $('.delete-category').click(function() {
        if (confirm('Are you sure you want to delete this category?')) {
            const id = $(this).data('id');
            
            $.ajax({
                url: 'api/products.php?action=delete-category',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
});
</script>

<?php require_once '../includes/admin-footer.php'; ?>