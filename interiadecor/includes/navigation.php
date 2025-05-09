<?php
/**
 * Navigation Template for Interia Decor
 * Includes responsive navbar with scroll effects
 */
?>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/logo.png" alt="Interia Decor" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>" href="services.php">Services</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'active' : '' ?>" href="projects.php">Projects</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<style>
/* Navigation Styles */
.navbar {
    background-color: rgba(0, 0, 0, 0.05);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    padding: 15px 0;
}

.navbar.scrolled {
    padding: 10px 0;
    background-color: var(--background-color);
}

.navbar-brand img {
    transition: all 0.3s ease;
}

.navbar.scrolled .navbar-brand img {
    height: 35px;
}

.nav-link {
    font-weight: 500;
    padding: 8px 15px !important;
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 15px;
    width: 0;
    height: 2px;
    background-color: var(--accent-color);
    transition: all 0.3s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: calc(100% - 30px);
}

.navbar-toggler {
    border: none;
    padding: 0.5rem;
    font-size: 1.5rem;
    color: var(--primary-color);
}

.navbar-toggler:focus {
    box-shadow: none;
    outline: none;
}

@media (max-width: 991.98px) {
    .navbar-collapse {
        background-color: var(--background-color);
        padding: 20px;
        margin-top: 10px;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .nav-link {
        padding: 10px 0 !important;
    }
    
    .nav-link::after {
        display: none;
    }
}
</style>

<script>
// Navigation Script
document.addEventListener('DOMContentLoaded', function() {
    // Navigation scroll effect
    const navbar = document.querySelector('.navbar');
    
    function handleNavScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    
    // Initialize on load
    handleNavScroll();
    
    // Add scroll event listener
    window.addEventListener('scroll', handleNavScroll);
    
    // Close mobile menu when clicking a link
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
    
    // Add active class based on current page
    const currentPage = window.location.pathname.split('/').pop();
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (currentPage === linkPage) {
            link.classList.add('active');
        }
    });
});
</script>