<?php
include "navbar.php";
session_start();

// Function to generate a random visitor ID
function generateVisitorID() {
    $prefix = "aaiv_";
    $random_number = mt_rand(100000, 999999);
    return $prefix . $random_number;
}

// Check if OTP is verified
if(isset($_SESSION['emailVerified']) && $_SESSION['emailVerified'] == true){
    ?>
    <p>Fill Your Further Details</p>
    <?php
} else {
    // OTP verification form
    if(isset($_POST['verifyOtpBtn'])){
        if($_SESSION['otp'] == $_POST['otp']){
            echo "<script>alert('Verified Successfully')</script>";
            $_SESSION['emailVerified'] = true;
        } else{
            echo "<script>alert('Incorrect OTP Try again..')</script>";
        }    
    }

    // Function to check if an email exists in the database
    function isEmailExists($email, $con) {
        

        // Sanitize the email to prevent SQL injection
        $email = mysqli_real_escape_string($con, $email);

        // SQL query to check if the email exists in the database
        $sql = "SELECT COUNT(*) as count FROM visitor_data WHERE email = '$email'";

        // Execute the query
        $result = mysqli_query($con, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);

            // Check if the count is greater than 0, meaning the email exists
            if ($row['count'] > 0) {
                // Email exists
                return true;
            } else {
                // Email does not exist
                return false;
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

    if(isset($_POST['verifyEmail'])){
        
        if(isEmailExists($_POST['email'], $con)){
            echo "<script>alert('Email ID already Exist'); window.location.href = 'register.php'</script>";
        }else{

            $otp = mt_rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $_POST['email'];
            $email = $_POST['email'];

            // send otp to entered email.

            $role = "evs";
            ob_start();
            require 'verifyemailcontent.php';
            $subject = "AAI Visitor Pass [Email Verification]";
            $body = ob_get_clean();
            require 'sendEmail.php';

            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-info text-center fw-bold">OTP Sent To Your Entered Email.</p>
                                <h5 class="card-title text-center mb-4">Enter OTP</h5>            
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <div class="mb-3">
                                        <input type="number" name="otp" class="form-control" placeholder="Enter OTP" required>
                                    </div>
                                    <button type="submit" name="verifyOtpBtn" class="btn btn-primary w-100">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

// Display the form to enter email and send OTP if email is not verified
if(!isset($_SESSION['emailVerified']) || $_SESSION['emailVerified'] != true){
?>
<div class="container shadow mt-5 p-5">
    <h2 class="text-center">Register here</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="verifyEmail" class="form px-5">
        <div class="mb-3 pt-5">
            <label class="form-label"><b>Email Address</b></label>
            <input type="email" placeholder="Enter Your Email" class="form-control" name="email" required />
            <div id="emailHelp" class="form-text text-warning">We'll Send Verification Email</div>
            <button class="btn btn-primary mt-4" name="verifyEmail" value="true">Verify Email</button>
        </div>
    </form>
</div>
<?php
}


function generatePassword() {
    // Define a pool of characters to choose from
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Get the length of the character pool
    $charactersLength = strlen($characters);
    // Initialize the password variable
    $password = '';
    // Loop to generate 8 random characters
    for ($i = 0; $i < 8; $i++) {
        // Choose a random character from the pool
        $password .= $characters[rand(0, $charactersLength - 1)];
    }
    return $password;
}

// Save the Form Data
if (isset($_POST["submitForm"])) {
    // Get form data
    $visitor_id = $_POST['visitor_id'];
    $name = $_POST['name'];
    $email = $_SESSION['email'];
    $password = generatePassword();
    $sdw = $_POST['sdw'];
    $designation = $_POST['designation'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $companyID = $_POST['companyID'];
    $identity = $_POST['identity'];    

    // Handle file upload
    $file_tmp = $_FILES['uploadId']['tmp_name'];

    // Check if file exists and is readable
    if (!file_exists($file_tmp) || !is_readable($file_tmp)) {
        echo "Error: Unable to read uploaded file.";
        exit;
    }

    // Read the file content
    $file_data = file_get_contents($file_tmp);

    // Check if file content is empty
    if ($file_data === false) {
        echo "Error: Unable to read file content.";
        exit;
    }

    // Encode file content in Base64 format
    $file_data_base64 = base64_encode($file_data);

    // Check if Base64 encoding was successful
    if ($file_data_base64 === false) {
        echo "Error: Unable to encode file content in Base64 format.";
        exit;
    }

    // Get file MIME type
    $file_mime_type = mime_content_type($file_tmp);

    // Generate data URI
    $file_data = "data:$file_mime_type;base64,$file_data_base64";

    
    date_default_timezone_set("Asia/Kolkata");
    $regTime = date("Y-m-d H:i:s");

    // Save form data and file to database
    $sql = "INSERT INTO visitor_data (id, name, email, password, sdw, designation, phone, address, company_id, identity, upload_id, register_time) VALUES ('$visitor_id', '$name', '$email', '$password', '$sdw', '$designation', '$phone', '$address', '$companyID', '$identity', '$file_data', '$regTime')";
    if (mysqli_query($con, $sql)) {

        $role = "password";
        ob_start();
        require 'send_Login_cred.php';
        $subject = "AAI Visitor Pass [Login Credentials]";
        $body = ob_get_clean();
        require 'sendEmail.php';
        echo "<script>alert('Registered Successfully'); window.location.href = 'login.php'</script>";
        $_SESSION['emailVerified'] = false;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

// If email is verified, generate visitor ID
if(isset($_SESSION['emailVerified']) && $_SESSION['emailVerified'] == true){
    // Generate a random visitor ID
    $visitor_id = generateVisitorID();
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Visitor Application Form</h5>
                <form action="#" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name of Applicant:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <input type="hidden" class="form-control" value="<?php echo $visitor_id; ?>" id="visitor_id" name="visitor_id" required>
                </div>
                <div class="mb-3">
                    <label for="sdw" class="form-label">S/D/W of Name:</label>
                    <input type="text" class="form-control" id="sdw" name="sdw" required>
                </div>
                <div class="mb-3">
                    <label for="designation" class="form-label">Designation of Applicant:</label>
                    <input type="text" class="form-control" id="designation" name="designation" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Mob/Telephone no:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="companyID" class="form-label">Company ID:</label>
                    <input type="text" class="form-control" id="companyID" name="companyID">
                </div>
                <div class="mb-3">
                    <label for="identity" class="form-label">Identity:</label>
                    <select class="form-select" id="identity" name="identity" required>
                    <option value="Aadhaar Card">Aadhaar Card</option>
                    <option value="Pan Card">Pan Card</option>
                    <option value="Driving License">Driving License</option>
                    <option value="Election Card">Election Card</option>
                    <option value="Passport">Passport</option>
                    </select>
                </div>               
                <div class="mb-3">
                    <label for="uploadId" class="form-label">Upload Id:</label>
                    <input type="file" class="form-control" id="uploadId" name="uploadId" accept="image/*,.pdf" required>
                </div>
                <button type="submit" name="submitForm" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    <?php
}
?>
