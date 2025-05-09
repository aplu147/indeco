<?php
require_once 'includes/config.php';

// Destroy session and redirect to login
$auth->logout();
redirect('login.php');