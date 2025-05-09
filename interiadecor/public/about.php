<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$pageTitle = "About Us - Interia Decor";
?>

<section class="page-header parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/about-bg.jpg">
    <div class="container">
        <h1>About Interia Decor</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Our Story</h2>
                <p>Founded in 2015, Interia Decor has been transforming spaces in Sylhet and across Bangladesh with innovative interior design solutions. Our journey began with a small team of passionate designers and craftsmen, and today we've grown into a full-service interior design firm serving both residential and commercial clients.</p>
                <p>We believe that great design should be accessible to everyone, and we work closely with our clients to create spaces that reflect their personality, lifestyle, and functional needs.</p>
            </div>
            <div class="col-lg-6">
                <div class="about-image-grid">
                    <div class="row g-2">
                        <div class="col-6">
                            <img src="<?= SITE_URL ?>/assets/images/about-1.jpg" alt="Our office" class="img-fluid rounded">
                        </div>
                        <div class="col-6">
                            <img src="<?= SITE_URL ?>/assets/images/about-2.jpg" alt="Our team" class="img-fluid rounded">
                        </div>
                        <div class="col-6">
                            <img src="<?= SITE_URL ?>/assets/images/about-3.jpg" alt="Design process" class="img-fluid rounded">
                        </div>
                        <div class="col-6">
                            <img src="<?= SITE_URL ?>/assets/images/about-4.jpg" alt="Completed project" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">We deliver exceptional value to our clients</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Professional Team</h3>
                    <p>Our designers and craftsmen are highly skilled and experienced in all aspects of interior design.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                    <h3>Quality Materials</h3>
                    <p>We use only the highest quality materials sourced from trusted suppliers.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>On-Time Delivery</h3>
                    <p>We respect your time and complete projects within the agreed timeframe.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>After-Sales Support</h3>
                    <p>Our relationship continues even after project completion with dedicated support.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="section-title">Our Mission</h2>
                <p>To create beautiful, functional spaces that enhance our clients' quality of life while delivering exceptional value through innovative design solutions, quality craftsmanship, and outstanding customer service.</p>
                <div class="mission-stats row mt-4">
                    <div class="col-6">
                        <div class="stat-item">
                            <h3 class="stat-number" data-count="150">0</h3>
                            <p>Completed Projects</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <h3 class="stat-number" data-count="98">0</h3>
                            <p>Satisfied Clients</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title">Our Vision</h2>
                <p>To be recognized as the leading interior design firm in Bangladesh, known for our creativity, attention to detail, and commitment to customer satisfaction. We aim to continually push the boundaries of design while maintaining our core values of integrity, quality, and professionalism.</p>
                <div class="vision-image mt-4">
                    <img src="<?= SITE_URL ?>/assets/images/vision.jpg" alt="Our vision" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>