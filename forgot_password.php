<?php
include 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $check = $conn->prepare("SELECT id FROM users_table WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE users_table SET otp_code = ?, otp_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $otp, $expiry, $email);
        
        if ($update->execute()) {
            // Seedha OTP response mein bhej rahe hain taaki app usko utha sake
            echo json_encode([
                "success" => true, 
                "message" => "OTP generated successfully",
                "otp" => $otp 
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email not found"]);
    }
}
?>
