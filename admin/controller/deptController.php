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
    $department_name = sanitizeInput($_POST["department_name"]);

    // Insert query
    $sql = "INSERT INTO departments (department_name) VALUES ('$department_name')";
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
    $sql = "SELECT * FROM departments";
    $result = $con->query($sql);
    $departments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }
    echo json_encode($departments);
    exit;
}

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $department_id = $_POST["department_id"];
    $department_name = sanitizeInput($_POST["department_name"]);

    // Update query
    $sql = "UPDATE departments SET department_name='$department_name' WHERE department_id=$department_id";
    if ($con->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $department_id = $_POST["department_id"];

    // Delete query
    $sql = "DELETE FROM departments WHERE department_id=$department_id";
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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["fetchDept"])) {
    // Query to fetch departments from the database
    $sql = "SELECT department_id, department_name FROM departments";

    // Perform the query
    $result = $con->query($sql);

    // Check if there are any departments
    if ($result) {
        // Initialize departments array
        $departments = array();

        // Fetch departments and add them to the array
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }

        // Close the result set
        $result->close();

        // Set response status and departments data
        $response['status'] = 'success';
        $response['departments'] = $departments;
    } else {
        // If query fails, set error message in response
        $response['status'] = 'error';
        $response['message'] = 'Failed to fetch departments: ' . $con->error;
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