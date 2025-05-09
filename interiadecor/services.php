<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

$pageTitle = "Our Services - Interia Decor";

// Get all published services
$db->query('SELECT * FROM services WHERE status = "published" ORDER BY display_order ASC');
$services = $db->resultSet();

// Get service categories for filtering
$db->query('SELECT DISTINCT category FROM services WHERE status = "published" AND category IS NOT NULL ORDER BY category');
$categories = $db->resultSet();
?>

<!-- Parallax Header Section -->
<section class="page-header parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/services-bg.jpg">
    <div class="container">
        <h1>Our Services</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Services Filter Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="service-filters">
                    <button class="btn btn-outline-primary filter-button active" data-filter="all">All Services</button>
                    <?php foreach ($categories as $category): ?>
                    <button class="btn btn-outline-primary filter-button" data-filter="<?= strtolower(str_replace(' ', '-', $category->category)) ?>">
                        <?= htmlspecialchars($category->category) ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid Section with Parallax Elements -->
<section class="py-5">
    <div class="container">
        <div class="row" id="services-container">
            <?php foreach ($services as $service): 
                $serviceClass = $service->category ? strtolower(str_replace(' ', '-', $service->category)) : '';
            ?>
            <div class="col-md-6 col-lg-4 mb-4 service-item <?= $serviceClass ?>">
                <div class="service-card">
                    <div class="service-image-wrapper parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($service->featured_image) ?>">
                        <div class="service-overlay">
                            <h3><?= htmlspecialchars($service->title) ?></h3>
                            <?php if ($service->category): ?>
                            <span class="service-badge"><?= htmlspecialchars($service->category) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="service-body">
                        <div class="service-icon">
                            <i class="<?= $service->icon ?: 'fas fa-paint-roller' ?>"></i>
                        </div>
                        <p><?= substr(htmlspecialchars($service->short_description), 0, 100) ?>...</p>
                        <div class="service-actions">
                            <a href="#service-<?= $service->id ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal">Details</a>
                            <a href="<?= SITE_URL ?>/contact.php?service=<?= urlencode($service->title) ?>" class="btn btn-sm btn-primary">Get Quote</a>
                        </div>
                    </div>
                </div>
                
                <!-- Service Modal -->
                <div class="modal fade" id="service-<?= $service->id ?>" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="serviceModalLabel"><?= htmlspecialchars($service->title) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="service-modal-image">
                                            <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($service->featured_image) ?>" alt="<?= htmlspecialchars($service->title) ?>" class="img-fluid rounded">
                                        </div>
                                        <?php if ($service->category): ?>
                                        <div class="service-category mt-3">
                                            <span class="badge bg-primary"><?= htmlspecialchars($service->category) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="service-details">
                                            <h4>Service Details</h4>
                                            <div class="service-description">
                                                <?= htmlspecialchars($service->description) ?>
                                            </div>
                                            
                                            <?php if ($service->features): 
                                                $features = explode("\n", $service->features);
                                            ?>
                                            <div class="service-features mt-4">
                                                <h5>Key Features</h5>
                                                <ul class="feature-list">
                                                    <?php foreach ($features as $feature): 
                                                        if (trim($feature)): ?>
                                                    <li><i class="fas fa-check-circle text-primary me-2"></i> <?= htmlspecialchars(trim($feature)) ?></li>
                                                    <?php endif; endforeach; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="service-pricing mt-4">
                                                <h5>Pricing</h5>
                                                <p><?= $service->price_range ? htmlspecialchars($service->price_range) : 'Contact us for a custom quote' ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="<?= SITE_URL ?>/contact.php?service=<?= urlencode($service->title) ?>" class="btn btn-primary">Get This Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Process Section with Parallax Background -->
<section class="service-process py-5 parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/process-bg.jpg">
    <div class="container">
        <div class="section-header text-center text-white mb-5">
            <h2 class="section-title">Our Process</h2>
            <p class="section-subtitle">How we deliver exceptional interior design services</p>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Consultation</h4>
                        <p>We discuss your vision, requirements, and budget to understand your needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Concept Development</h4>
                        <p>Our designers create initial concepts and present them for your feedback.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Design Finalization</h4>
                        <p>We refine the design based on your input and create detailed plans.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="process-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Implementation</h4>
                        <p>Our skilled team brings the design to life with quality craftsmanship.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Client Experiences</h2>
            <p class="section-subtitle">What our clients say about our services</p>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="testimonial-slider owl-carousel">
                    <?php
                    // Get service-related testimonials
                    $db->query('SELECT * FROM testimonials WHERE status = "published" AND service_id IS NOT NULL ORDER BY created_at DESC LIMIT 5');
                    $testimonials = $db->resultSet();
                    
                    foreach ($testimonials as $testimonial):
                        // Get service title
                        $serviceTitle = '';
                        if ($testimonial->service_id) {
                            $db->query('SELECT title FROM services WHERE id = :id');
                            $db->bind(':id', $testimonial->service_id);
                            $service = $db->single();
                            $serviceTitle = $service ? $service->title : '';
                        }
                    ?>
                    <div class="testimonial-item">
                        <div class="testimonial-content">
                            <?php if ($serviceTitle): ?>
                            <div class="service-badge">
                                <small>Service: <?= htmlspecialchars($serviceTitle) ?></small>
                            </div>
                            <?php endif; ?>
                            <div class="testimonial-rating">
                                <?php for ($i = 0; $i < $testimonial->rating; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p>"<?= htmlspecialchars($testimonial->content) ?>"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($testimonial->avatar) ?>" alt="<?= htmlspecialchars($testimonial->client_name) ?>">
                            <h5><?= htmlspecialchars($testimonial->client_name) ?></h5>
                            <p><?= htmlspecialchars($testimonial->client_position) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h2>Ready to Transform Your Space?</h2>
                <p class="lead mb-0">Contact us today to discuss your interior design needs.</p>
            </div>
            <div class="col-lg-4 text-lg-right">
                <a href="<?= SITE_URL ?>/contact.php" class="btn btn-light btn-lg">Get a Free Consultation</a>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Initialize service filtering
    $(".filter-button").click(function() {
        const value = $(this).attr('data-filter');
        
        $(".filter-button").removeClass("active");
        $(this).addClass("active");
        
        if (value == "all") {
            $(".service-item").show();
        } else {
            $(".service-item").not('.' + value).hide();
            $(".service-item").filter('.' + value).show();
        }
    });
    
    // Initialize testimonial slider
    $('.testimonial-slider').owlCarousel({
        loop: true,
        margin: 20,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });
    
    // Initialize parallax on service image wrappers
    $('.service-image-wrapper').parallax({
        imageSrc: $(this).data('image-src'),
        naturalWidth: 800,
        naturalHeight: 600
    });
});
</script>

<style>
/* Service Card Styles */
.service-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.service-image-wrapper {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.service-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
}

.service-overlay h3 {
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.service-badge {
    display: inline-block;
    background: var(--accent-color);
    color: white;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.service-body {
    padding: 20px;
    text-align: center;
}

.service-icon {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.service-actions {
    margin-top: 15px;
}

/* Process Steps */
.service-process {
    background-color: rgba(12, 75, 98, 0.8);
    color: white;
    position: relative;
}

.service-process:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
}

.process-step {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border-radius: 8px;
    padding: 20px;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.process-step:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-5px);
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--accent-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 15px;
}

.step-content h4 {
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.step-content p {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .service-filters {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .filter-button {
        margin-bottom: 10px;
    }
    
    .service-image-wrapper {
        height: 150px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>