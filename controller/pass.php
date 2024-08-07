<div class="mt-5">
    <h2 class="text-center"><?php echo ucfirst($role); ?></h2>
    <?php
    include '../controller/fetch_applications.php';
    ?>
</div>

<!-- uploaded ID -->
<div class="modal fade" id="idModal" tabindex="-1" aria-labelledby="idModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="idModalLabel">Uploaded ID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="idImage" src="#" alt="Uploaded ID" style="max-width: 100%;">
                <embed id="idImagePDF" src="#" type="application/pdf" style="width: 100%; height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- uploaded police clearance -->
<div class="modal fade" id="clearanceModal" tabindex="-1" aria-labelledby="clearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearanceModalLabel">Uploaded Police Clearance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="clearanceImg" src="#" alt="Clearance PDF" style="max-width: 100%;">
                <embed id="clearancePDF" src="#" type="application/pdf" style="width: 100%; height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- uploaded police clearance -->
<div class="modal fade" id="letterModal" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="letterModalLabel">Uploaded Appointment Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="letterImg" src="#" alt="Letter PDF" style="max-width: 100%;">
                <embed id="letterPDF" src="#" type="application/pdf" style="width: 100%; height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- applicant details -->
<div class="modal fade" id="applicantModal" tabindex="-1" aria-labelledby="applicantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicantModalLabel">Applicant Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="applicantDetails">
                <!-- Applicant details will be displayed here -->
            </div>
        </div>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label for="fromTimestamp">From:</label>
                        <input type="hidden" name="application_id" id="editApplicationId" />
                        <input type="datetime-local" class="form-control" id="fromTimestamp" name="fromTimestamp">
                    </div>
                    <div class="form-group">
                        <label for="toTimestamp">To:</label>
                        <input type="datetime-local" class="form-control" id="toTimestamp" name="toTimestamp">
                    </div>
                    <div class="form-group">
                        <label>Area of Visit:</label><br>
                        
                        <?php
                        // Fetch areas from the database
                        $getAreasQuery = "SELECT area_id, area_name FROM areas";
                        $areasResult = $con->query($getAreasQuery);

                        // Check if there are any areas
                        if ($areasResult && $areasResult->num_rows > 0) {
                            // Loop through each row in the result set
                            while ($area = $areasResult->fetch_assoc()) {
                                // Output the checkbox for each area
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='checkbox' id='areaCheckbox{$area['area_id']}' name='areas[]' value='{$area['area_id']}'>";
                                echo "<label class='form-check-label' for='areaCheckbox{$area['area_id']}'>{$area['area_name']}</label>";
                                echo "</div>";
                            }
                        } else {
                            // No areas found
                            echo "No areas found";
                        }
                        ?>

                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- approve confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this pass?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmApproveBtn">Yes, Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="form-group">
                        <label for="rejectReason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejectReason" name="rejectReason" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="rejectApplicationId" name="application_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>



