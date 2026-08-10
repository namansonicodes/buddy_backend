<?php
include 'db_connect.php';

// Check if email is posted
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Generate 6 digit OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP valid for 10 mins

    // Check if email exists in users_table
    $check = $conn->prepare("SELECT id FROM users_table WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Save OTP and expiry to database
        $update = $conn->prepare("UPDATE users_table SET otp_code = ?, otp_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $otp, $expiry, $email);
        
        if ($update->execute()) {
            // Here you can send mail or return OTP for testing
            echo json_encode(["success" => true, "message" => "OTP generated successfully", "otp" => $otp]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email not found in database"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please provide an email"]);
}
?>