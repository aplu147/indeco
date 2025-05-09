<?php
require_once '../../../includes/config.php';
require_once '../includes/admin-header.php';
require_once '../includes/admin-nav.php';

// Only admin can access user management
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    redirect('admin/login.php');
}

$pageTitle = 'User Management';
$breadcrumbs = [
    ['url' => 'settings/users.php', 'title' => 'Settings', 'active' => false],
    ['url' => '', 'title' => 'Users', 'active' => true]
];

// Get all users
$db->query('SELECT * FROM users ORDER BY role, username');
$users = $db->resultSet();

require_once '../includes/admin-header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="card-title">Manage Users</h4>
                    <a href="user-add.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> Add User
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user->username) ?></td>
                                <td><?= htmlspecialchars($user->full_name) ?></td>
                                <td><?= ucfirst($user->role) ?></td>
                                <td><?= $user->last_login ? date('M j, Y H:i', strtotime($user->last_login)) : 'Never' ?></td>
                                <td>
                                    <span class="badge bg-<?= $user->status ? 'success' : 'danger' ?>">
                                        <?= $user->status ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="user-edit.php?id=<?= $user->id ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-<?= $user->status ? 'danger' : 'success' ?> toggle-user" 
                                                data-id="<?= $user->id ?>" 
                                                data-status="<?= $user->status ? 0 : 1 ?>">
                                            <i class="fas fa-<?= $user->status ? 'times' : 'check' ?>"></i>
                                        </button>
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
    $('.toggle-user').click(function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        
        $.ajax({
            url: 'api/users.php?action=toggle-status',
            method: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>

<?php require_once '../includes/admin-footer.php'; ?>