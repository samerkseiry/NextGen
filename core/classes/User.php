<?php 


class User extends Connect {
    
        protected static $pdo;

        // public function __construct($pdo)  {
        //     $this->pdo = $pdo;
        // } 
         
      public static function checkInput ($input) {
        $input = htmlspecialchars($input);
        $input = trim($input);
        $input = stripslashes($input);
        return $input;
      }  

      public static  function login ($email , $password) {
        $stmt = self::connect()->prepare("SELECT `id` from `users` WHERE `email` = :email AND `password` = :password");
        $stmt->bindParam(":email" , $email , PDO::PARAM_STR);
        $password =md5($password);
        $stmt->bindParam(":password" , $password , PDO::PARAM_STR);    
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
         
        if ($stmt->rowCount() > 0) {
            $_SESSION['user_id'] = $user->id;
            header('location: ../home.php');
        } else {return false; }
      }

    

      public static function create($table , $fields = array()) {
            $colms = implode(',' , array_keys($fields));
            $values = ':' . implode(', :' , array_keys($fields));
            $sql = "INSERT INTO {$table} ({$colms}) VALUES ({$values})";
            $pdo = self::connect();
            $pdo->beginTransaction(); 
            if($stmt = $pdo->prepare($sql)) {
                  foreach($fields as $key => $data) {
                    $stmt->bindValue(':'. $key , $data );
                  }
                  if ($stmt->execute() === FALSE) {
                    $pdo->rollback();
                  } else {
                    $user_id = $pdo->lastInsertId();
                    $pdo->commit();
                  }
                  return $user_id;
            }
      }
      public static function register($email , $password , $name , $username) {
    
        $pdo = self::connect();
        $pdo->beginTransaction();      
        $stmt = $pdo->prepare("INSERT INTO `users` (`email` , `password` , `name` , `username`) Values (:email , :password , :name , :username)");

        $stmt->bindParam(":email" , $email , PDO::PARAM_STR);
        $password =md5($password);
        $stmt->bindParam(":password" , $password , PDO::PARAM_STR); 
        $stmt->bindParam(":name" , $name , PDO::PARAM_STR);
        $stmt->bindParam(":username" , $username , PDO::PARAM_STR);
    
        if ($stmt->execute() === FALSE) {
          $pdo->rollback();
          echo 'Unable to insert data';
        } else {
          $user_id = $pdo->lastInsertId();
          $pdo->commit();
        }
          $_SESSION['user_id'] = $user_id;
          
          // make user follow the owner by default and notofications also
          date_default_timezone_set("Africa/Cairo");
          $data = [
              'follower_id' => $user_id , 
              'following_id' => 2 , 
              'time' => date("Y-m-d H:i:s") 
          ];
          User::create('follow' , $data);
          $data_notify = [
            'notify_for' => 2,
            'notify_from' => $user_id ,
            'target' => 0, 
            'type' => 'follow' ,
            'time' => date("Y-m-d H:i:s") ,
            'count' => '0' , 
            'status' => '0'
            ];
            Tweet::create('notifications' , $data_notify);

          $_SESSION['welcome'] = 'welcome';
          header('location: ../home.php')  ;

      } 
      public static function update($table , $user_id , $fields = array()){
          $colms = '';
          $loopCount = 1;
          // to know when i insert ',' 
          foreach ($fields as $name => $value) {
            $colms .= "`{$name}` = :{$name}";
            if($loopCount < count($fields)) {
                $colms .= ', ' ; }

              $loopCount++;  
          }
          $sql = "UPDATE {$table} SET {$colms} WHERE id = {$user_id}";
          $pdo = self::connect(); 
            if($stmt = $pdo->prepare($sql)) {
                  foreach($fields as $key => $data) {
                    $stmt->bindValue(':'. $key , $data );
                  }
                  $stmt->execute();
                  return true;
            }

      } 

      public static function delete($table, $array){
        $sql   = "DELETE FROM " . $table;
        $where = " WHERE ";
    
        foreach($array as $key => $value){
          $sql .= $where . $key . " = " . $value . "";
          $where = " AND ";
        }
        $sql .= ";";
       
        $stmt = self::connect()->prepare($sql);
        $stmt->execute();
      }

      public static function getData($id) {
        $stmt = self::connect()->prepare("SELECT * from `users` WHERE `id` = :id");
        $stmt->bindParam(":id" , $id , PDO::PARAM_STR);
        $stmt->execute();
       return $stmt->fetch(PDO::FETCH_OBJ);
      }
        public static function logout () {
            $_SESSION = array();
            session_destroy();
            header('location: ../index.php');
        }

