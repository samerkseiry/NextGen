<?php
include '../core/init.php'; 

// Handle GET request for deleting a user
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_user_id'])) {
    $deleteUserId = $_GET['delete_user_id'];
}

// Redirect to the edit users page
header("Location: ../admin/userspanel.php");
exit;
