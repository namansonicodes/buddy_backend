<?php
header('Content-Type: application/json');
include 'db_connect.php';

$sql = "SELECT * FROM posts_table ORDER BY id DESC";
$result = $conn->query($sql);

$posts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['isLiked'] = (bool)$row['isLiked'];
        $posts[] = $row;
    }
}

echo json_encode($posts);
$conn->close();
?>