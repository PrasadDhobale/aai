<?php
require '../Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $applicationId = $_POST['application_id'];
    $rejectReason = $_POST['reject_reason'];
    $rejectedByRole = $_POST['role'];
    $userId = $_POST['userId'];
    
    date_default_timezone_set("Asia/Kolkata");
    $rejection_time = date('Y-m-d H:i:s');

    if (!empty($applicationId) && !empty($rejectReason)) {
        $sql = "UPDATE approval_level SET reason = ?, rejected_by_role = ?, rejected_by_id = ?, rejected_at = ? WHERE application_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssisi", $rejectReason, $rejectedByRole, $userId, $rejection_time, $applicationId);

        $response = [];
        if ($stmt->execute()) {
            
            $innerQuery = "select contract_id from pass_applications where application_id = $applicationId";
            $getContractorEmailQ = "select email from contractors where contract_id = ($innerQuery)";

            $contractor = $con->query($getContractorEmailQ)->fetch_assoc();
            $email = $contractor['email'];

            $role = 'reject';
            
            ob_start();
            require 'rejectPassEmail.php';
            $subject = "AAI Visitor Pass [Application Rejected]";
            $body = ob_get_clean();
            require '../sendEmail.php';

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