<?php
require('../Connection.php');

if(isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];

    $sql = "SELECT from_timestamp, to_timestamp, areaOfVisit FROM pass_applications WHERE application_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $applicationId);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        // Inside getEditDetails.php

        // Fetch and format areaOfVisit as an array
        $areaOfVisitArray = explode(",",$row["areaOfVisit"]);

        // Output data as JSON
        echo json_encode(array(
            'fromTimestamp' => $row['from_timestamp'],
            'toTimestamp' => $row['to_timestamp'],
            'areaOfVisit' => $areaOfVisitArray
        ));

    } else {
        echo json_encode(array('error' => 'No details found for this application'));
    }

    $stmt->close();
}

$con->close();
?>
