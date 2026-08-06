<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['id'])) {
    $id = intval($data['id']);
    $likesCount = intval($data['likesCount']);
    $isLiked = $data['isLiked'] ? 1 : 0;

    $sql = "UPDATE posts_table SET likesCount = $likesCount, isLiked = $isLiked WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true, "message" => "Like updated successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters"));
}

$conn->close();
?>