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
    $area_name = sanitizeInput($_POST["area_name"]);

    // Insert query
    $sql = "INSERT INTO areas (area_name) VALUES ('$area_name')";
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
    $sql = "SELECT * FROM areas";
    $result = $con->query($sql);
    $areas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $areas[] = $row;
        }
    }
    echo json_encode($areas);
    exit;
}

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $area_id = $_POST["area_id"];
    $area_name = sanitizeInput($_POST["area_name"]);

    // Update query
    $sql = "UPDATE areas SET area_name='$area_name' WHERE area_id=$area_id";
    if ($con->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $area_id = $_POST["area_id"];

    // Delete query
    $sql = "DELETE FROM areas WHERE area_id=$area_id";
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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["fetchAreas"])) {
    // Query to fetch areas from the database
    $sql = "SELECT area_id, area_name FROM areas";

    // Perform the query
    $result = $con->query($sql);

    // Check if there are any areas
    if ($result) {
        // Initialize areas array
        $areas = array();

        // Fetch areas and add them to the array
        while ($row = $result->fetch_assoc()) {
            $areas[] = $row;
        }

        // Close the result set
        $result->close();

        // Set response status and areas data
        $response['status'] = 'success';
        $response['areas'] = $areas;
    } else {
        // If query fails, set error message in response
        $response['status'] = 'error';
        $response['message'] = 'Failed to fetch areas: ' . $con->error;
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