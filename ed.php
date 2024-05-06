<?php
// Function to encrypt data
function encryptData($data, $key) {
    $cipher = "aes-256-cbc"; // Using AES encryption with CBC mode
    $iv = "1234567890123456"; // Fixed IV (Initialization Vector)
    $encryptedData = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv); // Encrypt the data
    $encryptedData = base64_encode($encryptedData); // Encode in base64
    return $encryptedData;
}

// Function to decrypt data
function decryptData($encryptedData, $key) {
    $cipher = "aes-256-cbc"; // Using AES encryption with CBC mode
    $iv = "1234567890123456"; // Fixed IV (Initialization Vector)
    $encryptedData = base64_decode($encryptedData); // Decode from base64
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA, $iv); // Decrypt the data
    return $decryptedData;
}

$secretKey = "aaisecretkey";

// Encrypt the email
$encryptedEmail = encryptData('11', $secretKey);
echo "Encrypted Email: " . $encryptedEmail . "<br>";

// Decrypt the email
$decryptedEmail = decryptData('z8SiW+qTf3NZU9fXirGMjA==', $secretKey);
echo "Decrypted Email: " . $decryptedEmail . "<br>";
?>
