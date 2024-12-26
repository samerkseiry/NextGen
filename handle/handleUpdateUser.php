<?php
include '../core/init.php'; 
// Check if the request is POST and the update_user button was clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    // Extract user details from POST data
    $userId = $_POST['user_id']; // Ensure your form includes a hidden input for the user ID
    $username = $_POST['username'];
    $email = $_POST['email'];

    $updateSuccess = User::updateUser($userId, $username, $email);

    if ($updateSuccess) {
        $_SESSION['flash_success'] = 'User updated successfully!';
    } else {
        $_SESSION['flash_error'] = 'Failed to update user.';
    }

    // Redirect to avoid form resubmission. Adjust the path as necessary.
    header("Location: ../admin/userspanel.php");
    exit;
} else {
    // If the script is accessed without a POST request, redirect to the user list or home page.
    header("Location: ../admin/userspanel.php"); 
    exit;
}
