<?php
// update_approval.php
require('../Connection.php');

// Check if the application ID is provided in the POST request
if (isset($_POST['application_id']) && isset($_POST['role']) && isset($_POST['userId'])) {

    // Get the application ID from the POST data
    $applicationId = $_POST['application_id'];
    $role = $_POST['role'];
    $userId = $_POST['userId'];

    // Assuming you have a function to update the approval status
    $success = updateApprovalStatus($con, $applicationId, $role, $userId);

    // Prepare the response
    $response = array();
    if ($success) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to update approval status';
    }

    // Send the response as JSON
    echo json_encode($response);
} else {
    // If the application ID is not provided in the POST request
    $response['success'] = false;
    $response['message'] = 'Application ID not provided';
    echo json_encode($response);
}

// Function to update the approval status in the database
function updateApprovalStatus($con, $applicationId, $role, $userId) {
    
    date_default_timezone_set("Asia/Kolkata");
    $approval_time = date('Y-m-d H:i:s'); // Current timestamp

    $update_query = '';

    if($role == 'contractor')
        $update_query = "UPDATE approval_level SET contractor_id = '$userId', contractor_approve_time =  '$approval_time' WHERE application_id = $applicationId";
    if($role == 'manager')
        $update_query = "UPDATE approval_level SET manager_id = '$userId', manager_approve_time =  '$approval_time' WHERE application_id = $applicationId";
    if($role == 'incharge')
        $update_query = "UPDATE approval_level SET incharge_id = '$userId', incharge_approve_time =  '$approval_time' WHERE application_id = $applicationId";
    $result = $con->query($update_query);
    
    return $result ? true : false;

    return true;
}
?>
