<?php
include '../core/init.php';

if (User::checkLogIn() === false) header('location: ../index.php');  


// Get user data
$user = User::getData($_SESSION['user_id']);
// Check if the user is not an admin
if ($user->is_admin != 1) {
  header('location: ../index.php');
  exit; 
}
$username = User::getUserNameById($_SESSION['user_id']);

// Counts using methods from the Tweet class
$postCount = Tweet::countTweets($user->id);
$commentCount = Tweet::countComments($user->id);

// Counts using methods from the Follow class
$followerCount = Follow::countAllFollows();

// Count of all users and tweets in the database
$totalUsersCount = User::countAllUsers();
$totalTweetsCount = Tweet::countAllTweets();

// Get the total number of likes
$totalLikesCount = Tweet::countAllLikes();

// Get the total number of comments
$totalCommentsCount = Tweet::countAllComments();

// Get the total number of comments
$totalRepostsCount = Tweet::countAllReposts();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Job Portal | NextGen</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logo.svg"> 

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  
  <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../assets/css/_all-skins.min.css">
  <link rel="stylesheet" href="../assets/css/generalcss.css">
 
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
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left: 0px;">

    <section id="candidates" class="content-header">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Welcome <b>Admin</b></h3>
              </div>
              <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                  <li class="active"><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                  <li><a href="userspanel.php"><i class="fas fa-users"></i> Users Panel</a></li>
                  <li><a href="postspanel.php"><i class="fa fa-envelope"></i> Posts Panel</a></li>
                  <li><a href="../home.php"><i class="fa fa-arrow-circle-o-right"></i> Logout</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9 bg-white padding-2">

            <h3>NextGen Statistics</h3>
            <div class="row">
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-blue"><i class="fas fa-users"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Users</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($totalUsersCount); ?></span>
                  </div>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-green"><i class="fas fa-id-badge"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Posts</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($totalTweetsCount); ?></span>
                    
                  </div>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-red"><i class="fas fa-heart"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Likes</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($totalLikesCount); ?></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-yellow"><i class="fas fa-comment"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Comments</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($totalCommentsCount); ?></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-aqua"><i class="ion ion-ios-browsers"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Reposts</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($totalRepostsCount); ?></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box bg-c-yellow">
                  <span class="info-box-icon bg-purple"><i class="ion ion-person-add"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Follows</span>
                    <span class="info-box-number"><?php echo htmlspecialchars($followerCount); ?></span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

    

  </div>
  <!-- /.content-wrapper -->

  

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>

<!-- jQuery 3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../js/adminlte.min.js"></script>
</body>
</html>
