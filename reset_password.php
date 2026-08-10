<?php
include 'db_connect.php';

if (isset($_POST['email']) && isset($_POST['otp']) && isset($_POST['new_password'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    $raw_password = $_POST['new_password'];

    // Password ko secure hash mein convert karna zaroori hai
    $new_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users_table WHERE email = ? AND otp_code = ? AND otp_expiry >= NOW()");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE users_table SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
        $update->bind_param("ss", $new_password, $email);
        
        if ($update->execute()) {
            echo json_encode(["success" => true, "message" => "Password reset successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update password"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid OTP or OTP expired"]);
    }
}
?>
