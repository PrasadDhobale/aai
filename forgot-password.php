<?php

session_start();
require_once "connection.php";

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the email address
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    
    try {
        $sql = "SELECT id, email, password, name FROM visitor_data WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

    
        if (!$user) {
            echo "Email address not found.";
            exit;
        }
    
        $id = $user['id'];
        $email = $user['email'];
        // The user is verified, you can send the password here
        $password = $user['password']; // Accessing the password
        
        $role = "password";
        ob_start();
        require 'send_Login_cred.php';
        $subject = "AAI Visitor Pass [Login Credentials]";
        $body = ob_get_clean();
        require 'sendEmail.php';
        exit;        
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
    


} else {
    echo "Invalid request method.";
}
?>
