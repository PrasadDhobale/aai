<?php
session_start();
include('../navbar.php');

// Check if email and manager token are set
if(isset($_POST['loginBtn'])) {
    
    // Sanitize user inputs
    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string($_POST['password']);

    // Prepare the SQL query using prepared statement
    $CheckContractorQuery = "SELECT * FROM contractors WHERE email = ? AND password = ?";
    $stmt = $con->prepare($CheckContractorQuery);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the row
    $contractor = $result->fetch_assoc();

    if($contractor) {
        $_SESSION['contractor'] = $contractor;
        $_SESSION['isContractorLogin'] = true;
        $_SESSION['role'] = "contractor";
        $_SESSION['roleId'] = $contractor['contract_id'];
        header("Location: index.php");
        exit;
    } else {
        // Invalid credentials, redirect to login page with alert
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3 mx-auto w-75">Invalid username or password. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }    
}
?>

<div class="container mt-5">
    <div class="shadow p-4 m-4 pb-4 mt-5">
        <h2>Contractor Login</h2>
        <form id="contractor_login" method="POST" class="form p-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return checkCaptcha();">
            <div class="mb-3">
                <label for="email"><b>Email</b></label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Here" required>
            </div>
            <div class="mb-3">
                <label for="password"><b>Password</b></label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password Here" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <b class="text-primary" aria-hidden="true">🙈</b>
                    </button>                    
                </div>
                
                <a href="#" aria-current="page" data-bs-toggle="modal" data-bs-target="#ForgotPasswordModal" class="fw-bold">Forgot Password 😕?</a>
            </div>
            <?php $captcha = rand(1000, 90000); ?>
            <div class="mb-3">
                <label for="captcha"><b>Captcha</b></label>
                <span class="btn btn-outline" disabled><?php echo $captcha; ?></span>
                <input type="text" name="captcha" id="captcha" class="form-control" placeholder="Enter Captcha" required>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" name="loginBtn" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="ForgotPasswordModal" tabindex="-1" aria-labelledby="ForgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ForgotPasswordModalLabel">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="forgotPasswordForm">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="fpemail" name="email" placeholder="Enter your registered email here" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="sendResetLink()">Send Reset Link</button>
      </div>
    </div>
  </div>
</div>


<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordInput = document.getElementById('password');
        var passworToggle = document.getElementById('togglePassword');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passworToggle.innerHTML = '<i class="bx bx-hide text-primary" aria-hidden="true">🐵</i>';
        } else {
            passwordInput.type = "password";
            passworToggle.innerHTML = '<i class="bx bx-show text-primary" aria-hidden="true">🙈</i>';
        }
    });

    function checkCaptcha() {
        var enteredCaptcha = document.getElementById("captcha").value;
        if (enteredCaptcha == <?php echo $captcha; ?>) {            
            return true;
        } else {
            alert("Invalid Captcha Entered..");
            return false;
        }
    }

    function sendResetLink() {
    var email = document.getElementById('fpemail').value;

    // Perform email validation
    if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    // Perform AJAX request to send the reset link
    $.ajax({
        url: '../forgot-password.php',
        type: 'POST',
        data: { email: email, role: 'contractor' },
        success: function(response) {
            response = response.replace(/<script>alert\(\'/g, "");
            response = response.replace(/\'\)<\/script>/g, "");

            // Display success message
            alert(response);
            $('#ChangePasswordModal').modal('hide');
            $('#ForgotPasswordModal').modal('hide'); // Close the modal
        },
        error: function(xhr, status, error) {
        console.error(xhr.responseText); // Log the error
        alert("An error occurred. Please try again later."); // Display error message
        }
    });
    }

    // Function to validate email address
    function validateEmail(email) {
    // Regular expression for email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
    }

</script>

</body>
</html>
