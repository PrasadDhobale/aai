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

// Check if GET request to fetch managers
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["fetch"])) {
    // Fetch manager details from the database
    $sql = "SELECT managers.manager_id, managers.first_name, managers.last_name, managers.phone, managers.email, managers.dept_id, departments.department_name FROM managers INNER JOIN departments ON managers.dept_id = departments.department_id";
    $result = $con->query($sql);

    if ($result) {
        // Initialize array to store managers
        $managers = array();
        while ($row = $result->fetch_assoc()) {
            // Include password in the response
            $manager = array(
                "manager_id" => $row["manager_id"],
                "first_name" => $row["first_name"],
                "last_name" => $row["last_name"],
                "phone" => $row["phone"],
                "dept_id" => $row["dept_id"],
                "email" => $row["email"],
                "department_name" => $row["department_name"]
            );
            $managers[] = $manager;
        }
        // Set success response
        $response["status"] = "success";
        $response["managers"] = $managers;
    } else {
        // Error fetching managers
        $response["status"] = "error";
        $response["message"] = "Failed to fetch managers: " . $con->error;
    }
}
// Check if POST request to register manager
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $first_name = sanitizeInput($_POST["firstName"]);
    $last_name = sanitizeInput($_POST["lastName"]);
    $phone = sanitizeInput($_POST["phone"]);
    $dept_id = sanitizeInput($_POST["department"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);

    // Insert manager data into the database
    $sql = "INSERT INTO managers (first_name, last_name, phone, dept_id, email, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssss", $first_name, $last_name, $phone, $dept_id, $email, $password);
        if ($stmt->execute()) {
            // Record inserted successfully
            $response["status"] = "success";
        } else {
            // Failed to insert record
            $response["status"] = "error";
            $response["message"] = "Failed to register manager: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in prepared statement
        $response["status"] = "error";
        $response["message"] = "Error: Unable to prepare SQL statement.";
    }
}
// Check if PUT request to update manager
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Parse PUT request data
    parse_str(file_get_contents("php://input"), $putData);

    // Sanitize form data
    $manager_id = sanitizeInput($putData["managerId"]);
    $first_name = sanitizeInput($putData["firstName"]);
    $last_name = sanitizeInput($putData["lastName"]);
    $phone = sanitizeInput($putData["phone"]);
    $dept_id = sanitizeInput($putData["department"]);
    $email = sanitizeInput($putData["email"]);

    // Update manager data in the database
    $sql = "UPDATE managers SET first_name=?, last_name=?, phone=?, dept_id=?, email=? WHERE manager_id=?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $dept_id, $email, $manager_id);
        if ($stmt->execute()) {
            // Record updated successfully
            $response["status"] = "success";
        } else {
            // Failed to update record
            $response["status"] = "error";
            $response["message"] = "Failed to update manager: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in prepared statement
        $response["status"] = "error";
        $response["message"] = "Error: Unable to prepare SQL statement.";
    }
}
// Check if DELETE request to delete manager
else if ($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET["managerId"])) {
    // Sanitize manager ID
    $manager_id = sanitizeInput($_GET["managerId"]);

    // Delete manager from the database
    $sql = "DELETE FROM managers WHERE manager_id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $manager_id);
        if ($stmt->execute()) {
            // Manager deleted successfully
            $response["status"] = "success";
        } else {
            // Failed to delete manager
            $response["status"] = "error";
            $response["message"] = "Failed to delete manager: " . $stmt->error;
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
