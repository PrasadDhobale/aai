<?php
session_start();
if(!$_SESSION['isManagerLogin']){
    header("Location: login.php");
}else{
    require("../Connection.php");
    $roleId = $_SESSION['roleId'];
    $role = $_SESSION['role'];
    $userId = $_SESSION['manager']['manager_id'];
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AAI Manager Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/33b8233684.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>/manager">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>department/requests.php">Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>department/department.php">Department</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#managerProfileModal">Profile</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ChangePasswordModal">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="modal fade" id="managerProfileModal" tabindex="-1" aria-labelledby="managerProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="managerProfileModalLabel">Profile</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <td><?php echo $_SESSION['manager']['manager_id']; ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?php echo $_SESSION['manager']['first_name']. " ". $_SESSION['manager']['last_name']; ?></td>
                            </tr>
                            <tr>                                
                                <th>Dept</th>
                                <?php
                                $getDeptQuery = "SELECT department_name FROM departments WHERE department_id = ".$_SESSION['manager']['dept_id'];
                                $department = $con->query($getDeptQuery)->fetch_assoc();
                                ?>
                                <td><?php echo $department['department_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo $_SESSION['manager']['phone']; ?></td>
                            </tr>
                            <tr>
                                <th>Email ID</th>
                                <td><?php echo $_SESSION['manager']['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><?php echo $_SESSION['manager']['password']; ?></td>
                            </tr>
                            <tr>
                                <th>Reg Time</th>
                                <td><?php echo (date("d-M-Y H:i", strtotime($_SESSION['manager']['reg_time']))) ?></td>
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
                                            <input type="text" class="form-control" value="<?php echo $_SESSION['manager']['password']; ?>" id="currentPassword" name="currentPassword" required readonly>
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

    <script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php
}
?>