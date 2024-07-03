<?php
require('../Connection.php');
session_start();

if (isset($_POST['requestPass'])) {
    // Retrieve form data
    $phone = $_POST['phone'];

    // Check if phone number already exists
    $checkPhoneSql = "SELECT COUNT(*) FROM visitor_data WHERE phone = ?";
    $stmt = $con->prepare($checkPhoneSql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->bind_result($phoneCount);
    $stmt->fetch();
    $stmt->close();

    if ($phoneCount > 0) {
        $_SESSION['message'] = "Mobile number already exists, use another mobile number.";
        header('Location: index.php');
        exit();
    }

    // Visitor data
    $name = $_POST['name'];
    $sdw = $_POST['sdw'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $companyId = $_POST['companyID'];
    $identity = $_POST['identity'];

    // Handle file upload for visitor identity
    $uploadID = $_FILES['uploadId']['tmp_name'];
    $file_mime_type = mime_content_type($uploadID);
    $uploadID_data = file_get_contents($uploadID);
    $uploadIDbase64 = base64_encode($uploadID_data);
    $uploadIdData = "data:$file_mime_type;base64,$uploadIDbase64";
    
    // Insert visitor data into visitor_data table
    $visitorSql = "INSERT INTO visitor_data (name, sdw, designation, phone, address, company_id, identity, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($visitorSql);
    $stmt->bind_param("ssssssss", $name, $sdw, $designation, $phone, $address, $companyId, $identity, $uploadIdData);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        exit();
    }

    // Get the last inserted visitor_id
    $visitor_id = $stmt->insert_id;
    $stmt->close();

    // Pass application data
    $passType = $_POST['passType'];
    $passFees = $_POST['passFees'];
    $purposeOfVisit = $_POST['purposeOfVisit'];
    $fromTimestamp = $_POST['fromTimestamp'];
    $toTimestamp = $_POST['toTimestamp'];
    $departmentId = $_POST['department'];
    $contractId = $_POST['contract'];
    $otherContract = isset($_POST['other_contract']) ? $_POST['other_contract'] : 'none';
    $areaOfVisit = implode(",", $_POST['areaOfVisit']);
    date_default_timezone_set("Asia/Kolkata");
    $applyTime = date('Y-m-d H:i:s');
    $policeClearance = $_POST['policeClearance'];
    
    // Handle file upload for police clearance if applicable
    $uploadClearanceData = null;
    $documentNumber = null;
    $issueDate = null;

    if ($policeClearance == "yes") {
        $documentNumber = $_POST['documentNumber'];
        $issueDate = $_POST['issueDate'];
        $uploadClearance = $_FILES['uploadClearance']['tmp_name'];
        $file_mime_type = mime_content_type($uploadClearance);
        $uploadClearance_data = file_get_contents($uploadClearance);
        $uploadClearancebase64 = base64_encode($uploadClearance_data);
        $uploadClearanceData = "data:$file_mime_type;base64,$uploadClearancebase64";
    }

    // Insert pass application data into pass_applications table
    $applicationSql = "INSERT INTO pass_applications (visitor_id, pass_type, pass_fees, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, upload_clearance, document_number, issue_date, contract_id, other_contract, department_id, areaOfVisit, apply_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($applicationSql);
    $stmt->bind_param("issssssssssisss", $visitor_id, $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $uploadClearanceData, $documentNumber, $issueDate, $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime);

    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;

        // Insert approval level data
        $approvalSql = "INSERT INTO approval_level (application_id, contractor_id, contractor_approve_time, manager_id, manager_approve_time, incharge_id, incharge_approve_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtApproval = $con->prepare($approvalSql);

        // Set default values for IDs and timestamps
        $contractorId = 0;
        $contractorApproveTime = null;
        $managerId = 0;
        $managerApproveTime = null;
        $inchargeId = 0;
        $inchargeApproveTime = null;

        if ($contractId == 'other') {
            $stmtApproval->bind_param("iisisis", $application_id, $contractorId, $contractorApproveTime, $managerId, $managerApproveTime, $inchargeId, $inchargeApproveTime);
        } else {
            $stmtApproval->bind_param("iisisis", $application_id, $contractorId, $contractorApproveTime, $managerId, $managerApproveTime, $inchargeId, $inchargeApproveTime);
        }

        if ($stmtApproval->execute()) {
            $_SESSION['message'] = "Pass application submitted successfully<br> Your Application ID : " . $application_id;
            header('Location: index.php');
        } else {
            echo "Error: " . $stmtApproval->error;
        }
        $stmtApproval->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $con->close();
}

if(isset($_POST['renewPass'])){
    $visitor_id = $_POST['visitor_id'];
    $passType = "renew";
    $passFees = "fees";

    $purposeOfVisit = $_POST['purposeOfVisit'];
    $fromTimestamp = $_POST['fromTimestamp'];
    $toTimestamp = $_POST['toTimestamp'];
    $departmentId = $_POST['department'];
    $contractId = $_POST['contract'];
    $otherContract = isset($_POST['other_contract']) ? $_POST['other_contract'] : 'none';
    $areaOfVisit = implode(",", $_POST['areaOfVisit']);
    date_default_timezone_set("Asia/Kolkata");
    $applyTime = date('Y-m-d H:i:s');
    $policeClearance = $_POST['policeClearance'];
    
    // Handle file upload for police clearance if applicable
    $uploadClearanceData = null;
    $documentNumber = null;
    $issueDate = null;

    if ($policeClearance == "yes") {
        $documentNumber = $_POST['documentNumber'];
        $issueDate = $_POST['issueDate'];
        $uploadClearance = $_FILES['uploadClearance']['tmp_name'];
        $file_mime_type = mime_content_type($uploadClearance);
        $uploadClearance_data = file_get_contents($uploadClearance);
        $uploadClearancebase64 = base64_encode($uploadClearance_data);
        $uploadClearanceData = "data:$file_mime_type;base64,$uploadClearancebase64";
    }

    // Insert pass application data into pass_applications table
    $applicationSql = "INSERT INTO pass_applications (visitor_id, pass_type, pass_fees, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, upload_clearance, document_number, issue_date, contract_id, other_contract, department_id, areaOfVisit, apply_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($applicationSql);
    $stmt->bind_param("issssssssssisss", $visitor_id, $passType, $passFees, $purposeOfVisit, $fromTimestamp, $toTimestamp, $policeClearance, $uploadClearanceData, $documentNumber, $issueDate, $contractId, $otherContract, $departmentId, $areaOfVisit, $applyTime);

    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;

        // Insert approval level data
        $approvalSql = "INSERT INTO approval_level (application_id, contractor_id, contractor_approve_time, manager_id, manager_approve_time, incharge_id, incharge_approve_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtApproval = $con->prepare($approvalSql);

        // Set default values for IDs and timestamps
        $contractorId = 0;
        $contractorApproveTime = null;
        $managerId = 0;
        $managerApproveTime = null;
        $inchargeId = 0;
        $inchargeApproveTime = null;

        if ($contractId == 'other') {
            $stmtApproval->bind_param("iisisis", $application_id, $contractorId, $contractorApproveTime, $managerId, $managerApproveTime, $inchargeId, $inchargeApproveTime);
        } else {
            $stmtApproval->bind_param("iisisis", $application_id, $contractorId, $contractorApproveTime, $managerId, $managerApproveTime, $inchargeId, $inchargeApproveTime);
        }

        if ($stmtApproval->execute()) {
            $_SESSION['message'] = "Pass application submitted successfully<br> Your Application ID : " . $application_id;
            header('Location: index.php');
        } else {
            echo "Error: " . $stmtApproval->error;
        }
        $stmtApproval->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $con->close();
}
?>
