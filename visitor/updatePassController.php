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
    $adhaar = $_POST['adhaar'];
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

     // Handle appointment letter data
     $appointmentLetter = $_POST['appointmentLetter'];

     $uploadAppointmentData = null;
     $startDate = null;
     $endDate = null;
 
     if ($appointmentLetter == "yes") {
         $startDate = $_POST['startDate'];
         $endDate = $_POST['endDate'];
         echo $startDate. ' '. $endDate;
         $uploadAppointment = $_FILES['uploadAppointment']['tmp_name'];
         $file_mime_type = mime_content_type($uploadAppointment);
         $uploadAppointment_data = file_get_contents($uploadAppointment);
         $uploadAppointmentbase64 = base64_encode($uploadAppointment_data);
         $uploadAppointmentData = "data:$file_mime_type;base64,$uploadAppointmentbase64";
     }
 

    // Update the pass application in the database
    $sql_pass = "UPDATE pass_applications 
            SET pass_type = ?, pass_fees = ?, purpose_of_visit = ?, from_timestamp = ?, to_timestamp = ?, 
                police_clearance = ?, document_number = ?, issue_date = ?, appointment_letter = ?, st_date = ?, end_date = ?, contract_id = ?, other_contract = ?, 
                department_id = ?, areaOfVisit = ?, apply_time = ?";
    
    // Add upload_clearance to the query if a new file is uploaded
    if ($uploadClearanceData !== null) {
        $sql_pass .= ", upload_clearance = ?";
    }

    if ($uploadAppointmentData !== null) {
        $sql_pass .= ", upload_appointment = ?";
    }
    $sql_pass .= " WHERE application_id = ?";

    // Prepare and bind parameters
    $stmt_pass = $con->prepare($sql_pass);
    if ($uploadClearanceData !== null) {


        if ($uploadAppointmentData !== null) {
            $stmt_pass->bind_param("ssssssssssssssssssi", 
            $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, 
            $documentNumber, $issueDate, $appointmentLetter, $startDate, $endDate, $contractId, $otherContract, $departmentId, $areaOfVisit, 
            $applyTime, $uploadClearanceData, $uploadAppointmentData, $application_id);
        }else{
            $stmt_pass->bind_param("sssssssssssssssssi", 
            $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, 
            $documentNumber, $issueDate, $appointmentLetter, $startDate, $endDate, $contractId, $otherContract, $departmentId, $areaOfVisit, 
            $applyTime, $uploadClearanceData, $application_id);
        }

    } else {

        echo "no police";
        if ($uploadAppointmentData !== null) {
            $stmt_pass->bind_param("sssssssssssssssssi", 
            $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, 
            $documentNumber, $issueDate, $appointmentLetter, $startDate, $endDate, $contractId, $otherContract, $departmentId, $areaOfVisit, 
            $applyTime, $uploadAppointmentData, $application_id);
        }else{
            $stmt_pass->bind_param("ssssssssssssssssi", 
            $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, 
            $documentNumber, $issueDate, $appointmentLetter, $startDate, $endDate, $contractId, $otherContract, $departmentId, $areaOfVisit, 
            $applyTime, $application_id);
        }
    }

    // Update the visitor data in the database
    $sql_visitor = "UPDATE visitor_data 
            SET name = ?, sdw = ?, designation = ?, phone = ?, adhaar_no = ?, address = ?, 
                company_id = ?, identity = ?";

    // Add upload_id to the query if a new file is uploaded
    if ($uploadIdData !== null) {
        $sql_visitor .= ", upload_id = ?";
    }
    $sql_visitor .= " WHERE id = (SELECT visitor_id FROM pass_applications WHERE application_id = ?)";
    

    // Prepare and bind parameters
    $stmt_visitor = $con->prepare($sql_visitor);
    if ($uploadIdData !== null) {
        $stmt_visitor->bind_param("sssssssssi", 
            $name, $sdw, $designation, $phone, $adhaar, $address, $companyId, $identity, $uploadIdData, $application_id);
    } else {
        $stmt_visitor->bind_param("ssssssssi", 
            $name, $sdw, $designation, $phone, $adhaar, $address, $companyId, $identity, $application_id);
    }

    // Execute statements
    $stmt_pass_result = $stmt_pass->execute();
    $stmt_visitor_result = $stmt_visitor->execute();

    if ($stmt_pass_result && $stmt_visitor_result) {
        $updateReApplied = 'UPDATE approval_level 
                            SET contractor_id = 0, contractor_approve_time = NULL, manager_id = 0, manager_approve_time = NULL, 
                                incharge_id = 0, incharge_approve_time = NULL, reason = NULL, rejected_by_role = NULL, 
                                rejected_by_id = NULL, rejected_at = NULL 
                            WHERE application_id = ?';
        
        $stmt_updateReApplied = $con->prepare($updateReApplied);
        $stmt_updateReApplied->bind_param("i", $application_id);
        $updateReApplied_result = $stmt_updateReApplied->execute();

        if ($updateReApplied_result) {
            $_SESSION['message'] = "Pass application updated successfully. Application ID: " . $application_id;
        } else {
            $_SESSION['message'] = "Something went wrong with updating the approval levels.";
        }
        header('Location: index.php');
    } else {
        echo "Error: " . $con->error;
    }

    // Close statements and connection
    $stmt_pass->close();
    $stmt_visitor->close();
    $stmt_updateReApplied->close();
    $con->close();
}
?>
