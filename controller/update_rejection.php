<?php
require '../Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $applicationId = $_POST['application_id'];
    $rejectReason = $_POST['reject_reason'];
    $role = $_POST['role'];
    $userId = $_POST['userId'];

    // Ensure the application ID and reject reason are provided
    if (!empty($applicationId) && !empty($rejectReason)) {
        
        date_default_timezone_set("Asia/Kolkata");
        $rejection_time = date('Y-m-d H:i:s'); 

        $sql = "UPDATE approval_level SET reason = ?, rejected_by_role = ?, rejected_by_id = ? WHERE application_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssii", $rejectReason, $role, $userId, $applicationId);

        $response = [];
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = $stmt->error;
        }
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'error' => 'Application ID or rejection reason is missing']);
    }
}
?>
