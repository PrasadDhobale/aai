<?php
session_start();

require('../navbar.php');

// Include the database connection file here if needed
// require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Assuming you have a users table in your database
    // Perform your database query here to check if the username and password are correct
    // Example:
    // $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    // Execute the query and fetch the result

    // For demonstration, I'm using hardcoded values for username and password
    $correct_username = "admin";
    $correct_password = "pad";

    // Check if the provided username and password match the correct ones
    if ($username === $correct_username && $password === $correct_password) {
        // Redirect to dashboard.php if login is successful
        $_SESSION['isAdminLogin'] = true;
        $_SESSION['role'] = "admin";
        header("Location: index.php");
        exit;
    } else {
        // Display error message if login fails
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3 mx-auto w-75">Invalid username or password. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

?>
<script>
    document.title = "Admin Login | AAI";
</script>
<div class="login-form container shadow mt-5 p-5 w-75">
    <h2 class="text-center">Admin Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="mt-2 btn btn-primary btn-block">Login</button>
    </form>
</div>