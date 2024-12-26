<?php
include '../init.php'; 

function processLike($user_id, $tweet_id) {
    // Ensuring all IDs are provided: from comment.php
    if (empty($user_id) || empty($tweet_id)) {
        echo "Error: Missing ID, user ID, or tweet ID.";
        return;
    }

    date_default_timezone_set("Asia/Beirut");


    // Check if the user has already liked the tweet
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND post_id = :tweet_id");
    $stmt->execute([':user_id' => $user_id, ':tweet_id' => $tweet_id]);
    if ($stmt->fetchColumn() > 0) {
        echo "Error: You have already liked this tweet.";
        return;
    }

    // Get the owner of the tweet
    $stmt = $pdo->prepare("SELECT user_id FROM tweets WHERE post_id = :tweet_id");
    $stmt->execute([':tweet_id' => $tweet_id]);
    $for_user = $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];

    if ($for_user != $user_id) {
        // Preparing data for a notification
        $stmt = $pdo->prepare("INSERT INTO notifications (notify_for, notify_from, target, type, time, count, status) VALUES (:notify_for, :notify_from, :target, 'like', :time, '0', '0')");
        $stmt->execute([
            ':notify_for' => $for_user,
            ':notify_from' => $user_id,
            ':target' => $tweet_id,
            ':time' => date("Y-m-d H:i:s")
        ]);
    }

    // Preparing data for a like with a specific ID
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':post_id' => $tweet_id
    ]);

    echo "Tweet liked successfully.";
}

// Assuming this script can be triggered with id, user_id, and tweet_id parameters
if (isset($_GET['user_id']) && isset($_GET['tweet_id'])) {
    // If triggered via a GET request for testing purposes
    processLike($_GET['user_id'], $_GET['tweet_id']);
} else {
    echo "Error: This script expects id, user_id, and tweet_id as input.";
}

?>