       public static function checkEmail($email) {
        $stmt = self::connect()->prepare("SELECT `email` from `users` WHERE `email` = :email");
        $stmt->bindParam(":email" , $email , PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else return false;
        } 
        
        public static function checkUserName($username) {
          $stmt = self::connect()->prepare("SELECT `username` from `users` WHERE `username` = :username");
          $stmt->bindParam(":username" , $username , PDO::PARAM_STR);
          $stmt->execute();
  
          if ($stmt->rowCount() > 0) {
              return true;
          } else return false;
          } 

          public static function checkLogIn () {
              if (isset($_SESSION['user_id']))
                    return true;
              else return false;      
          }

          public static function getIdByUsername($username) {
            $stmt = self::connect()->prepare("SELECT `id` from `users` where `username` = :username");
            $stmt->bindParam(":username" , $username , PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user->id;
          }

          public static function getUserNameById($id) {
            $stmt = self::connect()->prepare("SELECT `username` from `users` where `id` = :id");
            $stmt->bindParam(":id" , $id , PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user->username;
          }

          public static function search($search){
            $stmt = self::connect()->prepare("SELECT `id`,`username`,`name`,`img`,`imgCover` FROM `users`
            WHERE `username` LIKE ? OR `name` LIKE ?");
            $stmt->bindValue(1, $search.'%', PDO::PARAM_STR);
            $stmt->bindValue(2, $search.'%', PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
          } 
          public static function CountNotification($user_id){
            $stmt = self::connect()->prepare("SELECT COUNT(notify_for) as count FROM `notifications`
            WHERE notify_for = :user_id AND count = 0");
             $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            $u = $stmt->fetch(PDO::FETCH_OBJ);
            return $u->count;
          } 
          public static function notification($user_id){
            $stmt = self::connect()->prepare("SELECT * FROM `notifications`
            WHERE notify_for = :user_id ORDER BY time DESC");
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
          } 
          public static function updateNotifications($user_id){
            $stmt = self::connect()->prepare("UPDATE `notifications` SET count = 1
             WHERE notify_for = :user_id AND count = 0" );
            $stmt->bindParam(":user_id" , $user_id , PDO::PARAM_STR);
             $s =$stmt->execute();
             if($s)
              return true;
            else return false;  
          } 

          public static function countAllUsers() {
            $stmt = self::connect()->prepare("SELECT COUNT(*) as count FROM users");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ)->count;
        }
    
        // Added getAllUsers method
        public static function getAllUsers() {
            $stmt = self::connect()->prepare("SELECT * FROM `users`");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        public static function deleteUserById($id) {
          $db = self::connect();
          // Begin transaction
          $db->beginTransaction();
      
          try {
              // Delete related comments
              $stmt = $db->prepare("DELETE FROM `comments` WHERE `user_id` = :id");
              $stmt->bindParam(":id", $id, PDO::PARAM_INT);
              $stmt->execute();
      
              // Delete related likes
              $stmt = $db->prepare("DELETE FROM `likes` WHERE `user_id` = :id");
              $stmt->bindParam(":id", $id, PDO::PARAM_INT);
              $stmt->execute();
      
              // Delete related notifications
              $stmt = $db->prepare("DELETE FROM `notifications` WHERE `notify_for` = :id OR `notify_from` = :id");
              $stmt->bindParam(":id", $id, PDO::PARAM_INT);
              $stmt->execute();
      
              // Delete related posts
              // Note: This should also handle cascading deletes for any entities related to posts (e.g., comments and likes specifically for those posts)
              $stmt = $db->prepare("DELETE FROM `posts` WHERE `user_id` = :id");
              $stmt->bindParam(":id", $id, PDO::PARAM_INT);
              $stmt->execute();
      
              // Finally, delete the user
              $stmt = $db->prepare("DELETE FROM `users` WHERE `id` = :id");
              $stmt->bindParam(":id", $id, PDO::PARAM_INT);
              $stmt->execute();
      
              // Commit the transaction
              $db->commit();
              return true;
          } catch (Exception $e) {
              // Rollback the transaction in case of error
              $db->rollBack();
              return false;
          }
      }
      
      
      public static function updateUser($userId, $username, $email) {
        $stmt = self::connect()->prepare("UPDATE `users` SET `username` = :username, `email` = :email WHERE `id` = :userId");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public static function addUser($username, $email, $password) {
      $pdo = self::connect();
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', md5($password)); //hashing the password
      return $stmt->execute();
  }
  
}