<script>
// JavaScript to handle modal functionality
$(document).ready(function(){
    // AJAX for View Image button click
    $('.view-identity').click(function() {
        var applicationId = $(this).data('id');
        $.ajax({
            url: '../controller/get_uploaded_files.php',
            type: 'post',
            data: { application_id: applicationId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.uploadIdData) {
                        if (isPdf(response.uploadIdData)) {
                            $('#idImagePDF').attr('src', response.uploadIdData).show();
                            $('#idImage').hide();
                        } else {
                            $('#idImage').attr('src', response.uploadIdData).show();
                            $('#idImagePDF').hide();
                        }
                        $('#idModal').modal('show'); // Show the ID modal
                    } else {
                        $('#idImage, #idImagePDF').hide();
                    }
                } else {
                    alert('Error fetching files');
                }
            },
            error: function() {
                alert('Error fetching files');
            }
        });
    });

    // AJAX for View Clearance button click
    $('.view-clearance').click(function() {
        var applicationId = $(this).data('id');
        $.ajax({
            url: '../controller/get_uploaded_files.php',
            type: 'post',
            data: { application_id: applicationId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.uploadClearanceData) {
                        if (isPdf(response.uploadClearanceData)) {
                            $('#clearancePDF').attr('src', response.uploadClearanceData).show();
                            $('#clearanceImg').hide();
                        } else {
                            $('#clearanceImg').attr('src', response.uploadClearanceData).show();
                            $('#clearancePDF').hide();
                        }
                        $('#clearanceModal').modal('show'); // Show the clearance modal
                    } else {
                        $('#clearanceImg, #clearancePDF').hide();
                    }
                } else {
                    alert('Error fetching files');
                }
            },
            error: function() {
                alert('Error fetching files');
            }
        });
    });


     // AJAX for View Letter button click
     $('.view-letter').click(function() {
        var applicationId = $(this).data('id');
        $.ajax({
            url: '../controller/get_uploaded_files.php',
            type: 'post',
            data: { application_id: applicationId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.uploadAppointmentData) {
                        if (isPdf(response.uploadAppointmentData)) {
                            $('#letterPDF').attr('src', response.uploadAppointmentData).show();
                            $('#letterImg').hide();
                        } else {
                            $('#letterImg').attr('src', response.uploadAppointmentData).show();
                            $('#letterPDF').hide();
                        }
                        $('#letterModal').modal('show'); // Show the Letter modal
                    } else {
                        $('#letterImg, #letterPDF').hide();
                    }
                } else {
                    alert('Error fetching files');
                }
            },
            error: function() {
                alert('Error fetching files');
            }
        });
    });

    // AJAX for View applicant details button click
    $('.applicant-details').click(function(){
        var applicationId = $(this).data('id');
        $.ajax({
            url: '../controller/get_applicant_details.php',
            type: 'post',
            data: {application_id: applicationId},
            success: function(response){
                $('#applicantDetails').html(response);
                $('#applicantModal').modal('show');
            },
            error: function(){
                alert('Error fetching applicant details');
            }
        });
    });

    // AJAX for edit application button click
    $('.edit-application').click(function() {
        var applicationId = $(this).closest('tr').find('.applicant-details').data('id');
        // Fetch existing details using AJAX and populate the form fields
        $.ajax({
            url: '../controller/getEditDetails.php',
            type: 'post',
            data: {application_id: applicationId},
            dataType: 'json',
            success: function(response) {
                // Populate form fields with existing details
                $('#fromTimestamp').val(response.fromTimestamp);
                $('#toTimestamp').val(response.toTimestamp);
                
                // Uncheck all checkboxes first
                $('input[type="checkbox"]').prop('checked', false);

                // Check checkboxes for areas of visit
                response.areaOfVisit.forEach(function(areaId) {
                    $('#areaCheckbox' + areaId).prop('checked', true);
                });

                // Open the modal
                $('#editApplicationId').val(applicationId);
                $('#editModal').modal('show');                    
            },
            error: function() {
                alert('Error fetching details');
            }
        });
    });

    // Handle edit application form submission
    $('#editForm').submit(function(event) {
        event.preventDefault();
        // Get form data
        var formData = $(this).serialize();
        // Send form data using AJAX for processing
        $.ajax({
            url: '../controller/update_details.php',
            type: 'post',
            data: formData,
            success: function(response) {
                // Handle success response
                alert('Updated Successfully')
                // Close the modal
                $('#editModal').modal('hide');
                window.location.reload();
            },
            error: function() {
                // Handle error
                alert('Error updating details');
            }
        });
    });


    var applicationId; // Define applicationId variable outside event handlers

    // When the approve button is clicked, show the confirmation modal
    $('.approve-btn').click(function() {
        applicationId = $(this).closest('tr').find('.applicant-details').data('id');
        $('#confirmationModal').modal('show');
    });

    // When the confirm approve button is clicked
    $('#confirmApproveBtn').click(function() {
        // Check if applicationId is defined
        if (typeof applicationId !== 'undefined') {
            // Perform AJAX request to update approval status
            $.ajax({
                url: '../controller/update_approval.php',
                type: 'post',
                data: { 
                    application_id: applicationId, 
                    role: '<?php echo $_SESSION['role']; ?>', 
                    userId: '<?php echo $userId; ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Pass approved successfully');
                        // Optionally, you can reload the page or update the UI
                        $('#confirmationModal').modal('hide');
                        window.location.reload();
                    } else {
                        alert('Failed to approve pass');
                    }
                },
                error: function() {
                    alert('Error approving pass');
                }
            });
        } else {
            alert('Application ID is not defined');
        }
    });

    // Open the reject modal and set the application ID
    $('.reject-btn').click(function() {
        var applicationId = $(this).data('id');
        $('#rejectApplicationId').val(applicationId);
        $('#rejectModal').modal('show');
    });

    // When the confirm reject button is clicked
    $('#confirmRejectBtn').click(function() {
        var applicationId = $('#rejectApplicationId').val();
        var rejectReason = $('#rejectReason').val();

        if (typeof applicationId !== 'undefined') {
            if (rejectReason.trim() === '') {
                alert('Please provide a reason for rejection.');
                return;
            }

            // Perform AJAX request to update rejection status
            $.ajax({
                url: '../controller/update_rejection.php',
                type: 'post',
                data: { 
                    application_id: applicationId,
                    reject_reason: rejectReason,
                    role: '<?php echo $_SESSION['role']; ?>',
                    userId: '<?php echo $userId; ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Application rejected successfully');
                        $('#rejectModal').modal('hide');
                        window.location.reload();
                    } else {
                        alert('Failed to reject application');
                    }
                },
                error: function() {
                    alert('Error rejecting application');
                }
            });
        } else {
            alert('Application ID is not defined');
        }
    });
});

// Function to check if the data represents a PDF
function isPdf(data){
    return data.startsWith('data:application/pdf');
}

</script>
