<?php
session_start();
require('AdminNavbar.php');

?>

<div class="container shadow rounded-4 w-75 mt-5 p-4">
    <!-- Create Form -->
    <h2>Register contract</h2>
    <form id="createForm" class="form p-2">
        <div class="form-group row">
            <label class="col-sm-3 mt-2" for="contract_name">Contract Name</label>
            <div class="col-sm-4 mt-2">
                <input type="text" class="form-control" placeholder="Enter contract name here" id="contract_name" required>
            </div>
            <div class="col-sm-3 mt-2">
            <button type="submit" class="btn btn-primary" name="create">Register</button>
            </div>
        </div>
        
    </form>

    <hr>

    <!-- Read Table -->
    <h2>Contracts</h2>
    <table id="contractTable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Contract Name</th>
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
    // AJAX to create contract
    $("#createForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "controller/contractController.php",
            data: {
                create: true,
                contract_name: $("#contract_name").val()
            },
            success: function(response) {
                if (response === "success") {
                    alert("Record created successfully.");
                    loadcontracts();
                } else {
                    alert("Error creating record.");
                }
            }
        });
    });

    // Function to load contracts
    function loadcontracts() {
        $.ajax({
            type: "GET",
            url: "controller/contractController.php",
            data: { read: true },
            success: function(response) {
                var contracts = JSON.parse(response);
                var tableContent = "";
                contracts.forEach(function(contract) {
                    tableContent += "<tr>";
                    tableContent += "<td>" + contract.contract_id + "</td>";
                    tableContent += "<td>" + contract.contract_name + "</td>";
                    tableContent += "<td><button class='btn btn-sm btn-warning updateBtn' data-id='" + contract.contract_id + "' data-name='" + contract.contract_name + "'>Update</button> ";
                    tableContent += "<button class='btn btn-sm btn-danger deleteBtn' data-id='" + contract.contract_id + "'>Delete</button></td>";
                    tableContent += "</tr>";
                });
                $("#contractTable tbody").html(tableContent);
            }
        });
    }

    // Load contracts on page load
    $(document).ready(function() {
        loadcontracts();
    });

    // Update contract
    $(document).on("click", ".updateBtn", function() {
        var contractId = $(this).data("id");
        var contractName = $(this).data("name");
        var newcontractName = prompt("Enter new contract name:", contractName);
        if (newcontractName !== null) {
            $.ajax({
                type: "POST",
                url: "controller/contractController.php",
                data: {
                    update: true,
                    contract_id: contractId,
                    contract_name: newcontractName
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record updated successfully.");
                        loadcontracts();
                    } else {
                        alert("Error updating record.");
                    }
                }
            });
        }
    });

    // Delete contract
    $(document).on("click", ".deleteBtn", function() {
        var contractId = $(this).data("id");
        if (confirm("Are you sure you want to delete this contract?")) {
            $.ajax({
                type: "POST",
                url: "controller/contractController.php",
                data: {
                    delete: true,
                    contract_id: contractId
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Record deleted successfully.");
                        loadcontracts();
                    } else {
                        alert("Error deleting record.");
                    }
                }
            });
        }
    });
</script>
