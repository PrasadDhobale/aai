<?php
session_start();
require('AdminNavbar.php');

?>

<div class="container shadow rounded-4 w-75 mt-5 p-4">
    <!-- Create Form -->
    <h2>Register Department</h2>
    <form id="createForm" class="form p-2">
        <div class="form-group row">
            <label class="col-sm-3 mt-2" for="department_name">Department Name</label>
            <div class="col-sm-4 mt-2">
                <input type="text" class="form-control" placeholder="Enter department name here" id="department_name" required>
            </div>
            <div class="col-sm-3 mt-2">
            <button type="submit" class="btn btn-primary" name="create">Register</button>
            </div>
        </div>
        
    </form>

    <hr>

    <!-- Read Table -->
    <h2>Departments</h2>
    <table id="departmentTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here via AJAX -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // AJAX to create department
    $("#createForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "controller/deptController.php",
            data: {
                create: true,
                department_name: $("#department_name").val()
            },
            success: function(response) {
                if (response === "success") {
                    alert("Record created successfully.");
                    loadDepartments();
                } else {
                    alert("Error creating record.");
                }
            }
        });
    });

    // Function to load departments
    function loadDepartments() {
        $.ajax({
            type: "GET",
            url: "controller/deptController.php",
            data: { read: true },
            success: function(response) {
                var departments = JSON.parse(response);
                var tableContent = "";
                departments.forEach(function(department) {
                    tableContent += "<tr>";
                    tableContent += "<td>" + department.department_id + "</td>";
                    tableContent += "<td>" + department.department_name + "</td>";
                    tableContent += "<td><button class='btn btn-sm btn-warning updateBtn' data-id='" + department.department_id + "' data-name='" + department.department_name + "'>Update</button> ";
                    tableContent += "<button class='btn btn-sm btn-danger deleteBtn' data-id='" + department.department_id + "'>Delete</button></td>";
                    tableContent += "</tr>";
                });
                $("#departmentTable tbody").html(tableContent);
            }
        });
    }

    // Load departments on page load
    $(document).ready(function() {
        loadDepartments();
    });

    // Update department
    $(document).on("click", ".updateBtn", function() {
        var departmentId = $(this).data("id");
        var departmentName = $(this).data("name");
        var newDepartmentName = prompt("Enter new department name:", departmentName);
        if (newDepartmentName !== null) {
            $.ajax({
                type: "POST",
                url: "controller/deptController.php",
                data: {
                    update: true,
                    department_id: departmentId,
                    department_name: newDepartmentName
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record updated successfully.");
                        loadDepartments();
                    } else {
                        alert("Error updating record.");
                    }
                }
            });
        }
    });

    // Delete department
    $(document).on("click", ".deleteBtn", function() {
        var departmentId = $(this).data("id");
        if (confirm("Are you sure you want to delete this department?")) {
            $.ajax({
                type: "POST",
                url: "controller/deptController.php",
                data: {
                    delete: true,
                    department_id: departmentId
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record deleted successfully.");
                        loadDepartments();
                    } else {
                        alert("Error deleting record.");
                    }
                }
            });
        }
    });
</script>
