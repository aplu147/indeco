<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions
if (!$auth->isLoggedIn()) {
    redirect('admin/login.php');
}

// Get stats for dashboard
$db->query('SELECT COUNT(*) as total FROM projects WHERE status = "published"');
$totalProjects = $db->single()->total;

$db->query('SELECT COUNT(*) as total FROM products WHERE status = "published"');
$totalProducts = $db->single()->total;

$db->query('SELECT COUNT(*) as total FROM testimonials WHERE status = "published"');
$totalTestimonials = $db->single()->total;

$db->query('SELECT COUNT(*) as total FROM content_revisions WHERE status = "pending"');
$pendingApprovals = $db->single()->total;

// Get latest pending content for approval (admin only)
$pendingContent = [];
if ($auth->isAdmin()) {
    $db->query('SELECT * FROM content_revisions WHERE status = "pending" ORDER BY created_at DESC LIMIT 5');
    $pendingContent = $db->resultSet();
}

// Get latest notifications
$notifications = getUserNotifications($_SESSION['user_id'], 5);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h5 class="card-title">Projects</h5>
                    <h2 class="stat-number"><?= $totalProjects ?></h2>
                    <a href="projects/list.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h5 class="card-title">Products</h5>
                    <h2 class="stat-number"><?= $totalProducts ?></h2>
                    <a href="products/list.php" class="btn btn-sm btn-outline-success">View All</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="card-title">Testimonials</h5>
                    <h2 class="stat-number"><?= $totalTestimonials ?></h2>
                    <a href="testimonials/list.php" class="btn btn-sm btn-outline-info">View All</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h5 class="card-title">Pending Approvals</h5>
                    <h2 class="stat-number"><?= $pendingApprovals ?></h2>
                    <a href="approvals/pending.php" class="btn btn-sm btn-outline-warning">Review</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <?php if ($auth->isAdmin() && !empty($pendingContent)): ?>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pending Approvals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Content Type</th>
                                    <th>Submitted By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingContent as $item): ?>
                                <tr>
                                    <td><?= ucfirst($item->content_type) ?></td>
                                    <td><?= $auth->getUser($item->created_by)->full_name ?></td>
                                    <td><?= date('M d, Y', strtotime($item->created_at)) ?></td>
                                    <td>
                                        <a href="approvals/pending.php?action=view&id=<?= $item->id ?>" class="btn btn-sm btn-primary">Review</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="<?= $auth->isAdmin() && !empty($pendingContent) ? 'col-lg-6' : 'col-lg-12' ?>">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Notifications</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($notifications)): ?>
                        <div class="list-group">
                            <?php foreach ($notifications as $notification): ?>
                            <a href="<?= $notification->link ?: '#' ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= $notification->title ?></h6>
                                    <small><?= timeAgo($notification->created_at) ?></small>
                                </div>
                                <p class="mb-1"><?= $notification->message ?></p>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No new notifications.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/admin-footer.php'; ?>