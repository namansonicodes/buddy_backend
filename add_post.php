<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['title']) && isset($data['postContent'])) {
    $title = $conn->real_escape_string($data['title']);
    $postContent = $conn->real_escape_string($data['postContent']);
    $authorName = $conn->real_escape_string($data['authorName']);
    $timestamp = $conn->real_escape_string($data['timestamp']);
    $likesCount = intval($data['likesCount']);
    $isLiked = $data['isLiked'] ? 1 : 0;

    $sql = "INSERT INTO posts_table (title, postContent, authorName, timestamp, likesCount, isLiked) 
            VALUES ('$title', '$postContent', '$authorName', '$timestamp', $likesCount, $isLiked)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true, "message" => "Post added successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters"));
}

$conn->close();
?>