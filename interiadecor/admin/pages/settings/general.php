<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Only admin can access settings
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    redirect('admin/login.php');
}

$pageTitle = 'General Settings';
$breadcrumbs = [
    ['url' => 'settings/general.php', 'title' => 'Settings', 'active' => false],
    ['url' => '', 'title' => 'General', 'active' => true]
];

// Get current settings
$currentSettings = [
    'site_title' => getSetting('site_title'),
    'contact_email' => getSetting('contact_email'),
    'contact_phone' => getSetting('contact_phone'),
    'contact_address' => getSetting('contact_address'),
    'default_meta_title' => getSetting('default_meta_title'),
    'default_meta_description' => getSetting('default_meta_description'),
    'default_meta_keywords' => getSetting('default_meta_keywords')
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsToUpdate = [
        'site_title' => $_POST['site_title'] ?? '',
        'contact_email' => $_POST['contact_email'] ?? '',
        'contact_phone' => $_POST['contact_phone'] ?? '',
        'contact_address' => $_POST['contact_address'] ?? '',
        'default_meta_title' => $_POST['meta_title'] ?? '',
        'default_meta_description' => $_POST['meta_description'] ?? '',
        'default_meta_keywords' => $_POST['meta_keywords'] ?? ''
    ];
    
    // Update settings
    $settings->updateSettings($settingsToUpdate);
    $success = 'Settings updated successfully';
    
    // Refresh current settings
    foreach ($settingsToUpdate as $key => $value) {
        $currentSettings[$key] = $value;
    }
}

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <h4 class="card-title mb-4">Site Information</h4>
                    
                    <div class="mb-3">
                        <label for="site_title" class="form-label">Site Title *</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" 
                               value="<?= htmlspecialchars($currentSettings['site_title']) ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email *</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                       value="<?= htmlspecialchars($currentSettings['contact_email']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone *</label>
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                       value="<?= htmlspecialchars($currentSettings['contact_phone']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_address" class="form-label">Contact Address *</label>
                        <textarea class="form-control" id="contact_address" name="contact_address" rows="3" required><?= htmlspecialchars($currentSettings['contact_address']) ?></textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h4 class="card-title mb-4">SEO Settings</h4>
                    
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Default Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="<?= htmlspecialchars($currentSettings['default_meta_title']) ?>">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Default Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?= htmlspecialchars($currentSettings['default_meta_description']) ?></textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Default Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="<?= htmlspecialchars($currentSettings['default_meta_keywords']) ?>">
                        <small class="text-muted">Comma-separated list of keywords</small>
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
                <h4 class="card-title mb-4">Settings Guide</h4>
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle me-2"></i> Site Information</h5>
                    <p>These details will be displayed in the website footer and contact page.</p>
                </div>
                
                <div class="alert alert-warning">
                    <h5><i class="fas fa-search me-2"></i> SEO Settings</h5>
                    <p>Meta tags help search engines understand your content. Keep them concise and relevant.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>