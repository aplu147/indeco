<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

// Get featured projects
$db->query('SELECT * FROM projects WHERE status = "published" ORDER BY created_at DESC LIMIT 3');
$featuredProjects = $db->resultSet();

// Get featured services
$db->query('SELECT * FROM services WHERE status = "published" ORDER BY created_at DESC LIMIT 4');
$featuredServices = $db->resultSet();

// Get testimonials
$db->query('SELECT * FROM testimonials WHERE status = "published" ORDER BY created_at DESC LIMIT 3');
$testimonials = $db->resultSet();
?>

<!-- Parallax Hero Section -->
<section class="hero-section parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/hero-bg.jpg">
    <div class="container">
        <div class="hero-content">
            <h1><?= getSetting('hero_title') ?: 'Transform Your Space With Elegance' ?></h1>
            <p class="lead"><?= getSetting('hero_subtitle') ?: 'Professional interior design services in Sylhet, Bangladesh' ?></p>
            <a href="<?= SITE_URL ?>/contact.php" class="btn btn-primary btn-lg">Get a Free Consultation</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">About Interia Decor</h2>
                <p><?= getSetting('about_text') ?: 'We are a premier interior design firm based in Sylhet, Bangladesh...' ?></p>
                <a href="<?= SITE_URL ?>/about.php" class="btn btn-outline-primary">Learn More</a>
            </div>
            <div class="col-lg-6">
                <div class="about-image parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/about-bg.jpg"></div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">We offer comprehensive interior design solutions</p>
        </div>
        <div class="row">
            <?php foreach ($featuredServices as $service): ?>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="<?= $service->icon ?: 'fas fa-paint-roller' ?>"></i>
                    </div>
                    <h3><?= htmlspecialchars($service->title) ?></h3>
                    <p><?= substr(htmlspecialchars($service->description), 0, 100) ?>...</p>
                    <a href="<?= SITE_URL ?>/services.php#service-<?= $service->id ?>" class="btn btn-sm btn-outline-primary">Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= SITE_URL ?>/services.php" class="btn btn-primary">View All Services</a>
        </div>
    </div>
</section>

<!-- Projects Section with Parallax -->
<section class="projects-showcase py-5 parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/projects-bg.jpg">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title text-white">Our Projects</h2>
            <p class="section-subtitle text-white">See our recent work and get inspired</p>
        </div>
        <div class="row">
            <?php foreach ($featuredProjects as $project): ?>
            <div class="col-md-4 mb-4">
                <div class="project-card">
                    <div class="project-image">
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($project->featured_image) ?>" alt="<?= htmlspecialchars($project->title) ?>" class="img-fluid">
                        <div class="project-overlay">
                            <h3><?= htmlspecialchars($project->title) ?></h3>
                            <a href="<?= SITE_URL ?>/projects.php#project-<?= $project->id ?>" class="btn btn-outline-light">View Project</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= SITE_URL ?>/projects.php" class="btn btn-light">View All Projects</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Client Testimonials</h2>
            <p class="section-subtitle">What our clients say about us</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="testimonial-slider owl-carousel">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-item">
                        <div class="testimonial-content">
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
                            <p><?= htmlspecialchars($testimonial->client_position) ?>, <?= htmlspecialchars($testimonial->company) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Facebook Feed Section -->
<section id="facebook-feed" class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Follow Us on Facebook</h2>
            <p class="section-subtitle">Stay updated with our latest news and projects</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="fb-feed-container" class="facebook-feed"></div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA Section -->
<section id="contact-cta" class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2>Ready to Transform Your Space?</h2>
                <p class="lead mb-0">Contact us today for a free consultation and quote.</p>
            </div>
            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                <a href="<?= SITE_URL ?>/contact.php" class="btn btn-light btn-lg">Get in Touch</a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>