<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['username']) && isset($data['email']) && isset($data['password'])) {
    $username = $conn->real_escape_string($data['username']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Secure hashing

    // Check if user already exists
    $checkUser = "SELECT * FROM users_table WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($checkUser);

    if($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "Username or Email already exists!"));
    } else {
        $sql = "INSERT INTO users_table (username, email, password) VALUES ('$username', '$email', '$password')";
        if($conn->query($sql) === TRUE) {
            echo json_encode(array("success" => true, "message" => "Registration successful"));
        } else {
            echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
        }
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid parameters"));
}

$conn->close();
?>