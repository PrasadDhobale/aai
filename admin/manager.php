<?php
session_start();
require('AdminNavbar.php');

?>

<div class="container shadow rounded-4 p-3">
        <h2 class="mt-5 mb-3 text-center">Manager Registration</h2>
        <form id="managerForm">
            <div class="row">
                <div class="mb-3 col row">
                    <input type="hidden" name="managerId" id="managerId">
                    <label for="firstName" class="col-sm-4 col-form-label"><b>First Name</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Enter first name" id="firstName"
                            name="firstName" required>
                    </div>
                </div>
                <div class="mb-3 col row">
                    <label for="lastName" class="col-sm-4 col-form-label"><b>Last Name</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="Enter last name" id="lastName"
                            name="lastName" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 row col">
                    <label for="phone" class="col-sm-4 col-form-label"><b>Phone</b></label>
                    <div class="col-sm-6">
                        <input type="tel" class="form-control" id="phone" placeholder="+91 9078467233" name="phone"
                            required>
                    </div>
                </div>
                <div class="mb-3 row col">
                    <label for="department" class="col-sm-4 col-form-label"><b>Department</b></label>
                    <div class="col-sm-6">
                        <select class="form-select" id="department" name="department">
                            <option value="" selected>Select Department</option>
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
    <h2 class="mb-3">Registered Managers</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="managerTableBody">
            <!-- Manager details will be dynamically populated here -->
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    
    $('#managerForm').submit(function (event) {
        event.preventDefault();

        // Get form data
        var formData = $(this).serialize();
        
        // Check if the button text is 'Register'
        if ($('#btn').text().trim() === 'Register') {
            // Submit form data via AJAX for registration
            $.ajax({
                url: 'controller/managerController.php', // Change the URL to your PHP script that handles form submission
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert('Manager registered successfully!');
                        // Clear form fields
                        $('#managerForm').trigger('reset');
                        fetchManagers();
                    } else {
                        alert('Failed to register manager: ' + response.message);
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
                    url: 'controller/managerController.php', // Change the URL to your PHP script that handles form submission
                    method: 'PUT',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert('Manager updated successfully!');
                            // Clear form fields
                            $('#managerForm').trigger('reset');
                            fetchManagers();
                        } else {
                            alert('Failed to update manager: ' + response.message);
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





    
    // Function to fetch managers from server
    function fetchManagers() {
        $.ajax({
            url: 'controller/managerController.php?fetch=true',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    displayManagers(response.managers);
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Function to display managers in table
    function displayManagers(managers) {
        $('#managerTableBody').empty();
        managers.forEach(function (manager, index) {

            var row = `<tr>
                            <td>${index + 1}</td>
                            <td>${manager.first_name} ${manager.last_name}</td>
                            <td>${manager.phone}</td>
                            <td>${manager.email}</td>
                            <td>${manager.department_name}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-btn" data-manager-id="${manager.manager_id}" data-first-name="${manager.first_name}" data-last-name="${manager.last_name}" data-phone="${manager.phone}" data-email="${manager.email}" data-department="${manager.dept_id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-manager-id="${manager.manager_id}">Delete</button>
                            </td>
                        </tr>`;
            $('#managerTableBody').append(row);
        });
    }

    
    $(document).ready(function () {

        // Fetch departments dynamically
        $.ajax({
            url: 'controller/deptController.php?fetchDept=true',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    response.departments.forEach(function (department) {
                        $('#department').append($('<option>', {
                            value: department.department_id,
                            text: department.department_name
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

        // Fetch managers on page load
        fetchManagers();

        

        // Edit manager button click event
        $(document).on('click', '.edit-btn', function () {
            var managerId = $(this).data('manager-id');
            var firstName = $(this).data('first-name');
            var lastName = $(this).data('last-name');
            var phone = $(this).data('phone');
            var email = $(this).data('email');
            var department = $(this).data('department');

            // Populate form fields with manager data
            $('#managerId').val(managerId);
            $('#firstName').val(firstName);
            $('#lastName').val(lastName);
            $('#phone').val(phone);
            $('#email').val(email);
            $('#password').val('*********');
            $('#password').prop('readonly', true);
            $('#department').val(department);
            $('#btn').text('Update');
            
        });

        // Delete manager button click event
        $(document).on('click', '.delete-btn', function () {
            var managerId = $(this).data('manager-id');
            var confirmation = confirm('Are you sure you want to delete this manager?');
            if (confirmation) {
                $.ajax({
                    url: 'controller/managerController.php?managerId=' + managerId,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            fetchManagers(); // Refresh manager list
                            alert('Manager deleted successfully!');
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