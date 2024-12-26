<?php

class Community extends User {
    protected static $pdo;

    
    // Method to get all communities
    public static function getAllCommunities() {
        $stmt = self::connect()->prepare("SELECT * FROM communities ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Method to get details of a specific community by its ID
    public static function getCommunityDetails($community_id) {
        $stmt = self::connect()->prepare("SELECT * FROM communities WHERE id = :community_id");
        $stmt->bindParam(':community_id', $community_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Method to get tweets by community ID
    public static function getTweetsByCommunityId($community_id) {
        $stmt = self::connect()->prepare(
            "SELECT tweets.*, users.username, users.img as userImg 
             FROM tweets
             JOIN users ON tweets.user_id = users.id
             WHERE tweets.community_id = :community_id 
             ORDER BY tweets.created_at DESC"
        );
        $stmt->bindParam(':community_id', $community_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
}
