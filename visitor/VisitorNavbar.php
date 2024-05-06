<?php

if(!$_SESSION['isvisitorlogin']){
    header("Location: ../login.php");
}else{
    require("../Connection.php");
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AAI Dashboard| <?php echo $_SESSION['visitor']['name']; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        
    </head>
    <body>    
        <nav class="navbar navbar-expand-lg navbar-primary navbar-sticky">
            <div class="container-fluid">
                <a class="navbar-brand text-primary" href="<?php echo BASE_URL; ?>/visitor">
                    <b><i class="fas fa-landmark"></i>Dashboard</b>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav flex">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>visitor/pass.php"><b>Request Pass</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>visitor/history.php"><b>History</b></a>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="profileDropdownMenuButton" data-bs-toggle="dropdown"
                            aria-expanded="false" onmouseover="openProfileDropdown()">
                            Profile
                        </button>
                        <ul class="dropdown-menu" id="profileDropdownMenu" aria-labelledby="profileDropdownMenuButton">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#visitorProfileModal">Profile</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ChangePasswordModal">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>visitor/logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>            
            </div>
        </nav>    

        <!-- Visitor Profile Modal -->
        <div class="modal fade" id="visitorProfileModal" tabindex="-1" aria-labelledby="visitorProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="visitorProfileModalLabel">Profile</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <th>Vistor ID</th>
                                <td><?php echo $_SESSION['visitor']['id']; ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?php echo $_SESSION['visitor']['name']; ?></td>
                            </tr>
                            <tr>
                                <th>S/D/W of</th>
                                <td><?php echo $_SESSION['visitor']['sdw']; ?></td>
                            </tr>
                            <tr>
                                <th>Identity</th>
                                <td><?php echo $_SESSION['visitor']['identity']; ?></td>
                            </tr>
                            <tr>
                                <th>ID Image</th>
                                <td>
                                <img class="rounded mx-auto w-75" src="<?php echo $_SESSION['visitor']['upload_id']; ?>" width="250" height="250" />
                                </td>
                            </tr>     
                            <tr>
                                <th>Company ID</th>
                                <td><?php echo $_SESSION['visitor']['company_id']; ?></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td><?php echo $_SESSION['visitor']['address']; ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo $_SESSION['visitor']['phone']; ?></td>
                            </tr>
                            <tr>
                                <th>Email ID</th>
                                <td><?php echo $_SESSION['visitor']['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><?php echo $_SESSION['visitor']['password']; ?></td>
                            </tr>
                            <tr>
                                <th>Reg Time</th>
                                <td><?php echo (date("d-M-Y H:i", strtotime($_SESSION['visitor']['register_time']))) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="UpdateProfile.php"><button type="button" class="btn btn-primary">Update Profile</button></a>
                    </div>  
                </div>          
            </div>
        </div>

        <div class="modal fade" id="ChangePasswordModal" tabindex="-1" aria-labelledby="ChangePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ChangePasswordModalLabel">Change Password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2">
                            <div class="row justify-content-center">                            
                                <form action="#" method="POST" onsubmit="return submitForm()">
                                    <div class="mb-3 row">
                                        <label for="currentPassword" class="col-sm-6 col-form-label">Current Password</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="<?php echo $_SESSION['visitor']['password']; ?>" id="currentPassword" name="currentPassword" required readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="newPassword" class="col-sm-6 col-form-label">New Password</label>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="confirmPassword" class="col-sm-6 col-form-label">Confirm New Password</label>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                        </div>
                                        <div id="passwordMismatch" class="text-danger" style="display: none;">Passwords do not match</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            function openProfileDropdown() {
                var profileDropdownMenu = document.getElementById("profileDropdownMenu");
                if (profileDropdownMenu) {
                    profileDropdownMenu.classList.add("show");
                }
            }
            
            function submitForm() {
                var currentPassword = document.getElementById('currentPassword').value;
                var newPassword = document.getElementById('newPassword').value;
                var confirmPassword = document.getElementById('confirmPassword').value;

                // Check if the new password and confirm password match
                if (newPassword !== confirmPassword) {
                    alert("Passwords do not match. Please re-enter your new password.");
                    return;
                }

                // Perform AJAX request to change the password
                $.ajax({
                    url: 'change_password.php',
                    type: 'POST',
                    data: {
                        currentPassword: currentPassword,
                        newPassword: newPassword
                    },
                    success: function(response) {
                        response = response.replace(/<script>alert\(\'/g, "");
                        response = response.replace(/\'\)<\/script>/g, "");

                        // Display success message
                        alert(response);
                        window.location.href="index.php";
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log the error
                        alert("An error occurred. Please try again later."); // Display error message
                    }
                });
            }
        </script>
<?php
}
?>