<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['id']) && isset($data['authorName'])) {
    $id = intval($data['id']);
    $requestAuthor = $conn->real_escape_string($data['authorName']);

    // Pehle check karo ki database mein is post ka asli author kaun hai
    $checkSql = "SELECT authorName FROM posts_table WHERE id = $id";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dbAuthor = $row['authorName'];

        // Agar request bhejne wala aur database wala author match karta hai, tabhi delete hoga
        if ($dbAuthor === $requestAuthor) {
            $deleteSql = "DELETE FROM posts_table WHERE id = $id";
            if ($conn->query($deleteSql) === TRUE) {
                echo json_encode(array("success" => true, "message" => "Post deleted successfully"));
            } else {
                echo json_encode(array("success" => false, "message" => "Database error: " . $conn->error));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "You can only delete your own posts!"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Post not found"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters"));
}

$conn->close();
?>