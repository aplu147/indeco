<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions - only admin can access approvals
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    redirect('admin/login.php');
}

// Set page title
$pageTitle = 'Pending Approvals';

// Set breadcrumbs
$breadcrumbs = [
    ['url' => 'approvals/pending.php', 'title' => 'Approvals', 'active' => false],
    ['url' => '', 'title' => 'Pending', 'active' => true]
];

// Get pending approvals
$db->query('SELECT cr.*, u.full_name as creator_name 
           FROM content_revisions cr 
           JOIN users u ON cr.created_by = u.id 
           WHERE cr.status = "pending" 
           ORDER BY cr.created_at DESC');
$approvals = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($approvals)): ?>
                <div class="table-responsive">
                    <table id="approvals-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Content Type</th>
                                <th>Submitted By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($approvals as $approval): ?>
                            <tr>
                                <td><?= $approval->id ?></td>
                                <td><?= ucfirst($approval->content_type) ?></td>
                                <td><?= htmlspecialchars($approval->creator_name) ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($approval->created_at)) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="view.php?id=<?= $approval->id ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <p class="mb-0">No pending approvals found.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
require_once '../includes/admin-footer.php';
?>