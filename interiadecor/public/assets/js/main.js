// Initialize Parallax Effects
$(document).ready(function() {
    // Initialize Parallax
    $('.parallax-window').parallax({
        imageSrc: $(this).data('image-src'),
        naturalWidth: 1920,
        naturalHeight: 1080
    });

    // Initialize Testimonial Slider
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

    // Load Facebook Feed
    loadFacebookFeed();

    // Dark/Light Mode Toggle
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }

    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
});

// Facebook Feed Loader
function loadFacebookFeed() {
    // Replace with your Facebook Page Plugin code
    const fbContainer = document.getElementById('fb-feed-container');
    if (fbContainer) {
        fbContainer.innerHTML = `
            <div class="fb-page" 
                 data-href="https://www.facebook.com/interiadecorbd" 
                 data-tabs="timeline" 
                 data-width="500" 
                 data-height="500" 
                 data-small-header="false" 
                 data-adapt-container-width="true" 
                 data-hide-cover="false" 
                 data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/interiadecorbd" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/interiadecorbd">Interia Decor</a>
                </blockquote>
            </div>
        `;
        
        // Load Facebook SDK if not already loaded
        if (!window.fbAsyncInit) {
            window.fbAsyncInit = function() {
                FB.init({
                    appId: '<?= getSetting('facebook_app_id') ?>',
                    xfbml: true,
                    version: 'v12.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        } else {
            // If SDK is already loaded, parse the new elements
            if (typeof FB !== 'undefined') {
                FB.XFBML.parse();
            }
        }
    }
}

// Theme Toggle Function
function toggleTheme() {
    const body = document.body;
    body.classList.toggle('dark-theme');
    
    // Save preference to localStorage
    if (body.classList.contains('dark-theme')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}