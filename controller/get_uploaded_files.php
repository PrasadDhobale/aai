<?php
// get_uploaded_files.php

require('../Connection.php');

if(isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];

    // Fetch uploaded ID from visitor_data and police clearance from pass_applications
    $sql = "SELECT vd.upload_id, pa.upload_clearance, pa.upload_appointment
            FROM pass_applications pa
            JOIN visitor_data vd ON pa.visitor_id = vd.id
            WHERE pa.application_id = ?";
    
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $applicationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            // Decode the base64 encoded data
            $uploadIdData = $row['upload_id'];
            $uploadClearanceData = $row['upload_clearance'];
            $uploadAppointmentData = $row['upload_appointment'];

            echo json_encode(array('success' => true, 'uploadIdData' => $uploadIdData, 'uploadClearanceData' => $uploadClearanceData, 'uploadAppointmentData' => $uploadAppointmentData));
        } else {
            echo json_encode(array('success' => false, 'message' => 'No records found.'));
        }

        $stmt->close();
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to prepare SQL statement.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Application ID not provided.'));
}

$con->close();
?>
