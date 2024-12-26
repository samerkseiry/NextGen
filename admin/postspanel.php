<?php
include '../core/init.php'; 
if (User::checkLogIn() === false) header('location: ../index.php');  

$user = User::getData($_SESSION['user_id']);
// Check if the user is not an admin
if ($user->is_admin != 1) {
  header('location: ../index.php');
  exit; 
}
// Handle GET request for deleting a post
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_post_id'])) {
    $deletePostId = $_GET['delete_post_id'];
    Tweet::deletePostById($deletePostId);
    header("Location: postspanel.php"); // Redirect to refresh the page
    exit;
}

// Fetch posts from database
// Assuming Tweet::getAllPosts() method exists and returns all posts. You might want to adjust or add filtering by user
$posts = Tweet::getAllPosts();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Posts Panel | NextGen</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logo.svg"> 
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/AdminLTE.min.css">
    <link rel="stylesheet" href="../css/_all-skins.min.css">
    <link rel="stylesheet" href="../css/generalcss.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px;">

        <section id="posts" class="content-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Sidebar with navigation -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Welcome <b>Admin</b></h3>
                            </div>
                            <div class="box-body no-padding">
                                <ul class="nav nav-pills nav-stacked">
                                    <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                                    <li><a href="userspanel.php"><i class="fa fa-briefcase"></i> Users Panel</a></li>
                                    <li class="active"><a href="posts_panel.php"><i class="fa fa-envelope"></i> Posts Panel</a></li>
                                    <li><a href="../home.php"><i class="fa fa-arrow-circle-o-right"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 bg-white padding-2">
                        <!-- Content for managing posts goes here -->
                        <h2>Posts Management</h2>
                        <!-- You can add a form here for filtering posts by user or adding new posts -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post->username); ?></td>
                                    <td><?php echo htmlspecialchars($post->status); ?></td>
                                    <td>
                                        <?php if ($post->img): ?>
                                            <img src="../assets/images/tweets/<?php echo htmlspecialchars($post->img); ?>" width="100" alt="Post Image">
                                        <?php else: ?>
                                            No image
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars(date("Y-m-d H:i:s", strtotime($post->post_on))); ?></td>
                                    <td>
                                        <!-- Assuming edit_post.php is your editing page -->
                                        <a href="edit_post.php?post_id=<?php echo $post->id; ?>">Edit</a> | 
                                        <a href="postspanel.php?delete_post_id=<?php echo $post->id; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->

    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Scripts as in your users panel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="../js/adminlte.min.js"></script>

</body>
</html>
