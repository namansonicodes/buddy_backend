<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['username']) && isset($data['password'])) {
    $identifier = $conn->real_escape_string($data['username']); // Yeh username ya email ho sakta hai
    $password = $data['password'];

    // Dono (username ya email) mein se kisi se bhi match kare
    $sql = "SELECT * FROM users_table WHERE username = '$identifier' OR email = '$identifier'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])) {
            echo json_encode(array(
                "success" => true, 
                "message" => "Login successful", 
                "username" => $row['username'] // Asli username wapas bhejenge
            ));
        } else {
            echo json_encode(array("success" => false, "message" => "Incorrect password"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "User not found"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters"));
}

$conn->close();
?>