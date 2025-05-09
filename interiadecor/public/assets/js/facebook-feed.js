$(document).ready(function() {
    // Initialize Facebook Feed
    function loadFacebookFeed() {
        const fbContainer = document.getElementById('fb-feed-container');
        
        if (!fbContainer) return;
        
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
        
        // Initialize Facebook SDK if not already loaded
        if (!window.fbAsyncInit) {
            window.fbAsyncInit = function() {
                FB.init({
                    appId: '<?= getSetting("facebook_app_id") ?>',
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

    // Load feed after a slight delay to prevent blocking
    setTimeout(loadFacebookFeed, 500);
});