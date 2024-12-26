<?php
include '../core/init.php';

// Handle POST request for adding a user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username']; // You might want to sanitize this
    $email = $_POST['email']; // You might want to sanitize and validate this
    $password = $_POST['password']; // You might want to hash this
    // Add other fields as needed

    // Assuming you have a method to add a user which returns true on success
    $result = User::addUser($username, $email, $password);
    
    if ($result) {
        // Optionally, set a session flash message to indicate success
        header("Location: userspanel.php"); // Redirect to refresh and see the new user in the list
        exit;
    } else {
        // Handle error case, such as displaying an error message
    }
}

// Handle GET request for deleting a user
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_user_id'])) {
    $deleteUserId = $_GET['delete_user_id'];
    User::deleteUserById($deleteUserId);
    header("Location: userspanel.php"); // Redirect back to refresh the page and the user list
    exit;
}

// Fetch users from database
$users = User::getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .form-add-user {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 20px auto;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        @media (max-width: 768px) {
            .form-add-user, table {
                width: 95%;
                margin: 10px auto;
            }
            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
   <!-- Add User Form -->
<form method="post" action="userspanel.php">
    <!-- Input fields for the user details -->
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <!-- Add other fields as necessary -->
    <button type="submit" name="add_user">Add User</button>
</form>


    <!-- Users Table -->
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <!-- ... other columns ... -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user->username); ?></td>
                <td><?php echo htmlspecialchars($user->email); ?></td>
                <!-- ... other columns ... -->
                <td>
                    <a href="edituser.php?user_id=<?php echo $user->id; ?>">Edit</a> |
                    <a href="userspanel.php?delete_user_id=<?php echo $user->id; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Optional: JavaScript for interactive functionality -->
    <script>
        // JavaScript for form validation, handling delete confirmation, etc.
    </script>
</body>
</html>
