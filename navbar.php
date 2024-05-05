<?php
include "Connection.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitor Entry Pass System - Airport Authority of India</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
  </head>
  <body>    
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand text-info" href="#">
                <img src="assets/images/aai_logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                <b><i class="fas fa-landmark"></i> A A I</b>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav flex">
                        <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>"><b>Home</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>#features"><b>Features</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>#working"><b>How it Works?</b></a>                        
                        <a class="nav-link" href="<?php echo BASE_URL; ?>#about"><b>About Us</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>login.php"><b>Login</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>register.php"><b>Register</b></a>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>#contact"><b>Contact</b></a>
                    </div>
                </div>
                <!-- <a href="/aai#contact"><button class="btn btn-outline-success my-2 my-sm-0">Contact</button></a> -->
            </div>
        </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>