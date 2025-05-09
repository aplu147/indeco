// Admin Dashboard JS
$(document).ready(function() {
    // Initialize DataTables
    $('.table').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: -1 } // Disable ordering for actions column
        ]
    });
    
    // Toggle sidebar
    $('.sidebar-toggle').click(function() {
        $('.admin-wrapper').toggleClass('sidebar-collapsed');
    });
    
    // Status toggle buttons
    $(document).on('click', '.status-toggle', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const $btn = $(this);
        
        $.ajax({
            url: 'api/products.php?action=update-status',
            method: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update button appearance
                    $btn.data('status', status === 'published' ? 'draft' : 'published');
                    $btn.removeClass('btn-success btn-warning')
                        .addClass(status === 'published' ? 'btn-warning' : 'btn-success');
                    $btn.find('i').removeClass('fa-eye fa-eye-slash')
                        .addClass(status === 'published' ? 'fa-eye-slash' : 'fa-eye');
                    $btn.attr('title', status === 'published' ? 'Unpublish' : 'Publish');
                    
                    // Update status badge
                    const $badge = $btn.closest('td').siblings('td').find('.badge');
                    $badge.removeClass('bg-success bg-warning bg-secondary')
                        .addClass(status === 'published' ? 'bg-success' : 
                                 status === 'pending' ? 'bg-warning' : 'bg-secondary')
                        .text(status === 'published' ? 'Published' : 
                              status === 'pending' ? 'Pending' : 'Draft');
                    
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });
    
    // Delete buttons
    $(document).on('click', '.delete-product', function() {
        const id = $(this).data('id');
        const $row = $(this).closest('tr');
        
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            $.ajax({
                url: 'api/products.php?action=delete',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                        });
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    });
    
    // Toastr notifications
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    
    // Show success message if present in URL
    if (window.location.search.includes('success=1')) {
        toastr.success('Operation completed successfully');
        // Clean URL
        history.replaceState(null, '', window.location.pathname);
    }
});