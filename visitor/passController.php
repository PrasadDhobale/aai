<?php
require('../Connection.php');
session_start();

if(isset($_POST['requestPass'])) {
    // Retrieve form data
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
    $areaOfVisit = implode(",", $_POST['areaOfVisit']);    

    date_default_timezone_set("Asia/Kolkata");
    $applyTime = date('Y-m-d H:i:s'); 

    // Handle file uploads
    $uploadID = $_FILES['uploadId']['tmp_name'];
    $file_mime_type = mime_content_type($uploadID);
    $uploadID_data = file_get_contents($uploadID);
    $uploadIDbase64 = base64_encode($uploadID_data);
    $uploadIdData = "data:$file_mime_type;base64,$uploadIDbase64";
    
    $application_id = rand(1000, 9000);

    $policeClearance = $_POST['policeClearance'];

    $stmt = '';
    $sql = '';
    
    if($policeClearance == "yes"){
        $documentNumber = $_POST['documentNumber'];
        $issueDate = $_POST['issueDate'];
        $uploadClearance = $_FILES['uploadClearance']['tmp_name'];
        $file_mime_type = mime_content_type($uploadClearance);
        $uploadClearance_data = file_get_contents($uploadClearance);
        $uploadClearancebase64 = base64_encode($uploadClearance_data);
        $uploadClearanceData = "data:$file_mime_type;base64,$uploadClearancebase64";

        // Prepare insert statement
        $sql = "INSERT INTO pass_applications 
        (application_id, pass_type, pass_fees, name, sdw, designation, phone, address, company_id, identity, upload_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, upload_clearance, document_number, issue_date, contract_id, department_id, areaOfVisit, apply_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Prepare and bind parameters
        $stmt = $con->prepare($sql);
        $stmt->bind_param("issssssssssssssssssiss", $application_id, $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, $uploadIdData, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $uploadClearanceData, $documentNumber, $issueDate, $contractId, $departmentId, $areaOfVisit, $applyTime);

    }else{
        // Prepare insert statement
        $sql = "INSERT INTO pass_applications 
        (application_id, pass_type, pass_fees, name, sdw, designation, phone, address, company_id, identity, upload_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, contract_id, department_id, areaOfVisit, apply_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Prepare and bind parameters
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isssssssssssssssiss", $application_id, $passType, $passFees, $name, $sdw, $designation, $phone, $address, $companyId, $identity, $uploadIdData, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $contractId, $departmentId, $areaOfVisit, $applyTime);

    }
    

    
    // Execute statement
    if ($stmt->execute()) {        
        // Prepare insert statement for approval_level table
        $approval_sql = "INSERT INTO approval_level 
            (application_id, contractor_id, contractor_approve_time, manager_id, manager_approve_time, clerk_id, clerk_approve_time, incharge_id, incharge_approve_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt_approval = $con->prepare($approval_sql);
        $stmt_approval->bind_param("iisisisis", $application_id, $contractorId, $contractorApproveTime, $managerId, $managerApproveTime, $clerkId, $clerkApproveTime, $inchargeId, $inchargeApproveTime);
        
        // Set default values for IDs and timestamps
        $contractorId = 0;
        $contractorApproveTime = null;
        $managerId = 0;
        $managerApproveTime = null;
        $clerkId = 0;
        $clerkApproveTime = null;
        $inchargeId = 0;
        $inchargeApproveTime = null;

        // Execute statement
        if ($stmt_approval->execute()) {
            $_SESSION['message'] = "Pass application submitted successfully<br> Your Application ID : ". $application_id;
            
            header('Location: index.php');
        } else {
            echo "Error: " . $approval_sql . "<br>" . $con->error;
        }
    } else {
        echo "Error: " . $approval_sql . "<br>" . $con->error;
    }

    // Close statement and connection
    $stmt->close();
    $con->close();
}
?>
