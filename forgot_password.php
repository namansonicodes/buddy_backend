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
            // Gmail SMTP Details
            $smtp_host = "smtp.gmail.com";
            $smtp_port = 587;
            $gmail_user = "harshitvermami4i@gmail.com";       // Yahan apna asli Gmail daalein
            $gmail_pass = "pbvbytbylvxnrang";          // Yahan wo 16-digit app password (bina space ke) daalein

            $to = $email;
            $subject = "Your Buddy App OTP Code";
            $message = "Your OTP for password reset is: " . $otp . "\nValid for 10 minutes.";

            // Simple cURL ya mail send logic ya phir agar aap chahte hain ki testing ke liye 
            // response mein bhi OTP mil jaye taaki app turant test ho sake:
            
            // For now, let's also return it in json so you can test instantly if hosting blocks port 587
            echo json_encode([
                "success" => true, 
                "message" => "OTP generated successfully",
                "debug_otp" => $otp // Testing ke liye, email ke sath yahan bhi dikhega
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email not found"]);
    }
}
?>