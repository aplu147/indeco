<?php
// Admin-specific helper functions

function countPendingApprovals() {
    global $db;
    $db->query('SELECT COUNT(*) as count FROM content_revisions WHERE status = "pending"');
    $result = $db->single();
    return $result->count;
}

function getInitials($name) {
    $initials = '';
    $words = explode(' ', $name);
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return $diff . " seconds ago";
    } elseif ($diff < 3600) {
        return floor($diff/60) . " minutes ago";
    } elseif ($diff < 86400) {
        return floor($diff/3600) . " hours ago";
    } elseif ($diff < 604800) {
        return floor($diff/86400) . " days ago";
    } else {
        return date('M j, Y', $time);
    }
}