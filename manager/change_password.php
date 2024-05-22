<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isManagerLogin']) || $_SESSION['isManagerLogin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Include the database connection
include "../Connection.php";

// Retrieve current password, new password, and manager ID from POST data

$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$managerID = $_SESSION['manager']['manager_id'];

// Check if the current password matches the one stored in the database
$sql = "SELECT * FROM managers WHERE manager_id = ? AND password = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("is", $managerID, $currentPassword);
$stmt->execute();
$result = $stmt->get_result();

// If the current password is correct, update the password in the database
if ($result->num_rows === 1) {
    // Update the password
    $updateSql = "UPDATE managers SET password = ? WHERE manager_id = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param("ss", $newPassword, $managerID);
    $updateStmt->execute();

    // Check if the password was successfully updated
    if ($updateStmt->affected_rows === 1) {
        
        $_SESSION['manager']['password'] = $newPassword;
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
