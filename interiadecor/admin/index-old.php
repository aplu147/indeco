<?php
echo 'Root dir: ' . dirname(__DIR__) . '<br>';
echo 'Config path: ' . dirname(__DIR__) . '/includes/config.php' . '<br>';
die();


// Define absolute root path
define('ROOT_DIR', 'C:/xampp/htdocs/interiadecor');

// Verify config.php exists - add this temporary debug
$configPath = ROOT_DIR . '/includes/config.php';
if (!file_exists($configPath)) {
    die("Error: Config file not found at: " . $configPath);
}

// Load configuration
require_once ROOT_DIR . '/includes/config.php';

// Load admin components
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/admin-nav.php';

// Check authentication
if (!$auth->isLoggedIn()) {
    redirect('login.php');
}


// Set page title
$pageTitle = 'Dashboard';


require_once 'includes/admin-header.php';
?>

<div class="row">
    <div class="col-md-4">
        <div class="card stat-card bg-primary">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h5 class="card-title">Active Projects</h5>
                <h2 class="stat-number">24</h2>
                <a href="pages/projects/list.php" class="btn btn-sm btn-light">View All</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card bg-success">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h5 class="card-title">Products</h5>
                <h2 class="stat-number">56</h2>
                <a href="pages/products/list.php" class="btn btn-sm btn-light">View All</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card bg-warning">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h5 class="card-title">Pending Approvals</h5>
                <h2 class="stat-number">5</h2>
                <a href="pages/approvals/pending.php" class="btn btn-sm btn-light">Review</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-content">
                            <p>New project "Modern Living Room" added</p>
                            <small>10 minutes ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <p>Project "Office Design" completed</p>
                            <small>2 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Stats</h5>
            </div>
            <div class="card-body">
                <canvas id="projects-chart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<?php 
$customScripts = ['dashboard.js'];
require_once __DIR__ . '/includes/admin-footer.php';
?>