<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Check permissions
if (!$auth->isLoggedIn() || (!$auth->isAdmin() && !$auth->isEditor())) {
    redirect('admin/login.php');
}

$pageTitle = 'Manage Projects';
$breadcrumbs = [
    ['url' => 'projects/list.php', 'title' => 'Projects', 'active' => true]
];

// Get projects based on role
if ($auth->isAdmin()) {
    $db->query('SELECT * FROM projects ORDER BY created_at DESC');
} else {
    $db->query('SELECT * FROM projects WHERE created_by = :user_id ORDER BY created_at DESC');
    $db->bind(':user_id', $_SESSION['user_id']);
}

$projects = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project->title) ?></td>
                                <td><?= htmlspecialchars($project->client_name) ?></td>
                                <td><?= htmlspecialchars($project->location) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $project->status == 'published' ? 'success' : 
                                        ($project->status == 'pending' ? 'warning' : 'secondary') 
                                    ?>">
                                        <?= ucfirst($project->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?= $project->id ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($auth->isAdmin()): ?>
                                        <button class="btn btn-sm btn-danger delete-project" data-id="<?= $project->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.delete-project').click(function() {
        if (confirm('Are you sure you want to delete this project?')) {
            const id = $(this).data('id');
            
            $.ajax({
                url: 'api/projects.php?action=delete',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
});
</script>

<?php require_once '../includes/admin-footer.php'; ?>