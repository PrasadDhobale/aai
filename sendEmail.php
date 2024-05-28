<?php

    use PHPMailer\PHPMailer\PHPMailer;
    if(BASE_URL == 'https://aai.compwallah.com/'){
        $to_email = ($role == "contact_form") ? "dhobaleprasad3@gmail.com" : $email;
        $subject = $subject;
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: AAI Services <services@internship.compwallah.com>\r\n";
        $headers .= "Reply-To: dhobaleprasad3@gmail.com\r\n";

        // Send the email
        if (mail($to_email, $subject, $body, $headers)) {
            if ($role == "evs") {
                echo "<script>alert('Please Verify Your Email From Your Email Inbox')</script>";
            } elseif ($role == "reject") {
                $response['success'] = true;
            } elseif ($role == "password") {
                echo "<script>alert('Your Password Reset link is sent to your verified Email.')</script>";
            } elseif ($role == "contact_form") {
                echo "<script>alert('Thanks for Contacting. " . $name . "\\n We will Contact You Soon.')</script>";
            } elseif ($role == "offer") {
                echo "<script>alert('Offer Letter Sent. ". $name ."')</script>";
            }else if($role = "send_email"){
                // echo "<script>alert('Sent to - ". $email ."')</script>";
            }
        } else {
            echo "<script>alert('Something Went Wrong. Please try again later.');</script>";
        }
    }
    else{
        
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "services.propad@gmail.com";
        $mail->Password = 'lmiqsegofxgsjbnl';
        $mail->Port = 465; //587
        $mail->CharSet = 'UTF-8';
        $mail->SMTPSecure = "ssl"; //tls
        
        $headers = 'MIME-Version: 1.0';
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        
        
        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom($email);
        if($role == "contact_form"){
            $mail->setFrom("dhobaleprasad3@gmail.com");
            $mail->addAddress("dhobaleprasad3@gmail.com");
        }else{
            $mail->addAddress($email);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->header = $subject;

        if ($mail->send()) {
            if($role == "evs"){
                echo  "<script>alert('Please Verify Your Email From Your Email Inbox')</script>";
            }else if($role == "reject"){
                $response['success'] = true;
            } else if($role == "password"){
                echo "<script>alert('Your Password Reset Link is sent to your verified Email :)')</script>";
            } else if($role == "contact_form"){
                echo  "<script>alert('Thanks for Contacting. ".$name."\\n We will Contact You Soon.')</script>";
            } elseif ($role == "offer") {
                echo "<script>alert('Offer Letter Sent. ". $name ."')</script>";
            }else if($role = "send_email"){
                echo "<script>alert('Sent to - ". $email ."')</script>";
            }
        } else {
            $msg = error_get_last()['message'];
            echo "<script>alert('Something Went Wrong. ".$msg."');</script>";
        }
    }
?>