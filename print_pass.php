<?php

require('Connection.php');

// Fetch the application_id from the URL
$application_id = $_GET['id'];

// Check the pass status
$checkPassStatusQuery = "SELECT incharge_id, incharge_approve_time FROM approval_level WHERE application_id = ?";
$stmt = $con->prepare($checkPassStatusQuery);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$incharge = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (isset($incharge['incharge_id']) && $incharge['incharge_id']) {
    // Fetch the details of the application and visitor
    $query = "
        SELECT 
            pa.application_id, 
            vd.name, 
            vd.designation, 
            vd.phone, 
            vd.identity, 
            vd.address, 
            pa.purpose_of_visit, 
            pa.from_timestamp, 
            pa.to_timestamp, 
            pa.police_clearance, 
            pa.document_number, 
            pa.issue_date, 
            pa.contract_id, 
            pa.department_id, 
            pa.areaOfVisit, 
            pa.apply_time 
        FROM 
            pass_applications pa
        JOIN 
            visitor_data vd ON pa.visitor_id = vd.id 
        WHERE 
            pa.application_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Fetch the contract name
    $getContractQuery = "SELECT contract_name FROM contracts WHERE contract_id = ?";
    $stmt = $con->prepare($getContractQuery);
    $stmt->bind_param("i", $row['contract_id']);
    $stmt->execute();
    $contract = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch the department name
    $getDeptQuery = "SELECT department_name FROM departments WHERE department_id = ?";
    $stmt = $con->prepare($getDeptQuery);
    $stmt->bind_param("i", $row['department_id']);
    $stmt->execute();
    $department = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch the area names
    $areaCodes = [];
    $areaOfVisitArray = explode(",", $row["areaOfVisit"]);
    foreach ($areaOfVisitArray as $areaId) {
        $getAreaQuery = "SELECT area_code FROM areas WHERE area_id = ?";
        $stmt = $con->prepare($getAreaQuery);
        $stmt->bind_param("i", $areaId);
        $stmt->execute();
        $area = $stmt->get_result()->fetch_assoc();
        $areaCodes[] = $area["area_code"];
        $stmt->close();
    }

    date_default_timezone_set("Asia/Kolkata");
    $currentDate = date('d/M/Y');
    $currentTime = date('H:i');

    // Assuming $incharge['incharge_approve_time'] is in the format "Y-m-d H:i:s"
    $dateTime = $incharge['incharge_approve_time'];

    // Extract date in Y-m-d format
    $issueDate = date('d/M/Y', strtotime($dateTime));

    // Extract time in H:i format
    $issueTime = date('H:i', strtotime($dateTime));

    // Calculate the validity of days
    $date1 = new DateTime($row["from_timestamp"]);
    $date2 = new DateTime($row["to_timestamp"]);
    $validityDays = $date2->diff($date1)->format("%a");

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pass</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }
        .table-borderless > tbody > tr > td,
        .table-borderless > tbody > tr > th,
        .table-borderless > tfoot > tr > td,
        .table-borderless > tfoot > tr > th,
        .table-borderless > thead > tr > td,
        .table-borderless > thead > tr > th {
            border: none;
        }
        .no-print {
            display: none !important;
        }
        </style>
    </head>
    <body>
    <div class="p-4">
        <table class="table">
            <tr>
                <th class="my-auto"><td><h2>नागर विमानन सुरक्षा ब्यूरो</h2></td></th>
                <th class="text-center">
                    <td class="text-center"><img src="<?php echo BASE_URL; ?>assets/images/aai_logo.png" alt="Logo" width="40" height="50"/></td>
                    <td class="lh-3 text-center">GOVERNMENT OF INDIA <br>
                    <span class="fs-5">BUREAU OF CIVIL AVIATION SECURITY</span> <br>
                    दैनिक परमिट / DAILY PERMIT</td>
                </th>
            </tr>
        </table>

        <div class="row">
            <div class="col-sm-6">
                PassNo : <?php echo $row["application_id"]; ?>
            </div>
            <div class="col-sm-6 text-end">
                Pass Issue Time :  <?php echo $issueDate .' '. $issueTime; ?><br>  
                Pass Printed Time : <?php echo $currentDate; ?> <?php echo $currentTime; ?>                    
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8">
                <table class="table table-borderless lh-2">
                    <tbody>
                        <tr>
                            <th>Valid Airport</th>
                            <td>Pune</td>
                        </tr>                            
                        <tr>
                            <th>Name</th>
                            <td><?php echo $row["name"]; ?></td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td><?php echo $row["designation"]; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo $row["address"]; ?></td>
                        </tr>
                        <tr>
                            <th>Validity of Days</th>
                            <td><?php echo $validityDays <= 1 ? '1 Day' : $validityDays . ' Days'; ?></td>
                        </tr>
                        
                        <tr>
                            <th>From</th>
                            <td><?php echo $row["from_timestamp"]; ?></td>
                        </tr>
                        <tr>
                            <th>To</th>
                            <td><?php echo $row["to_timestamp"]; ?></td>
                        </tr>  
                        <tr>
                            <th>Purpose</th>
                            <td><?php echo $row["purpose_of_visit"]; ?></td>
                        </tr>                          
                        <tr>
                            <th>Area of Visit</th>
                            <td>
                                <?php 
                                foreach ($areaCodes as $areaCode) {
                                    echo $areaCode . " | ";
                                }
                                ?>                        
                            </td>
                        </tr>
                        <tr>
                            <th>Esct By</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Id Proof</th>
                            <td>
                                <?php echo $row["identity"]; ?>
                                <th>ContactNo</th>
                                <td><?php echo $row["phone"]; ?></td>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4">
                <div class="no-print">
                    <label for="softCopyToggle">I have a softcopy</label>
                    <input type="checkbox" id="softCopyToggle" onchange="toggleSoftCopy()">
                </div>
                <div id="cameraSection">
                    <div class="video-wrap">
                        <video id="video" playsinline="" autoplay="" style="width: 120px; height: 140px;"></video>
                    </div>
                </div>
                <div id="uploadSection" style="display:none;">
                    <input type="file" class="no-print" id="imageUpload" accept="image/*">
                    <img id="uploadedImage" src="#" alt="Uploaded Image Preview" style="display:none; width: 120px; height: 140px;">
                </div>
            </div>
        </div>

        <div class="row text-center fw-bold mt-5 pt-5">
            <div class="col">
                <p>Holder's Signature</p>
            </div>
           
            <div class="col">
                <p>Signature Of Authority</p>
            </div>
        </div>
    </div>

    <script>            
        const video = document.getElementById('video');
        const errorMsgElement = document.querySelector('span#errorMsg');
        const cameraSection = document.getElementById('cameraSection');
        const softCopyToggle = document.getElementById('softCopyToggle');
        const uploadSection = document.getElementById('uploadSection');
        const imageUpload = document.getElementById('imageUpload');
        const uploadedImage = document.getElementById('uploadedImage');

        const constraints = {
            audio: false,
            video: {
                width: 300,
                height: 320
            }
        };

        // Access webcam
        async function init() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                handleSuccess(stream);
            } catch (e) {
                errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
            }
        }

        // Success
        function handleSuccess(stream) {
            window.stream = stream;
            video.srcObject = stream;
        }

        // Load init
        init();

        // Handle image upload and preview
        imageUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImage.src = e.target.result;
                    uploadedImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Toggle camera section visibility
        function toggleSoftCopy() {
            if (softCopyToggle.checked) {
                cameraSection.style.display = 'none';
                uploadSection.style.display = 'block';
            } else {
                cameraSection.style.display = 'block';
                uploadSection.style.display = 'none';
            }
        }
    </script>
    </body>
    </html>
        <?php
    }else{
        echo "<script>alert('Not Approved Yet'); window.location.href = 'index.php'</script>";
    }
$con->close();
?>
