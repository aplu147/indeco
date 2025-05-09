<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions - only admin can access settings
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    redirect('admin/login.php');
}

// Set page title
$pageTitle = 'Design Settings';

// Set breadcrumbs
$breadcrumbs = [
    ['url' => 'settings/general.php', 'title' => 'Settings', 'active' => false],
    ['url' => '', 'title' => 'Design', 'active' => true]
];

// Get current theme settings
$themeSettings = $settings->getThemeSettings();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'theme_primary' => $_POST['primary_color'] ?? $themeSettings['theme_primary'],
        'theme_secondary' => $_POST['secondary_color'] ?? $themeSettings['theme_secondary'],
        'theme_accent' => $_POST['accent_color'] ?? $themeSettings['theme_accent'],
        'theme_background' => $_POST['background_color'] ?? $themeSettings['theme_background'],
        'theme_text' => $_POST['text_color'] ?? $themeSettings['theme_text'],
        'theme_light' => $_POST['light_color'] ?? $themeSettings['theme_light'],
        'theme_dark' => $_POST['dark_color'] ?? $themeSettings['theme_dark']
    ];
    
    // Handle logo upload
    if (!empty($_FILES['site_logo']['name'])) {
        $upload = uploadFile($_FILES['site_logo'], 'settings');
        if (isset($upload['path'])) {
            $data['site_logo'] = $upload['path'];
            
            // Delete old logo if exists
            if (!empty($themeSettings['site_logo'])) {
                @unlink(UPLOAD_DIR . $themeSettings['site_logo']);
            }
        }
    }
    
    // Handle favicon upload
    if (!empty($_FILES['site_favicon']['name'])) {
        $upload = uploadFile($_FILES['site_favicon'], 'settings');
        if (isset($upload['path'])) {
            $data['site_favicon'] = $upload['path'];
            
            // Delete old favicon if exists
            if (!empty($themeSettings['site_favicon'])) {
                @unlink(UPLOAD_DIR . $themeSettings['site_favicon']);
            }
        }
    }
    
    // Update settings
    if ($settings->updateSettings($data)) {
        // Regenerate theme CSS
        $settings->saveThemeCSS();
        
        $success = 'Design settings updated successfully';
    } else {
        $error = 'Failed to update design settings';
    }
    
    // Refresh theme settings
    $themeSettings = $settings->getThemeSettings();
}

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <h4 class="card-title mb-4">Color Scheme</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="primary_color" name="primary_color" value="<?= $themeSettings['theme_primary'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_primary'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="secondary_color" name="secondary_color" value="<?= $themeSettings['theme_secondary'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_secondary'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="accent_color" class="form-label">Accent Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="accent_color" name="accent_color" value="<?= $themeSettings['theme_accent'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_accent'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="background_color" class="form-label">Background Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="background_color" name="background_color" value="<?= $themeSettings['theme_background'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_background'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="text_color" class="form-label">Text Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="text_color" name="text_color" value="<?= $themeSettings['theme_text'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_text'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dark_color" class="form-label">Dark Color</label>
                                <div class="input-group colorpicker">
                                    <input type="text" class="form-control" id="dark_color" name="dark_color" value="<?= $themeSettings['theme_dark'] ?>">
                                    <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_dark'] ?>"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="light_color" class="form-label">Light Color</label>
                        <div class="input-group colorpicker">
                            <input type="text" class="form-control" id="light_color" name="light_color" value="<?= $themeSettings['theme_light'] ?>">
                            <span class="input-group-text"><i class="fas fa-square" style="color: <?= $themeSettings['theme_light'] ?>"></i></span>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h4 class="card-title mb-4">Branding</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_logo" class="form-label">Site Logo</label>
                                <div class="image-upload-container">
                                    <div class="image-preview">
                                        <?php if (!empty($themeSettings['site_logo'])): ?>
                                        <img src="<?= SITE_URL ?>/assets/uploads/<?= $themeSettings['site_logo'] ?>" alt="Current Logo" class="img-fluid mb-2" style="max-height: 100px;">
                                        <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <p>No logo uploaded</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_favicon" class="form-label">Site Favicon</label>
                                <div class="image-upload-container">
                                    <div class="image-preview">
                                        <?php if (!empty($themeSettings['site_favicon'])): ?>
                                        <img src="<?= SITE_URL ?>/assets/uploads/<?= $themeSettings['site_favicon'] ?>" alt="Current Favicon" class="img-fluid mb-2" style="max-height: 100px;">
                                        <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                            <p>No favicon uploaded</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Theme Preview</h4>
                
                <div class="theme-preview">
                    <div class="preview-header" style="background-color: <?= $themeSettings['theme_primary'] ?>; color: <?= $themeSettings['theme_light'] ?>;">
                        Header
                    </div>
                    <div class="preview-sidebar" style="background-color: <?= $themeSettings['theme_secondary'] ?>; color: <?= $themeSettings['theme_light'] ?>;">
                        Sidebar
                    </div>
                    <div class="preview-content" style="background-color: <?= $themeSettings['theme_background'] ?>; color: <?= $themeSettings['theme_text'] ?>;">
                        <p>Content Area</p>
                        <a href="#" style="color: <?= $themeSettings['theme_accent'] ?>;">Accent Link</a>
                    </div>
                    <div class="preview-footer" style="background-color: <?= $themeSettings['theme_dark'] ?>; color: <?= $themeSettings['theme_light'] ?>;">
                        Footer
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Current Theme CSS</h5>
                    <pre class="bg-light p-3"><code>:root {
    --primary-color: <?= $themeSettings['theme_primary'] ?>;
    --secondary-color: <?= $themeSettings['theme_secondary'] ?>;
    --accent-color: <?= $themeSettings['theme_accent'] ?>;
    --background-color: <?= $themeSettings['theme_background'] ?>;
    --text-color: <?= $themeSettings['theme_text'] ?>;
    --light-color: <?= $themeSettings['theme_light'] ?>;
    --dark-color: <?= $themeSettings['theme_dark'] ?>;
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$customScripts = ['colorpicker.js'];
require_once '../includes/admin-footer.php';
?>