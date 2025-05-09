$(document).ready(function() {
    const themeToggle = $('.theme-toggle');
    const themeLight = $('#theme-light');
    const themeDark = $('#theme-dark');
    
    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply the current theme
    if (currentTheme === 'dark') {
        themeLight.prop('disabled', true);
        themeDark.prop('disabled', false);
        $('body').addClass('dark-theme');
    } else {
        themeLight.prop('disabled', false);
        themeDark.prop('disabled', true);
        $('body').removeClass('dark-theme');
    }
    
    // Toggle theme
    themeToggle.click(function() {
        if ($('body').hasClass('dark-theme')) {
            // Switch to light theme
            themeLight.prop('disabled', false);
            themeDark.prop('disabled', true);
            $('body').removeClass('dark-theme');
            localStorage.setItem('theme', 'light');
        } else {
            // Switch to dark theme
            themeLight.prop('disabled', true);
            themeDark.prop('disabled', false);
            $('body').addClass('dark-theme');
            localStorage.setItem('theme', 'dark');
        }
    });
});