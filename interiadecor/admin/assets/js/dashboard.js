$(document).ready(function() {
    // Dashboard specific functionality
    
    // Initialize chart.js charts
    if ($('#projects-chart').length) {
        const ctx = document.getElementById('projects-chart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Projects Completed',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(82, 154, 68, 0.7)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Recent activity widget
    $('.activity-item').click(function() {
        const target = $(this).data('target');
        if (target) {
            window.location.href = target;
        }
    });
    
    // Quick stats update
    function updateQuickStats() {
        $.ajax({
            url: 'api/dashboard.php?action=stats',
            success: function(data) {
                $('#stat-projects').text(data.projects);
                $('#stat-products').text(data.products);
                $('#stat-approvals').text(data.approvals);
            }
        });
    }
    
    // Update every 30 seconds
    updateQuickStats();
    setInterval(updateQuickStats, 30000);
});