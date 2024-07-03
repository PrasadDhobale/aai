<?php
    session_start();

    include '../navbar.php';
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
        <h2 class="mb-4 text-center">Pass Application Form</h2>
        <form action="passController.php" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-sm-6 row">
                    <label for="passType" class="col-4 form-label"><b>Pass Type</b></label><br>
                    <div class="col-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="passType" id="passTypeNew" value="new" required checked>
                            <label class="form-check-label" for="passTypeNew">New</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="passType" id="passTypeRenew" value="renew" required>
                            <label class="form-check-label" for="passTypeRenew">Renew</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 row">
                    <label for="passFees" class="col-4 form-label"><b>Pass Fees</b></label><br>
                    <div class="col-8">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="passFees" id="passFeesFees" value="fees" required checked>
                            <label class="form-check-label" for="passFeesFees">Fees</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="passFees" id="passFeesNoFees" value="noFees" required>
                            <label class="form-check-label" for="passFeesNoFees">No Fees</label>
                        </div>
                    </div>
                </div>
            </div>        
            <div class="row">
                <div class="mb-3 col-sm-6 row">
                    <label for="name" class="col-4 form-label"><b>Name</b></label>
                    <div class="col-6">
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 row">
                    <label for="sdw" class="col-4 form-label"><b>S/D/W Of</b></label>
                    <div class="col-6">
                        <input type="text" class="form-control" id="sdw" name="sdw" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-6 row">
                    <label for="designation" class="col-sm-4 form-label"><b>Designation</b></label>
                    <div class="col-sm-6">
                        <input type="text" placeholder="Employee" class="form-control" id="designation" name="designation" required>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 row">
                    <label for="phone" class="col-sm-4 form-label"><b>Phone</b></label>
                    <div class="col-sm-6">
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-6 row">
                    <label for="address" class="col-sm-4 form-label"><b>Address</b></label>
                    <div class="col-sm-6">
                        <textarea placeholder="Enter your address" class="form-control" id="address" name="address" rows="2" required></textarea>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 row">
                    <label for="companyID" class="col-sm-4 form-label"><b>Company ID</b></label>
                    <div class="col-sm-6">
                        <input type="text" placeholder="Enter company id" class="form-control" id="companyID" name="companyID">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-6 row">
                    <label for="identity" class="col-sm-4 form-label"><b>Identity</b></label>
                    <div class="col-sm-6">
                        <select class="form-select" id="identity" name="identity" required>
                            <option value="Aadhaar Card">Aadhaar Card</option>
                            <option value="Pan Card">Pan Card</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Election Card">Election Card</option>
                            <option value="Passport">Passport</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 row">
                    <label for="uploadId" class="col-sm-4 form-label"><b>Upload Id</b></label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="uploadId" name="uploadId" accept="image/*,.pdf" required>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="purposeOfVisit" class="col-sm-2 form-label"><b>Purpose of Visit</b></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="Enter your purpose of visit" id="purposeOfVisit" name="purposeOfVisit" required>
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
            <div class="mb-3 border-top pt-2">
                <label for="areaOfVisit" class="form-label"><b>Area of Visit</b></label><br>            
                <div id="areaContainer" class="m-1 mb-3 row"></div>
            </div>
            <div class="text-center">
                <button type="submit" name="requestPass" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renewModalLabel">Enter Phone Number for Renewal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="renewPhone">Phone Number</label>
                        <input type="text" class="form-control" id="renewPhone" name="renewPhone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePhone">Renew</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>



        $(document).ready(function() {
            $('input[type=radio][name=passType]').change(function() {
                if (this.value == 'renew') {
                    $('#renewModal').modal('show');
                }
            });

            $('#savePhone').click(function() {
                var phone = $('#renewPhone').val();
                if (phone) {
                    // Redirect to renew.php with the phone number as a query parameter
                    window.location.href = 'renew.php?phone=' + phone;
                } else {
                    alert('Please enter a phone number');
                }
            });

            $('#renewModal').on('hidden.bs.modal', function () {
                location.reload();
            });
        });

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