<?php

   include 'core/init.php';
  
   $user_id = $_SESSION['user_id'];

   $user = User::getData($user_id);
   
   if (User::checkLogIn() === false) 
   header('location: index.php');
   $tweets = Tweet::tweets($user_id);
   $who_users = Follow::whoToFollow($user_id);
   $notify_count = User::CountNotification($user_id);
 //  $community_id = isset($_GET['community_id']) ? (int)$_GET['community_id'] : 0; 

?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communities Feed | NextGen</title>
    
    <link rel="shortcut icon" type="image/png" href="assets/images/logo.svg"> 
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/all.min.css">
        <link rel="stylesheet" href="assets/css/home_style.css?v=<?php echo time(); ?>">
    
   <style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #FFFFFF;
}

.ctweets-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start; 
    padding-right: 60px; 
    width: 100%;
}

.ctweet-card {
    margin-left: auto; 
    background-color: #ffffff;
    border: 1px solid #e1e4e8;
    border-radius: 10px;
    margin-bottom: 20px;
    padding: 20px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.cuser-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.cuser-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.cusername {
    font-weight: bold;
    font-size: 16px;
}

.ctweet-text {
    margin: 0 0 15px 0;
    line-height: 1.5;
}

.ctweet-img {
    width: 100%;
    border-radius: 8px;
    max-height: 300px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .tweet-card {
        width: 90%;
    }
}

    </style>
</head>
<body>
 

    <div id="mine">
 
    <div class="wrapper-left">
        <div class="sidebar-left">
          <div class="grid-sidebar" style="margin-top: 12px">
            <div class="icon-sidebar-align" style="margin-left: 90px;">
              <img src="assets/images/logo.png" alt="" height="75px" width="200px" />
            </div>
          </div>

          <a href="home.php">
          <div class="grid-sidebar bg-active" style="margin-top: 12px">
            <div class="icon-sidebar-align">
              <img src="assets/images/homeicon.png"homeicon alt="" height="26.25px" width="26.25px" />
            </div>
            <div class="wrapper-left-elements">
              <a class="wrapper-left-active" href="home.php" style="margin-top: 4px;"><strong>Home</strong></a>
            </div>
          </div>
          </a>

  
           <a href="notification.php">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align position-relative">
                <?php if ($notify_count > 0) { ?>
              <i class="notify-count"><?php echo $notify_count; ?></i> 
              <?php } ?>
              <img
                src="assets/images/noticon.png"
                alt=""
                height="26.25px"
                width="26.25px"
              />
            </div>
  
            <div class="wrapper-left-elements">
              <a href="notification.php" style="margin-top: 4px"><strong>Notification</strong></a>
            </div>
          </div>
          </a>
        
            <a href="<?php echo BASE_URL . $user->username; ?>">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align">
              <img src="assets/images/usericon.png" alt="" height="26.25px" width="26.25px" />
            </div>
  
            <div class="wrapper-left-elements">
              <!-- <a href="/NextGen/<?php echo $user->username; ?>"  style="margin-top: 4px"><strong>Profile</strong></a> -->
              <a  href="<?php echo BASE_URL . $user->username; ?>"  style="margin-top: 4px"><strong>Profile</strong></a>
            
            </div>
          </div>
          </a>
          <a href="communities.php">
          <div class="grid-sidebar" style="margin-top: 12px">
            <div class="icon-sidebar-align">
              <img src="assets/images/communityicon.png"homeicon alt="" height="26.25px" width="26.25px" />
            </div>
            <div class="wrapper-left-elements">
              <a class="wrapper-left-active" href="communities.php" style="margin-top: 4px;"><strong>Communities</strong></a>
            </div>
          </div>
          </a>
          <?php

//only admin!
if ($user->is_admin == 1) {
    echo '<a href="admin/dashboard.php">
            <div class="grid-sidebar">
                <div class="icon-sidebar-align">
                    <img src="assets/images/adminicon.png" alt="" height="26.25px" width="26.25px" />
                </div>
                <div class="wrapper-left-elements">
                    <a href="admin/dashboard.php" style="margin-top: 4px;"><strong>Dashboard</strong></a>
                </div>
            </div>
          </a>';
}
?>



          <a href="<?php echo BASE_URL . "account.php"; ?>">
          <div class="grid-sidebar ">
            <div class="icon-sidebar-align">
              <img src="assets/images/settingsicon.png" alt="" height="26.25px" width="26.25px" />
            </div>
  
            <div class="wrapper-left-elements">
              <a href="<?php echo BASE_URL . "account.php"; ?>" style="margin-top: 4px"><strong>Settings</strong></a>
            </div>
           
            
          </div>
          </a>
          <a href="includes/logout.php">
          <div class="grid-sidebar">
            <div class="icon-sidebar-align">
            <i style="font-size: 26px; color:red" class="fas fa-sign-out-alt"></i>
            </div>
  
            <div class="wrapper-left-elements">
              <a style="color:red" href="includes/logout.php" style="margin-top: 4px"><strong>Logout</strong></a>
            </div>
          </div>
          </a>
          
  
          <div class="box-user">
            <div class="grid-user">
              <div>
                <img
                  src="assets/images/users/<?php echo $user->img ?>"
                  alt="user"
                  class="img-user"
                />
              </div>
              <div>
                <p class="name"><strong><?php if($user->name !== null) {
                echo $user->name; } ?></strong></p>
                <p class="username">@<?php echo $user->username; ?></p>
              </div>
              <div class="mt-arrow">
                <img
                  src="https://i.ibb.co/mRLLwdW/arrow-down.png"
                  alt=""
                  height="18.75px"
                  width="18.75px"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
          

      <div class="grid-posts">
        <div class="border-right">
          <div class="grid-toolbar-center">
            <div class="center-input-search">
              <div class="input-group-login" id="whathappen">
                
                <div class="container">
                  <div class="part-1">
                    <div class="header">
                      <div class="home">
                        <h2>Community</h2>
                      </div>
                      <!-- <div class="icon">
                        <button type="button" name="button">+</button>
                      </div> -->
                    </div>
            
                    <div class="text">
                      <form class="" action="handle/handleCommunityTweet.php" method="post" enctype="multipart/form-data">
                        <div class="inner">
            
                            <img src="assets/images/users/<?php echo $user->img ?>" alt="profile photo">
                        
                          <label>
            
                            <textarea class="text-whathappen" name="status" rows="8" cols="80" placeholder="Post in This community"></textarea>
                        
                        </label>
                        </div> 
                            
                         <!-- tmp image upload place -->
                        <div class="position-relative upload-photo"> 
                          <img class="img-upload-tmp" src="assets/images/tweets/tweet-60666d6b426a1.jpg" alt="">
                          <div class="icon-bg">
                          <i id="#upload-delete-tmp" class="fas fa-times position-absolute upload-delete"></i>  

                          </div>
                        </div>


                        <div class="bottom"> 
                          
                          <div class="bottom-container">
                              
                            
                              
                           
                          <label for="tweet_img" class="ml-3 mb-2 uni">
    <img 
        src="assets/images/imgupload.png" 
        alt="Upload Image" 
        style="width: 30px; height: 30px; border-radius: 5px;" 
        onmouseover="this.style.borderRadius = '0px';this.style.cursor = 'pointer';" 
        onmouseout="this.style.borderRadius = '5px';"
    >
</label>


                            <input class="tweet_img" id="tweet_img" type="file" name="tweet_img">    
                                
                          </div>
                          <div class="hash-box">
                        
                              <ul style="margin-bottom: 0;">


                              </ul>
                          
                          </div>
                          <?php
                          $community_id = isset($_GET['community_id']) ? (int)$_GET['community_id'] : 0;
                          ?>
                          <!-- hidden -->
                        <input type="hidden" name="community_id" value="<?php echo htmlspecialchars($community_id); ?>">
                          <?php if (isset($_SESSION['errors_tweet'])) { 
                            
                            foreach($_SESSION['errors_tweet'] as $t) {?>
                            
                          <div class="alert alert-danger">
                          <span class="item2-pair"> <?php echo $t; ?> </span>
                          </div>
                         
                         <?php } } unset($_SESSION['errors_tweet']); ?>
                          <div>
                            
                        

                            <span class="bioCount" id="count">210</span>
                            <input id="tweet-input" type="submit" name="tweet" value="Post" class="submit" style="background-color: #581D61;">

                          </div>
                      </div>
                      </form>
                    </div>
                  </div>
                  <div class="part-2">
            
                  </div>
            
                </div>
                
                
              </div>
            </div>
          </div>
          <div class="box-fixed" id="box-fixed"></div>
            
          <?php  
          $community_id = isset($_GET['community_id']) ? (int)$_GET['community_id'] : 0;

          if ($community_id > 0) {
              $sql = "SELECT t.status, t.img, u.username, u.img AS user_img
                      FROM tweets t
                      JOIN posts p ON t.post_id = p.id
                      JOIN users u ON p.user_id = u.id
                      WHERE t.community_id = :community_id
                      ORDER BY p.post_on DESC";
                     
              try {
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':community_id', $community_id, PDO::PARAM_INT);
                  $stmt->execute();
                  
                  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                  if ($results) {
                      echo "<div class='ctweets-container'>";
                      foreach ($results as $row) {
                          echo "<div class='ctweet-card'>";
                          echo "<div class='cuser-info'>";
                          echo "<img class='cuser-img' src='assets/images/users/" . htmlspecialchars($row['user_img']) . "' alt='" . htmlspecialchars($row['username']) . "'s profile picture'>";
                          echo "<span class='cusername'>" . htmlspecialchars($row['username']) . "</span>";
                          echo "</div>";
                          echo "<p class='ctweet-text'>" . htmlspecialchars($row['status']) . "</p>";
                          if (!empty($row['img'])) {
                              echo "<img class='ctweet-img' src='assets/images/tweets/" . htmlspecialchars($row['img']) . "' alt='Tweet image'>";
                          }
                          echo "</div>";
                      }
                      echo "</div>";
                  } else {
                      echo "No tweets found for this community.";
                  }
              } catch (PDOException $e) {
                  echo "Error: " . $e->getMessage();
              }
          } else {
              echo "Invalid community ID.";
          }
          
          ?>

        </div>


        <div class="wrapper-right">
            <div style="width: 90%;" class="container">

          <div class="input-group py-2 m-auto pr-5 position-relative">

          <i id="icon-search" class="fas fa-search tryy"></i>
          <input type="text" class="form-control search-input"  placeholder="Search NextGen">
          <div class="search-result">


          </div>
          </div>
          </div>


       

            
          <div class="box-share">
            <p class="txt-share"><strong>Who to follow</strong></p>
            <?php 
            foreach($who_users as $user) { 
              //  $u = User::getData($user->user_id);
               $user_follow = Follow::isUserFollow($user_id , $user->id) ;
               ?>
          <div class="grid-share">
          <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">
                      <img
                        src="assets/images/users/<?php echo $user->img; ?>"
                        alt=""
                        class="img-share"
                      />
                    </a>
                    <div>
                      <p>
                      <a style="position: relative; z-index:5; color:black" href="<?php echo $user->username;  ?>">  
                      <strong><?php echo $user->name; ?></strong>
                      </a>
                    </p>
                      <p class="username">@<?php echo $user->username; ?>
                      <?php if (Follow::FollowsYou($user->id , $user_id)) { ?>
                  <span class="ml-1 follows-you">Follows You</span></p>
                  <?php } ?></p>
                    </div>
                    <div>
                      <button class="follow-btn follow-btn-m 
                      <?= $user_follow ? 'following' : 'follow' ?>"
                      data-follow="<?php echo $user->id; ?>"
                      data-user="<?php echo $user_id; ?>"
                      data-profile="<?php echo $u_id; ?>"
                      style="font-weight: 700;">
                      <?php if($user_follow) { ?>
                        Following 
                      <?php } else {  ?>  
                          Follow
                        <?php }  ?> 
                      </button>
                    </div>
                  </div>

                  <?php }?>
         
          
          </div>
  
  
        </div>
      </div>
      </div> 
      <script src="assets/js/search.js"></script>
          <script src="assets/js/photo.js?v=<?php echo time(); ?>"></script>
          <script type="text/javascript" src="assets/js/hashtag.js"></script>
          <script type="text/javascript" src="assets/js/like.js"></script>
          <script type="text/javascript" src="assets/js/comment.js?v=<?php echo time(); ?>"></script>
          <script type="text/javascript" src="assets/js/retweet.js?v=<?php echo time(); ?>"></script>
          <script type="text/javascript" src="assets/js/follow.js?v=<?php echo time(); ?>"></script>
      <script src="https://kit.fontawesome.com/38e12cc51b.js" crossorigin="anonymous"></script>
      <script src="assets/js/jquery-3.5.1.min.js"></script>

        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
</body>
</html> 