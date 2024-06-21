<?php
// Database connection
require('../../Connection.php');

// Function to sanitize form input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize response array
$response = array();

// Check if GET request to fetch contractors
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["fetch"])) {
    // Fetch contract details from the database
    $sql = "SELECT contractors.contractor_id, contractors.contractor_name, contractors.email, contractors.contract_id, contracts.contract_name FROM contractors INNER JOIN contracts ON contractors.contract_id = contracts.contract_id";
    $result = $con->query($sql);

    if ($result) {
        // Initialize array to store contractors
        $contractors = array();
        while ($row = $result->fetch_assoc()) {
            // Include password in the response
            $contract = array(
                "contractor_id" => $row["contractor_id"],
                "contractor_name" => $row["contractor_name"],
                "contract_id" => $row["contract_id"],
                "email" => $row["email"],
                "contract_name" => $row["contract_name"]
            );
            $contractors[] = $contract;
        }
        // Set success response
        $response["status"] = "success";
        $response["contractors"] = $contractors;
    } else {
        // Error fetching contractors
        $response["status"] = "error";
        $response["message"] = "Failed to fetch contractors: " . $con->error;
    }
}
// Check if POST request to register contract
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $contractor_name = sanitizeInput($_POST["contractorName"]);
    $contract_id = sanitizeInput($_POST["contract"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);

    // Insert contract data into the database
    $sql = "INSERT INTO contractors (contractor_name, contract_id, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $contractor_name, $contract_id, $email, $password);
        if ($stmt->execute()) {
            // Record inserted successfully
            $response["status"] = "success";
        } else {
            // Failed to insert record
            $response["status"] = "error";
            $response["message"] = "Failed to register contract: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in prepared statement
        $response["status"] = "error";
        $response["message"] = "Error: Unable to prepare SQL statement.";
    }
}
// Check if PUT request to update contract
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Parse PUT request data
    parse_str(file_get_contents("php://input"), $putData);

    // Sanitize form data
    $contractor_id = sanitizeInput($putData["contractorId"]);
    $contractor_name = sanitizeInput($putData["contractorName"]);
    $contract_id = sanitizeInput($putData["contract"]);
    $email = sanitizeInput($putData["email"]);

    // Update contract data in the database
    $sql = "UPDATE contractors SET  contractor_name=?, contractor_id=?, email=? WHERE contract_id=?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $contractor_name, $contractor_id, $email, $contract_id);
        if ($stmt->execute()) {
            // Record updated successfully
            $response["status"] = "success";
        } else {
            // Failed to update record
            $response["status"] = "error";
            $response["message"] = "Failed to update contract: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in prepared statement
        $response["status"] = "error";
        $response["message"] = "Error: Unable to prepare SQL statement.";
    }
}
// Check if DELETE request to delete contract
else if ($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET["contractorId"])) {
    // Sanitize contract ID
    $contractor_id = sanitizeInput($_GET["contractorId"]);

    // Delete contract from the database
    $sql = "DELETE FROM contractors WHERE contractor_id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $contractor_id);
        if ($stmt->execute()) {
            // contract deleted successfully
            $response["status"] = "success";
        } else {
            // Failed to delete contract
            $response["status"] = "error";
            $response["message"] = "Failed to delete contract: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in prepared statement
        $response["status"] = "error";
        $response["message"] = "Error: Unable to prepare SQL statement.";
    }
} else {
    // Invalid request
    $response["status"] = "error";
    $response["message"] = "Invalid request.";
}

// Return JSON response
header("Content-Type: application/json");
echo json_encode($response);
?>
