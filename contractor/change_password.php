<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isContractorLogin']) || $_SESSION['isContractorLogin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Include the database connection
include "../Connection.php";

// Retrieve current password, new password, and contractor ID from POST data

$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$contractorID = $_SESSION['contractor']['contractor_id'];

// Check if the current password matches the one stored in the database
$sql = "SELECT * FROM contractors WHERE contractor_id = ? AND password = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("is", $contractorID, $currentPassword);
$stmt->execute();
$result = $stmt->get_result();

// If the current password is correct, update the password in the database
if ($result->num_rows === 1) {
    // Update the password
    $updateSql = "UPDATE contractors SET password = ? WHERE contractor_id = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param("ss", $newPassword, $contractorID);
    $updateStmt->execute();

    // Check if the password was successfully updated
    if ($updateStmt->affected_rows === 1) {
        
        $_SESSION['contractor']['password'] = $newPassword;
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
