<?php
    session_start();

    include '../navbar.php';
    $getVisitorIdQuery = "select * from visitor_data where adhaar_no = ".$_GET['adhaar'];
    $visitor = $con->query($getVisitorIdQuery)->fetch_assoc();
    if(!$visitor){
        echo "<script>alert('adhaar number is not exist. try again..'); window.location.href='index.php';</script>";
    }else{
        $isPrevPassApprovedQuery = "select incharge_id from approval_level where application_id = (select application_id from pass_applications where visitor_id = ".$visitor['id']." order by apply_time desc limit 1)";
        $isPrevPassApproved = $con->query($isPrevPassApprovedQuery)->fetch_assoc();
        
        if($isPrevPassApproved['incharge_id'] == 0)
            echo "<script>alert('You cannot apply for 2 passes at a time. Please track your previous application.'); window.location.href='index.php';</script>";
    }

    ?>
      <div class="container shadow border-primary rounded-5 p-3 mt-5">
        <?php

        if(isset($_SESSION['message'])){
            ?>            
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php echo $_SESSION['message']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>            
            <?php
        }
        ?>

        <h2 class="mb-4 text-center">Pass Renew Form</h2>
        <h4 class="m-2 text-center">Welcome Back <?php echo $visitor['name']; ?> !!</h4>
            <table class="table table-responsive">
                <tr>
                    <th>Name</th>
                    <td><?php echo $visitor['name']; ?></td>
                </tr>
                <tr>
                    <th>SDW Of</th>
                    <td><?php echo $visitor['sdw'];?></td>
                </tr>
                <tr>
                    <th>Designation</th>
                    <td><?php echo $visitor['designation']; ?></td>
                </tr>
                    <th>Phone</th>
                    <td><?php echo $visitor['phone']; ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $visitor['address']; ?></td>
                </tr>

            </table>

            <form action="passController.php" method="post" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="purposeOfVisit" class="col-sm-2 form-label"><b>Purpose of Visit</b></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Enter your purpose of visit" id="purposeOfVisit" name="purposeOfVisit" required>
                        <input type="hidden" name="visitor_id" value="<?php echo $visitor['id']; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-sm-6 row">
                        <label for="fromTimestamp" class="col-sm-4 form-label"><b>From</b></label>
                        <div class="col-sm-6">
                            <input type="datetime-local" class="form-control" id="fromTimestamp" name="fromTimestamp" required>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6 row">
                        <label for="toTimestamp" class="col-sm-4 form-label"><b>To</b></label>
                        <div class="col-sm-6">
                            <input type="datetime-local" class="form-control" id="toTimestamp" name="toTimestamp" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                    // Query to fetch departments from the database
                    $sql = "SELECT department_id, department_name FROM departments where department_id NOT IN(0, 1000, 1001, 1002, 1003)";
                    $result = $con->query($sql);

                    // Check if there are any departments in the database
                    if ($result->num_rows > 0) {
                        echo '<div class="mb-3 col-sm-6 row">';
                        echo '<label for="department" class="col-sm-4 form-label"><b>Department</b></label>';
                        echo '<div class="col-sm-6">';
                        echo '<select class="form-select" id="department" name="department" required>';
                        echo '<option selected>Select Department</option>';

                        // Loop through each row in the result set
                        while ($row = $result->fetch_assoc()) {
                            // Output an option element for each department
                            echo '<option value="' . $row["department_id"] . '">' . $row["department_name"] . '</option>';
                        }

                        echo '</select>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        // If no departments are found in the database
                        echo '<div class="alert alert-warning" role="alert">No departments found.</div>';
                    }
                    
                    // Query to fetch departments from the database
                    $sql = "SELECT contract_id, contract_name FROM contracts where contract_id not in (0)";
                    $result = $con->query($sql);

                    // Check if there are any departments in the database
                    if ($result->num_rows > 0) {
                        echo '<div class="mb-3 col-sm-6 row">';
                        echo '<label for="contract" class="col-sm-4 form-label"><b>Contract</b></label>';
                        echo '<div class="col-sm-6">';
                        echo '<select class="form-select" id="contract" name="contract" required onchange="toggleOtherInput()">';
                        echo '<option selected>Select Contract</option>';

                        // Loop through each row in the result set
                        while ($row = $result->fetch_assoc()) {
                            // Output an option element for each contract
                            echo '<option value="' . $row["contract_id"] . '">' . $row["contract_name"] . '</option>';
                        }

                        // Add the "Other" option
                        echo '<option value="other">Other</option>';

                        echo '</select>';
                        echo '</div>';
                        echo '</div>';

                        // Add the hidden input text box for "Other"
                        echo '<div class="mb-3 col-sm-6 row" id="other-contract-div" style="display: none;">';
                        echo '<label for="other-contract" class="col-sm-4 form-label"><b>Other Contract</b></label>';
                        echo '<div class="col-sm-6">';
                        echo '<input type="text" class="form-control" id="other-contract" name="other_contract" placeholder="Enter contract name">';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        // If no departments are found in the database
                        echo '<div class="alert alert-warning" role="alert">No Contractors found.</div>';
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="mb-3 row col-sm-6">
                        <label for="policeClearance" class="col-sm-4 form-label"><b>Police Clearance</b></label><br>
                        <div class="col-sm-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="policeClearance" id="policeClearanceYes" value="yes" required>
                                <label class="form-check-label" for="policeClearanceYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="policeClearance" id="policeClearanceNo" value="no" required checked>
                                <label class="form-check-label" for="policeClearanceNo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6 row" id="uploadClearanceField">
                        <label for="uploadClearance" class="col-sm-4 form-label"><b>Upload Clearance</b></label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" id="uploadClearance" name="uploadClearance" accept="image/*,.pdf">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-sm-6 row" id="policeClearanceFields">
                        <label for="documentNumber" class="col-sm-4 form-label"><b>Document No</b></label><br>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="documentNumber" placeholder="document number" name="documentNumber">
                        </div>
                    </div>

                    <div class="mb-3 col-sm-6 row" id="issueDateFields">
                        <label for="issueDate" class="col-sm-4 form-label"><b>Issue Date</b></label><br>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="issueDate" name="issueDate">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 row col-sm-6">
                        <label for="appointmentLetter" class="col-sm-4 form-label"><b>Appointment Letter</b></label><br>
                        <div class="col-sm-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appointmentLetter" id="appointmentLetterYes" value="yes" required>
                                <label class="form-check-label" for="appointmentLetterYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appointmentLetter" id="appointmentLetterNo" value="no" required checked>
                                <label class="form-check-label" for="appointmentLetterNo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6 row" id="uploadAppointmentField">
                        <label for="uploadAppointment" class="col-sm-4 form-label"><b>Upload Letter</b></label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" id="uploadAppointment" name="uploadAppointment" accept="image/*,.pdf">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-sm-6 row" id="startDateField">
                        <label for="startDate" class="col-sm-4 form-label"><b>Start Date</b></label><br>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6 row" id="endDateField">
                        <label for="endDate" class="col-sm-4 form-label"><b>End Date</b></label><br>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>
                    </div>
                </div>
                <div class="mb-3 border-top pt-2">
                    <label for="areaOfVisit" class="form-label"><b>Area of Visit</b></label><br>            
                    <div id="areaContainer" class="m-1 mb-3 row"></div>
                </div>
                <div class="text-center">
                    <button type="submit" name="renewPass" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>       

        function toggleOtherInput() {
            var contractSelect = document.getElementById("contract");
            var otherContractDiv = document.getElementById("other-contract-div");
            if (contractSelect.value === "other") {
                otherContractDiv.style.display = "flex"; // Show the input text box
            } else {
                otherContractDiv.style.display = "none"; // Hide the input text box
            }
        }

        $(document).ready(function () {
            // Initially hide police clearance fields
            $("#uploadClearanceField").hide();
            $("#policeClearanceFields").hide();
            $("#issueDateFields").hide();            

            $("#uploadAppointmentField").hide();
            $("#startDateField").hide();
            $("#endDateField").hide();
            // Handle change event of police clearance radio buttons
            $("input[name='policeClearance']").change(function () {
                if (this.value === "yes") {
                    $("#uploadClearanceField").show();
                    $("#policeClearanceFields").show();
                    $("#issueDateFields").show();
                } else {
                    $("#uploadClearanceField").hide();
                    $("#policeClearanceFields").hide();
                    $("#issueDateFields").hide();
                }
            });

            // Handle change event of appointment letter radio buttons
            $("input[name='appointmentLetter']").change(function () {
                if (this.value === "yes") {
                    $("#uploadAppointmentField").show();
                    $("#startDateField").show();
                    $("#endDateField").show();
                } else {
                    $("#uploadAppointmentField").hide();
                    $("#startDateField").hide();
                    $("#endDateField").hide();
                }
            });
        });


        $(document).ready(function () {
            // Fetch areas dynamically
            $.ajax({
                url: '../admin/controller/areaController.php?fetchAreas=true',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        response.areas.forEach(function (area) {
                            // Create checkbox element
                            var checkbox = $('<div class="col-sm-4 form-check"></div>')
                                .append($('<input class="form-check-input" type="checkbox" id='+area.area_id+' name="areaOfVisit[]" value="' + area.area_id + '">'))
                                .append($('<label class="form-check-label" for='+area.area_id+'>' + area.area_name + '</label>'));

                            // Append checkbox to container
                            $('#areaContainer').append(checkbox);
                        });
                    } else {
                        console.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

    </script>
