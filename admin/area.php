<?php
session_start();
require('AdminNavbar.php');

?>

<div class="container shadow rounded-4 w-75 mt-5 p-4">
    <!-- Create Form -->
    <h2>Register area</h2>
    <form id="createForm" class="form p-2">
        <div class="form-group row">
            <label class="col-sm-3 mt-2" for="area_name">Area Name</label>
            <div class="col-sm-4 mt-2">
                <input type="text" class="form-control" placeholder="Enter area name here" id="area_name" required>
            </div>
            <div class="col-sm-3 mt-2">
            <button type="submit" class="btn btn-primary" name="create">Register</button>
            </div>
        </div>
        
    </form>

    <hr>

    <!-- Read Table -->
    <h2>Areas</h2>
    <table id="areaTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Area Name</th>
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
    // AJAX to create area
    $("#createForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "controller/areaController.php",
            data: {
                create: true,
                area_name: $("#area_name").val()
            },
            success: function(response) {
                if (response === "success") {
                    alert("Record created successfully.");
                    loadareas();
                } else {
                    alert("Error creating record.");
                }
            }
        });
    });

    // Function to load areas
    function loadareas() {
        $.ajax({
            type: "GET",
            url: "controller/areaController.php",
            data: { read: true },
            success: function(response) {
                var areas = JSON.parse(response);
                var tableContent = "";
                areas.forEach(function(area) {
                    tableContent += "<tr>";
                    tableContent += "<td>" + area.area_id + "</td>";
                    tableContent += "<td>" + area.area_name + "</td>";
                    tableContent += "<td><button class='btn btn-sm btn-warning updateBtn' data-id='" + area.area_id + "' data-name='" + area.area_name + "'>Update</button> ";
                    tableContent += "<button class='btn btn-sm btn-danger deleteBtn' data-id='" + area.area_id + "'>Delete</button></td>";
                    tableContent += "</tr>";
                });
                $("#areaTable tbody").html(tableContent);
            }
        });
    }

    // Load areas on page load
    $(document).ready(function() {
        loadareas();
    });

    // Update area
    $(document).on("click", ".updateBtn", function() {
        var areaId = $(this).data("id");
        var areaName = $(this).data("name");
        var newareaName = prompt("Enter new area name:", areaName);
        if (newareaName !== null) {
            $.ajax({
                type: "POST",
                url: "controller/areaController.php",
                data: {
                    update: true,
                    area_id: areaId,
                    area_name: newareaName
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record updated successfully.");
                        loadareas();
                    } else {
                        alert("Error updating record.");
                    }
                }
            });
        }
    });

    // Delete area
    $(document).on("click", ".deleteBtn", function() {
        var areaId = $(this).data("id");
        if (confirm("Are you sure you want to delete this area?")) {
            $.ajax({
                type: "POST",
                url: "controller/areaController.php",
                data: {
                    delete: true,
                    area_id: areaId
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record deleted successfully.");
                        loadareas();
                    } else {
                        alert("Error deleting record.");
                    }
                }
            });
        }
    });
</script>
