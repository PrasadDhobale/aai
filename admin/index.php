<?php
session_start();
require('AdminNavbar.php');
?>
 <style>
        body {
            background-color: #f7f9fc;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 16px rgba(0, 0, 0, 0.2);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .card-icon {
            font-size: 2.5rem;
            color: #007bff;
            padding-right: 5px;
        }
        .card-body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 150px;
        }
    </style>
    <div class="container mt-5">
        <div class="row">
            <!-- Requests Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/requests.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5 class="card-title">Requests</h5>
                    </div>
                </div>
            </div>
            <!-- Contractor Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/contractor.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                        <h5 class="card-title">Contractor</h5>
                    </div>
                </div>
            </div>
            <!-- Contracts Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/contract.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h5 class="card-title">Contracts</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Areas Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/area.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="card-title">Areas</h5>
                    </div>
                </div>
            </div>
            <!-- Departments Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/department.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5 class="card-title">Departments</h5>
                    </div>
                </div>
            </div>
            <!-- Manager Card -->
            <div class="col-md-4 mb-4" onclick="window.location.href = '<?php echo BASE_URL; ?>admin/manager.php'">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h5 class="card-title">Manager</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>