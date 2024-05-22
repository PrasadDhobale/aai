<?php

session_start();
require_once "Connection.php";

// Function to encrypt data
function encryptData($data, $key) {
    $cipher = "aes-256-cbc"; // Using AES encryption with CBC mode
    $iv = "1234567890123456"; // Fixed IV (Initialization Vector)
    $encryptedData = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv); // Encrypt the data
    $encryptedData = base64_encode($encryptedData); // Encode in base64
    return $encryptedData;
}


// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the email address
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }
    
    try {
        $sql = '';
        $role = $_POST['role'];
        
        if($role == 'manager')
            $sql = "SELECT manager_id, email, password FROM managers WHERE email = ?";
        else if($role == 'contractor')
            $sql = "SELECT contractor_id, email, password FROM contractors WHERE email = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param('s', $email); // Bind the parameter
        $stmt->execute();
        
        $result = $stmt->get_result();

        $user = $result->fetch_array(MYSQLI_NUM);
    
        if (!$user) {
            echo "Email address not found.";
            exit;
        }
    
        $id = $user[0];
        $email = $user[1];
        $password = $user[2];

        $secretKey = "aaisecretkey";

        // Encrypt data
        $encryptedId = urlencode(encryptData($id, $secretKey));
        $encryptedEmail = urlencode(encryptData($email, $secretKey));
        $encryptedPassword = urlencode(encryptData($password, $secretKey));

        // Construct the string
        $string = "role=$role&&id=$encryptedId&&email=$encryptedEmail&&password=$encryptedPassword";

        
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
