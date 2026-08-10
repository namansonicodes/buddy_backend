<?php
include 'db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // 6 digit OTP generate karein
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Check karein ki email database mein exist karti hai ya nahi
    $check = $conn->prepare("SELECT id FROM users_table WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // OTP aur expiry time database mein save karein
        $update = $conn->prepare("UPDATE users_table SET otp_code = ?, otp_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $otp, $expiry, $email);
        
        if ($update->execute()) {
            // Gmail SMTP ke zariye real email bhejne ki setting
            $to = $email;
            $subject = "Your Buddy App OTP Code";
            $message = "Hello,\n\nYour OTP for password reset is: " . $otp . "\nThis OTP is valid for 10 minutes.";
            
            // Gmail credentials jo aapne abhi generate kiye hain
            $from_email = "harshitvermami4i@gmail.com"; // Yahan apna Gmail daalein
            $app_password = "pbvbytbylvxnrang"; // Yahan wo 16-digit key daalein (bina space ke)

            // Mail headers
            $headers = "From: " . $from_email;

            // Render/Free server par mail send karne ke liye
            // (Note: Agar direct mail() function SMTP support nahi karta, toh hum PHPMailer ya cURL use kar sakte hain)
            @mail($to, $subject, $message, $headers);

            echo json_encode(["success" => true, "message" => "OTP sent successfully to your email"]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email not found"]);
    }
}
?>
