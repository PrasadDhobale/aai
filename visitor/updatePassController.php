<?php
require('../Connection.php');
session_start();

if (isset($_POST['requestPass'])) {
    // Retrieve form data
    $application_id = $_POST['application_id']; // Assume application_id is sent as a hidden field in the form
    $passType = $_POST['passType'];
    $passFees = $_POST['passFees'];
    $name = $_POST['name'];
    $sdw = $_POST['sdw'];
    $designation = $_POST['designation'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $companyId = $_POST['companyID'];
    $identity = $_POST['identity'];
    $purposeOfVisit = $_POST['purposeOfVisit'];
    $fromTimestamp = $_POST['fromTimestamp'];
    $toTimestamp = $_POST['toTimestamp'];
    $departmentId = $_POST['department'];
    $contractId = $_POST['contract'];
    $otherContract = isset($_POST['other_contract']) ? $_POST['other_contract'] : 'none';
    $areaOfVisit = implode(",", $_POST['areaOfVisit']);
    $policeClearance = $_POST['policeClearance'];

    date_default_timezone_set("Asia/Kolkata");
    $applyTime = date('Y-m-d H:i:s');

    // Handle file uploads for identity document
    $uploadIdData = null;
    if (isset($_FILES['uploadId']) && $_FILES['uploadId']['error'] === UPLOAD_ERR_OK) {
        $uploadID = $_FILES['uploadId']['tmp_name'];
        $file_mime_type = mime_content_type($uploadID);
        $uploadID_data = file_get_contents($uploadID);
        $uploadIDbase64 = base64_encode($uploadID_data);
        $uploadIdData = "data:$file_mime_type;base64,$uploadIDbase64";
    }

    // Handle file uploads for police clearance
    $uploadClearanceData = null;
    $documentNumber = null;
    $issueDate = null;
    if ($policeClearance == "yes") {
        $documentNumber = $_POST['documentNumber'];
        $issueDate = $_POST['issueDate'];

        if (isset($_FILES['uploadClearance']) && $_FILES['uploadClearance']['error'] === UPLOAD_ERR_OK) {
            $uploadClearance = $_FILES['uploadClearance']['tmp_name'];
            $file_mime_type = mime_content_type($uploadClearance);
            $uploadClearance_data = file_get_contents($uploadClearance);
            $uploadClearancebase64 = base64_encode($uploadClearance_data);
            $uploadClearanceData = "data:$file_mime_type;base64,$uploadClearancebase64";
        }
    }

    // Update the pass application in the database
    $sql = "UPDATE pass_applications 
            SET pass_type = ?, pass_fees = ?, name = ?, sdw = ?, designation = ?, phone = ?, address = ?, 
                company_id = ?, identity = ?, purpose_of_visit = ?, from_timestamp = ?, to_timestamp = ?, 
                police_clearance = ?, document_number = ?, issue_date = ?, contract_id = ?, other_contract = ?, 
                department_id = ?, areaOfVisit = ?, apply_time = ?";
    
    // Add upload_id and upload_clearance to the query if new files are uploaded
    if ($uploadIdData !== null) {
        $sql .= ", upload_id = ?";
    }
    if ($uploadClearanceData !== null) {
        $sql .= ", upload_clearance = ?";
    }
    $sql .= " WHERE application_id = ?";

    // Prepare and bind parameters
    $stmt = $con->prepare($sql);
    if ($uploadIdData !== null && $uploadClearanceData !== null) {
        $stmt->bind_param("ssssssssssssssssssssssi", 
            $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, 
            $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $documentNumber, $issueDate, 
            $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime, $uploadIdData, $uploadClearanceData, $application_id);
    } elseif ($uploadIdData !== null) {
        $stmt->bind_param("sssssssssssssssssssssi", 
            $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, 
            $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $documentNumber, $issueDate, 
            $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime, $uploadIdData, $application_id);
    } elseif ($uploadClearanceData !== null) {
        $stmt->bind_param("sssssssssssssssssssssi", 
            $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, 
            $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $documentNumber, $issueDate, 
            $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime, $uploadClearanceData, $application_id);
    } else {
        $stmt->bind_param("ssssssssssssssssssi", 
            $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, 
            $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $documentNumber, $issueDate, 
            $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime, $application_id);
    }

    // Execute statement
    if ($stmt->execute()) {
        
        $updateReApplied = 'UPDATE approval_level set contractor_id = 0, contractor_approve_time = null, manager_id = 0, manager_approve_time = null, incharge_id = 0, incharge_approve_time = null, reason = null, rejected_by_role = null, rejected_by_id = null, rejected_at = null WHERE application_id = '.$application_id;
        $f = mysqli_query($con, $updateReApplied);
        if($f)
            $_SESSION['message'] = "Pass application updated successfully. Application ID: ". $application_id;
        else{
            $_SESSION['message'] = "Something went wrong";
        }
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }

    // Close statement and connection
    $stmt->close();
}
?>