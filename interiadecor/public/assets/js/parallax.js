$(document).ready(function() {
    // Initialize parallax on all elements with the class
    $('.parallax-window').each(function() {
        const $this = $(this);
        const imgSrc = $this.data('image-src');
        
        $this.parallax({
            imageSrc: imgSrc,
            naturalWidth: $this.data('natural-width') || 1200,
            naturalHeight: $this.data('natural-height') || 800,
            speed: $this.data('speed') || 0.2,
            bleed: $this.data('bleed') || 0,
            iosFix: true,
            androidFix: true
        });
    });
    
    // Handle window resize
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            $(window).trigger('resize.px.parallax');
        }, 250);
    });
});