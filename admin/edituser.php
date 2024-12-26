<?php
include '../core/init.php'; 
if (User::checkLogIn() === false) header('location: ../index.php');  
$user = User::getData($_SESSION['user_id']);
// Check if the user is not an admin
if ($user->is_admin != 1) {
  header('location: ../index.php');
  exit; 
}
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $user = User::getData($userId); // Fetch user data based on the user ID
} else {
    // Redirect or handle the error if the user_id is not set
    header('Location: editusers.php'); // Redirect to a default page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | NextGen</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logo.svg"> 

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-wrapper {
            background-color: #fff;
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button[type="submit"] {
            background-color: #0056b3;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <h2>Edit User</h2>
    <form method="post" action="../handle/handleUpdateUser.php">
        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
        <input type="text" name="username" value="<?php echo $user->username; ?>" placeholder="Username" required>
        <input type="email" name="email" value="<?php echo $user->email; ?>" placeholder="Email" required>
        <button type="submit" name="update_user">Update User</button>
    </form>
</div>

</body>
</html>
