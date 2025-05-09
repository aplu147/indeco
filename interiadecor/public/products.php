<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$pageTitle = "Our Products - Interia Decor";

// Get all published products
$db->query('SELECT p.*, pc.name as category_name 
            FROM products p 
            JOIN product_categories pc ON p.category_id = pc.id 
            WHERE p.status = "published" 
            ORDER BY p.created_at DESC');
$products = $db->resultSet();

// Get product categories for filtering
$db->query('SELECT * FROM product_categories WHERE status = 1 ORDER BY name');
$categories = $db->resultSet();
?>

<section class="page-header parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/products-bg.jpg">
    <div class="container">
        <h1>Our Products</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Premium Interior Products</h2>
            <p class="section-subtitle">Explore our collection of high-quality interior products</p>
        </div>
        
        <!-- Product Filters -->
        <div class="product-filters mb-5">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="search-filter">
                        <input type="text" id="product-search" class="form-control" placeholder="Search products...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select id="category-filter" class="form-select">
                        <option value="all">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category->id) ?>"><?= htmlspecialchars($category->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="row" id="products-container">
            <?php foreach ($products as $product): 
                $specs = json_decode($product->specifications, true) ?: [];
            ?>
            <div class="col-md-6 col-lg-4 mb-4 product-item" 
                 data-name="<?= htmlspecialchars(strtolower($product->name)) ?>"
                 data-category="<?= htmlspecialchars($product->category_id) ?>">
                <div class="product-card">
                    <div class="product-badge"><?= htmlspecialchars($product->category_name) ?></div>
                    <div class="product-image">
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($product->featured_image) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="img-fluid">
                        <div class="product-overlay">
                            <a href="#product-<?= $product->id ?>" class="btn btn-outline-light" data-bs-toggle="modal">View Details</a>
                        </div>
                    </div>
                    <div class="product-body">
                        <h3 class="product-title"><?= htmlspecialchars($product->name) ?></h3>
                        <div class="product-specs">
                            <?php if (!empty($specs)): 
                                $count = 0;
                                foreach ($specs as $key => $value): 
                                    if ($count < 2 && !empty($value)): ?>
                            <p><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></p>
                            <?php 
                                    $count++;
                                    endif;
                                endforeach; 
                            endif; ?>
                        </div>
                        <a href="#product-<?= $product->id ?>" class="btn btn-sm btn-primary" data-bs-toggle="modal">More Info</a>
                    </div>
                </div>
                
                <!-- Product Modal -->
                <div class="modal fade" id="product-<?= $product->id ?>" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel"><?= htmlspecialchars($product->name) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="product-gallery">
                                            <div class="main-image mb-3">
                                                <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($product->featured_image) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="img-fluid">
                                            </div>
                                            <?php 
                                            $gallery = json_decode($product->gallery_images, true) ?: [];
                                            if (!empty($gallery)): ?>
                                            <div class="gallery-thumbs">
                                                <?php foreach ($gallery as $image): ?>
                                                <div class="thumb-item">
                                                    <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="img-fluid">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="product-details">
                                            <h4>Product Details</h4>
                                            <div class="product-category mb-3">
                                                <span class="badge bg-primary"><?= htmlspecialchars($product->category_name) ?></span>
                                            </div>
                                            
                                            <div class="product-description mb-4">
                                                <h5>Description</h5>
                                                <p><?= htmlspecialchars($product->description) ?></p>
                                            </div>
                                            
                                            <div class="product-specifications">
                                                <h5>Specifications</h5>
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <?php if (!empty($specs)): 
                                                            foreach ($specs as $key => $value): 
                                                                if (!empty($value)): ?>
                                                        <tr>
                                                            <th><?= htmlspecialchars($key) ?></th>
                                                            <td><?= htmlspecialchars($value) ?></td>
                                                        </tr>
                                                        <?php 
                                                                endif;
                                                            endforeach; 
                                                        endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="<?= SITE_URL ?>/contact.php?product=<?= urlencode($product->name) ?>" class="btn btn-primary">Inquire About This Product</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <p>No products found. Please check back later.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // Product filtering
    $('#product-search, #category-filter').on('keyup change', function() {
        const searchTerm = $('#product-search').val().toLowerCase();
        const categoryFilter = $('#category-filter').val();
        
        $('.product-item').each(function() {
            const name = $(this).data('name');
            const category = $(this).data('category');
            
            const nameMatch = name.includes(searchTerm);
            const categoryMatch = categoryFilter === 'all' || category == categoryFilter;
            
            if (nameMatch && categoryMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Initialize gallery thumbs
    $('.gallery-thumbs').each(function() {
        const $thumbs = $(this);
        const $mainImage = $thumbs.siblings('.main-image').find('img');
        
        $thumbs.find('.thumb-item img').click(function() {
            const src = $(this).attr('src');
            $mainImage.attr('src', src);
            $thumbs.find('.thumb-item').removeClass('active');
            $(this).parent().addClass('active');
        });
        
        // Activate first thumb
        $thumbs.find('.thumb-item:first').addClass('active');
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>