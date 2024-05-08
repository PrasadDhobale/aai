<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand text-primary" href="#">
            <img src="assets/images/aai_logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            <b><i class="fas fa-landmark"></i> A A I</b>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-dark">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>#working">How it Works?</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>#about">About Us</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Login
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#visitorProfileModal">Visitor</a></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ChangePasswordModal">Department</a></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ChangePasswordModal">Security Incharge</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>register.php">Register</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      
</body>
</html>