$(document).ready(function() {
    // Handle featured image upload
    $('#upload-featured-btn').click(function() {
        $('#featured_image').click();
    });
    
    $('#featured_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#featured-image-preview').html(
                    `<img src="${e.target.result}" class="img-fluid" alt="Preview">`
                );
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Handle gallery images upload
    $('#upload-gallery-btn').click(function() {
        $('#gallery_images').click();
    });
    
    $('#gallery_images').change(function() {
        const files = this.files;
        $('#gallery-preview').empty();
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#gallery-preview').append(
                        `<div class="gallery-thumb">
                            <img src="${e.target.result}" class="img-thumbnail" alt="Preview ${i+1}">
                            <button type="button" class="btn btn-danger btn-sm remove-thumb">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`
                    );
                }
                reader.readAsDataURL(files[i]);
            }
        }
    });
    
    // Remove gallery thumbnails
    $(document).on('click', '.remove-thumb', function() {
        $(this).parent().remove();
    });
    
    // Dropzone initialization
    if ($('#file-upload-dropzone').length) {
        Dropzone.autoDiscover = false;
        new Dropzone("#file-upload-dropzone", {
            url: "api/upload.php",
            paramName: "file",
            maxFilesize: 5, // MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "Drop files here or click to upload",
            dictRemoveFile: "Remove",
            init: function() {
                this.on("success", function(file, response) {
                    if (response.success) {
                        $(file.previewElement).addClass("dz-success");
                        $(file.previewElement).find('.dz-remove').attr('data-file', response.file);
                    } else {
                        this.removeFile(file);
                        alert(response.message);
                    }
                });
            }
        });
    }
});