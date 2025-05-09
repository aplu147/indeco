$(document).ready(function() {
    // Add specification field
    $('#add-spec').click(function() {
        const $specItem = $(`
            <div class="specification-item mb-2">
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="spec_keys[]" placeholder="Specification name">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="spec_values[]" placeholder="Specification value">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-spec w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
        $('#specifications-container').append($specItem);
    });
    
    // Remove specification field
    $(document).on('click', '.remove-spec', function() {
        $(this).closest('.specification-item').remove();
    });
    
    // Form submission
    $('#product-form').submit(function(e) {
        e.preventDefault();
        
        // Create specifications object
        const specs = {};
        $('input[name="spec_keys[]"]').each(function(index) {
            const key = $(this).val();
            const value = $('input[name="spec_values[]"]').eq(index).val();
            if (key && value) {
                specs[key] = value;
            }
        });
        
        // Create FormData object
        const formData = new FormData(this);
        
        // Remove existing spec fields
        formData.delete('spec_keys[]');
        formData.delete('spec_values[]');
        
        // Add specs as JSON
        formData.append('specifications', JSON.stringify(specs));
        
        // Submit via AJAX
        $.ajax({
            url: 'api/products.php?action=add',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#product-form').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    $('#product-form').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Product');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
                $('#product-form').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Product');
            }
        });
    });
});