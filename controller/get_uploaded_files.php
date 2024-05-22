<?php
// get_uploaded_files.php

require('../Connection.php');

if(isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];

    // Fetch uploaded ID and police clearance from the database
    $sql = "SELECT upload_id, upload_clearance FROM pass_applications WHERE application_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $applicationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        // Decode the base64 encoded data
        $uploadIdData = $row['upload_id'];
        $uploadClearanceData = $row['upload_clearance'];

        echo json_encode(array('success' => true, 'uploadIdData' => $uploadIdData, 'uploadClearanceData' => $uploadClearanceData));
    } else {
        echo json_encode(array('success' => false));
    }

    $stmt->close();
}

$con->close();
?>
