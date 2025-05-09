<?php
// Load configuration first
require_once dirname(__DIR__) . '/includes/config.php';

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    redirect('admin/index.php');
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    
    if ($auth->login($username, $password)) {
        // Check if user is admin or editor
        if ($auth->isAdmin() || $auth->isEditor()) {
            redirect('index.php');
        } else {
            $auth->logout();
            $error = 'You do not have permission to access the admin panel.';
        }
    } else {
        $error = 'Invalid username or password.';
    }
}

require_once 'includes/admin-header.php';
?>

<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <img src="<?= ADMIN_URL ?>/assets/images/logo.png" alt="Interia Decor Admin">
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            
            <div class="text-center">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>