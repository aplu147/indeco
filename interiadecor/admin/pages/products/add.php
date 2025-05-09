<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions
if (!$auth->isLoggedIn() || (!$auth->isAdmin() && !$auth->isEditor())) {
    redirect('admin/login.php');
}

// Set page title
$pageTitle = 'Add New Product';

// Set breadcrumbs
$breadcrumbs = [
    ['url' => 'products/list.php', 'title' => 'Products', 'active' => false],
    ['url' => '', 'title' => 'Add New', 'active' => true]
];

// Get categories for dropdown
$db->query('SELECT * FROM product_categories WHERE status = 1 ORDER BY name');
$categories = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="product-form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category->id ?>"><?= htmlspecialchars($category->name) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control summernote" id="description" name="description" rows="5" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Specifications</label>
                                <div id="specifications-container">
                                    <div class="specification-item mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="spec_keys[]" placeholder="Specification name">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="spec_values[]" placeholder="Specification value">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-spec w-100">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-spec" class="btn btn-sm btn-secondary mt-2">
                                    <i class="fas fa-plus me-1"></i> Add Specification
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Featured Image *</label>
                                <div class="image-upload-container">
                                    <div class="image-preview" id="featured-image-preview">
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <p>No image selected</p>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control d-none" id="featured_image" name="featured_image" accept="image/*" required>
                                    <button type="button" class="btn btn-primary btn-sm mt-2 w-100" id="upload-featured-btn">
                                        <i class="fas fa-upload me-1"></i> Upload Image
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gallery_images" class="form-label">Gallery Images</label>
                                <div class="gallery-upload-container">
                                    <div class="gallery-preview" id="gallery-preview"></div>
                                    <input type="file" class="form-control d-none" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2 w-100" id="upload-gallery-btn">
                                        <i class="fas fa-images me-1"></i> Add Gallery Images
                                    </button>
                                </div>
                            </div>
                            
                            <?php if ($auth->isAdmin()): ?>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-published" value="published" checked>
                                    <label class="form-check-label" for="status-published">
                                        Published
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-draft" value="draft">
                                    <label class="form-check-label" for="status-draft">
                                        Draft
                                    </label>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Product
                                </button>
                                <a href="list.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$customScripts = ['file-upload.js', 'summernote.js', 'products-form.js'];
require_once '../includes/admin-footer.php';
?>