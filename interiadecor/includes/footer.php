        </main>
        
        <!-- Footer -->
        <footer class="footer bg-dark text-white py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h3>Interia Decor</h3>
                        <p>Professional interior design services in Sylhet, Bangladesh.</p>
                        <div class="social-links">
                            <a href="<?= getSetting('facebook_url') ?>" target="_blank" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h3>Quick Links</h3>
                        <ul class="list-unstyled">
                            <li><a href="<?= SITE_URL ?>" class="text-white">Home</a></li>
                            <li><a href="<?= SITE_URL ?>/about.php" class="text-white">About Us</a></li>
                            <li><a href="<?= SITE_URL ?>/services.php" class="text-white">Services</a></li>
                            <li><a href="<?= SITE_URL ?>/projects.php" class="text-white">Projects</a></li>
                            <li><a href="<?= SITE_URL ?>/products.php" class="text-white">Products</a></li>
                            <li><a href="<?= SITE_URL ?>/contact.php" class="text-white">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4">
                        <h3>Contact Us</h3>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-map-marker-alt me-2"></i> <?= getSetting('contact_address') ?></li>
                            <li><i class="fas fa-phone me-2"></i> <?= getSetting('contact_phone') ?></li>
                            <li><i class="fas fa-envelope me-2"></i> <?= getSetting('contact_email') ?></li>
                        </ul>
                    </div>
                </div>
                
                <hr class="my-4 bg-light">
                
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <?= date('Y') ?> Interia Decor. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed & Developed by Interia Decor Team</p>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Dark/Light Mode Toggle -->
        <div class="theme-toggle">
            <i class="fas fa-moon"></i>
        </div>
        
        <!-- Back to Top Button -->
        <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Parallax JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
        
        <!-- Owl Carousel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        
        <!-- Custom JS -->
        <script src="<?= SITE_URL ?>/assets/js/main.js"></script>
        
        <!-- Facebook SDK (for feed) -->
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" 
                src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0&appId=<?= getSetting('facebook_app_id') ?>&autoLogAppEvents=1" 
                nonce="YOUR_NONCE"></script>
    </body>
</html>