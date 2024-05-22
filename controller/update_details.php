<?php
require('../Connection.php');

if(isset($_POST['application_id']) && isset($_POST['fromTimestamp']) && isset($_POST['toTimestamp']) && isset($_POST['areas'])) {
    $applicationId = $_POST['application_id'];
    $fromTimestamp = $_POST['fromTimestamp'];
    $toTimestamp = $_POST['toTimestamp'];
    $areas = implode(",", $_POST['areas']);

    // Update application details
    $updateSql = "UPDATE pass_applications SET from_timestamp = ?, to_timestamp = ?, areaOfVisit = ? WHERE application_id = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param("sssi", $fromTimestamp, $toTimestamp, $areas, $applicationId);

    if ($updateStmt->execute()) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to update details'));
    }

    $updateStmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Missing parameters'));
}

$con->close();
?>
