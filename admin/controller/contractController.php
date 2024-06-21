<?php

require("../../Connection.php");

// Function to sanitize form input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $contract_name = sanitizeInput($_POST["contract_name"]);

    // Insert query
    $sql = "INSERT INTO contracts (contract_name) VALUES ('$contract_name')";
    if ($con->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Read
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["read"])) {
    // Select query
    $sql = "SELECT * FROM contracts";
    $result = $con->query($sql);
    $contracts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contracts[] = $row;
        }
    }
    echo json_encode($contracts);
    exit;
}

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $contract_id = $_POST["contract_id"];
    $contract_name = sanitizeInput($_POST["contract_name"]);

    // Update query
    $sql = "UPDATE contracts SET contract_name='$contract_name' WHERE contract_id=$contract_id";
    if ($con->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $contract_id = $_POST["contract_id"];

    // Delete query
    $sql = "DELETE FROM contracts WHERE contract_id=$contract_id";
    if ($con->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}


// Initialize response array
$response = array();

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["fetchCont"])) {
    // Query to fetch contracts from the database
    $sql = "SELECT contract_id, contract_name FROM contracts";

    // Perform the query
    $result = $con->query($sql);

    // Check if there are any contracts
    if ($result) {
        // Initialize contracts array
        $contracts = array();

        // Fetch contracts and add them to the array
        while ($row = $result->fetch_assoc()) {
            $contracts[] = $row;
        }

        // Close the result set
        $result->close();

        // Set response status and contracts data
        $response['status'] = 'success';
        $response['contracts'] = $contracts;
    } else {
        // If query fails, set error message in response
        $response['status'] = 'error';
        $response['message'] = 'Failed to fetch contracts: ' . $con->error;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If request method is not GET, set error message in response
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>