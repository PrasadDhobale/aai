<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isvisitorlogin']) || $_SESSION['isvisitorlogin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit;
}

// Include the database connection
include "../Connection.php";

// Retrieve current password, new password, and visitor ID from POST data

$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$visitorID = $_SESSION['visitor']['id'];

// Check if the current password matches the one stored in the database
$sql = "SELECT * FROM visitor_data WHERE id = ? AND password = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("is", $visitorID, $currentPassword);
$stmt->execute();
$result = $stmt->get_result();

// If the current password is correct, update the password in the database
if ($result->num_rows === 1) {
    // Update the password
    $updateSql = "UPDATE visitor_data SET password = ? WHERE id = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param("ss", $newPassword, $visitorID);
    $updateStmt->execute();

    // Check if the password was successfully updated
    if ($updateStmt->affected_rows === 1) {
        
        $_SESSION['visitor']['password'] = $newPassword;
        echo "Password changed successfully.";
    } else {
        // Error updating password
        echo "Error: Unable to change password.";
    }

    // Close the prepared statement
    $updateStmt->close();
} else {
    // Current password does not match
    echo $currentPassword." Error: Current password is incorrect.";
}

// Close the database connection
$stmt->close();
$con->close();
?>
