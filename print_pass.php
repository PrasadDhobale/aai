<?php

require('Connection.php');
// Fetch the application_id from the URL
$application_id = $_GET['id'];

$checkPassStatusQuery = "select incharge_id from approval_level where application_id = $application_id";
$incharge = $con->query($checkPassStatusQuery)->fetch_assoc();
if(isset($incharge['incharge_id'])){
    if($incharge['incharge_id']){
        // Fetch the details of the application
        $query = "SELECT application_id, pass_type, pass_fees, name, sdw, address, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, document_number, issue_date, contract_id, department_id, areaOfVisit, apply_time FROM pass_applications WHERE application_id = $application_id";
        $result = $con->query($query);
        $row = $result->fetch_assoc();

        // Fetch the contract name
        $getContractQuery = "SELECT contract_name FROM contracts WHERE contract_id = ".$row['contract_id'];
        $contract = $con->query($getContractQuery)->fetch_assoc();

        // Fetch the department name
        $getDeptQuery = "SELECT department_name FROM departments WHERE department_id = ".$row['department_id'];
        $department = $con->query($getDeptQuery)->fetch_assoc();

        // Fetch the area names
        $areaNames = [];
        $areaOfVisitArray = explode(",", $row["areaOfVisit"]);
        foreach ($areaOfVisitArray as $areaId) {
            $getAreaQuery = "SELECT area_code FROM Areas WHERE area_id = $areaId";
            $area = $con->query($getAreaQuery)->fetch_assoc();
            $areaCodes[] = $area["area_code"];
        }

        date_default_timezone_set("Asia/Kolkata");
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Pass</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        </head>
        <body>
        <div class="p-4">
            <h2 class="p-4 text-center">Airport Authority Of India</h2>

            <div class="row">
                <div class="col-sm-8">
                    <table class="table table-light table-striped-columns">
                        <tr>
                            <th>Pass No</th>
                            <td><?php echo $row["application_id"]; ?></td>                    
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo $currentDate; ?></td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td><?php echo $currentTime; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-4">
                    <div class="video-wrap">
                        <video id="video" playsinline="" autoplay="" style="width: 120px; height: 140px;"></video>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Valid Airport</th>
                        <td>Pune</td>
                    </tr>
                    <tr>
                        <th>Pass</th>
                        <td><?php echo $row["pass_type"]." | ".$row["pass_fees"]; ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?php echo $row["name"]; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $row["address"]; ?></td>
                    </tr>
                    <tr>
                        <th>S/D/W of</th>
                        <td><?php echo $row["sdw"]; ?></td>
                    </tr>
                    <tr>
                        <th>Purpose</th>
                        <td><?php echo $row["purpose_of_visit"]; ?></td>
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
                        <th>Police Clearance</th>
                        <td><?php echo $row['police_clearance'] === "yes" ? "Document No: " . $row["document_number"] . "<br>Issue Date: " . $row["issue_date"] : "No"; ?></td>
                    </tr>
                    <tr>
                        <th>Esct By</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Contract</th>
                        <td><?php echo $contract["contract_name"]; ?></td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td><?php echo $department["department_name"]; ?></td>
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
                        <th>Apply Time</th>
                        <td><?php echo $row["apply_time"]; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="row text-center fw-bold mt-5 pt-5">
                <div class="col">
                    <p>Holder's Signature</p>
                </div>
                <div class="col">
                    <p>Signature Of HOD</p>
                </div>
                <div class="col">
                    <p>Signature Of Authority</p>
                </div>
            </div>
        </div>

            <script>
                
            'use strict';

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const snap = document.getElementById("snap");
            const errorMsgElement = document.querySelector('span#errorMsg');

            const constraints = {
                audio: false,
                video: {
                    width: 300 , height: 320
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

            </script>
        </body>
        </html>
        <?php
    }else{
        echo "<script>alert('Not Approved Yet'); window.location.href = 'index.php'</script>";
    }
}else{
    echo "<script>alert('Application ID Not Found'); window.location.href = 'index.php'</script>";
}
$con->close();
?>
