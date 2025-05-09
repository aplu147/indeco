<?php
// Admin navigation sidebar
?>
<nav class="admin-sidebar-nav">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?= ADMIN_URL ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        
        <?php if ($auth->isAdmin() || $auth->isEditor()): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= ADMIN_URL ?>/pages/products/list.php">
                <i class="fas fa-box-open"></i> Products
            </a>
        </li>
        <?php endif; ?>
        
        <?php if ($auth->isAdmin()): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= ADMIN_URL ?>/pages/settings/general.php">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>