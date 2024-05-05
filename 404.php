<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    /* Center the content vertically */
    body, html {
      height: 100%;
    }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
<body>
  <div class="container text-center">
    <h1 class="mt-3">Page Not Found</h1>
    <p class="lead">The page you are looking for could not be found.</p>
    <a href="/" class="btn btn-primary">Go to Home Page</a>
    <img src="<?php require('config.php'); echo BASE_URL; ?>assets/images/notfound.svg" width="400" height="400" class="img-fluid" alt="Page Not Found SVG">    
  </div>
</body>
</html>
