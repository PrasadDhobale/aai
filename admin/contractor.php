<?php
session_start();
require('AdminNavbar.php');

?>

<div class="container shadow rounded-4 p-3">
        <h2 class="mt-5 mb-3 text-center">Contractor Registration</h2>
        <form id="contractorForm">
            <div class="row">
               
                <div class="mb-3 col row">
                    <label for="contractorName" class="col-sm-4 col-form-label"><b>Contractor Name</b></label>
                    <div class="col-sm-6">
                    <input type="hidden" name="contractorId" id="contractorId">
                        <input type="text" class="form-control" placeholder="Enter Contractor name" id="contractorName"
                            name="contractorName" required>
                    </div>
                </div>
                <div class="mb-3 row col">
                    <label for="contract" class="col-sm-4 col-form-label"><b>Contract</b></label>
                    <div class="col-sm-6">
                        <select class="form-select" id="contract" name="contract">
                            <option value="" selected>Select Contract</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 row col">
                    <label for="email" class="col-sm-4 col-form-label"><b>Email</b></label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" placeholder="Enter email" id="email" name="email"
                            required>
                    </div>
                </div>
                <div class="mb-3 row col">
                    <label for="password" class="col-sm-4 col-form-label"><b>Password</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Enter password" id="password"
                            name="password" required>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" id="btn" class="update-btn btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-5">
    <h2 class="mb-3">Registered Contractors</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>contract</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="contractorTableBody">
            <!-- contractor details will be dynamically populated here -->
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    
    $('#contractorForm').submit(function (event) {
        event.preventDefault();

        // Get form data
        var formData = $(this).serialize();
        
        // Check if the button text is 'Register'
        if ($('#btn').text().trim() === 'Register') {
            // Submit form data via AJAX for registration
            $.ajax({
                url: 'controller/contractorController.php', // Change the URL to your PHP script that handles form submission
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert('contractor registered successfully!');
                        // Clear form fields
                        $('#contractorForm').trigger('reset');
                        fetchContractors();
                    } else {
                        alert('Failed to register contractor: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Something went wrong, try again.');
                }
            });
        } else {
            // Prompt confirmation for update
            var confirmation = confirm('Are you sure you want to update this details?');
            if (confirmation) {
                // Submit form data via AJAX for update
                $.ajax({
                    url: 'controller/contractorController.php', // Change the URL to your PHP script that handles form submission
                    method: 'PUT',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert('contractor updated successfully!');
                            // Clear form fields
                            $('#contractorForm').trigger('reset');
                            fetchContractors();
                        } else {
                            alert('Failed to update contractor: ' + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
                $('#btn').text('Register');
            }        
        }
    });





    
    // Function to fetch contractors from server
    function fetchContractors() {
        $.ajax({
            url: 'controller/contractorController.php?fetch=true',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    displaycontractors(response.contractors);
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Function to display contractors in table
    function displaycontractors(contractors) {
        $('#contractorTableBody').empty();
        contractors.forEach(function (contractor, index) {

            var row = `<tr>
                            <td>${index + 1}</td>
                            <td>${contractor.contractor_name}</td>
                            <td>${contractor.email}</td>
                            <td>${contractor.contract_name}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-btn" data-contractor-id="${contractor.contractor_id}" data-contractor-name="${contractor.contractor_name}" data-phone="${contractor.phone}" data-email="${contractor.email}" data-contract="${contractor.contract_id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-contractor-id="${contractor.contractor_id}">Delete</button>
                            </td>
                        </tr>`;
            $('#contractorTableBody').append(row);
        });
    }

    
    $(document).ready(function () {

        // Fetch contracts dynamically
        $.ajax({
            url: 'controller/contractController.php?fetchCont=true',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    response.contracts.forEach(function (contract) {
                        $('#contract').append($('<option>', {
                            value: contract.contract_id,
                            text: contract.contract_name
                        }));
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        // Fetch contractors on page load
        fetchContractors();

        

        // Edit contractor button click event
        $(document).on('click', '.edit-btn', function () {
            var contractorId = $(this).data('contractor-id');
            var contractorName = $(this).data('contractor-name');
            var email = $(this).data('email');
            var contract = $(this).data('contract');

            // Populate form fields with contractor data
            $('#contractorId').val(contractorId);
            $('#contractorName').val(contractorName);
            $('#email').val(email);
            $('#password').val('*********');
            $('#password').prop('readonly', true);
            $('#contract').val(contract);
            $('#btn').text('Update');
            
        });

        // Delete contractor button click event
        $(document).on('click', '.delete-btn', function () {
            var contractorId = $(this).data('contractor-id');
            var confirmation = confirm('Are you sure you want to delete this contractor?');
            if (confirmation) {
                $.ajax({
                    url: 'controller/contractorController.php?contractorId=' + contractorId,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            fetchContractors(); // Refresh contractor list
                            alert('contractor deleted successfully!');
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

</script>