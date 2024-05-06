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
            // echo $otp;

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
    $file_name = $_FILES['uploadId']['name'];
    $file_type = $_FILES['uploadId']['type'];

    // Check if file is an image or PDF
    $allowed_types = array('image/jpeg', 'image/png', 'image/jpg', 'application/pdf');
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>alert('Error: Only images (JPEG, PNG, JPG) and PDF files are allowed.'); window.location.href = 'register.php'</script>";
        exit;
    }

    // Check if file exists and is readable
    if (!file_exists($file_tmp) || !is_readable($file_tmp)) {
        echo "<script>alert('Error: Unable to read uploaded file.'); window.location.href = 'register.php'</script>";
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3 row">
                    <label for="name" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-7">
                        <input type="text" value="<?php echo $_SESSION['email']; ?>" class="form-control" id="name" name="name" required readonly>
                    </div>
                    <div class="col-sm-3">
                        <?php
                        if(isset($_POST['changeEmail'])){
                            $_SESSION['emailVerified'] = false;
                            header('Location: register.php');
                        }
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form">
                            <button type="submit" name="changeEmail" class="btn btn-sm btn-outline-danger"><b>Change Email</b></button>
                        </form>
                    </div>
                    <input type="hidden" class="form-control" value="<?php echo $visitor_id; ?>" id="visitor_id" name="visitor_id" required>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-sm-2 col-form-label">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="John Smith" class="form-control" id="name" name="name" required>
                    </div>
                    <input type="hidden" class="form-control" value="<?php echo $visitor_id; ?>" id="visitor_id" name="visitor_id" required>
                </div>
                <div class="mb-3 row">
                    <label for="sdw" class="col-sm-2 col-form-label">S/D/W Of</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="Steve Smith" class="form-control" id="sdw" name="sdw" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="designation" class="col-sm-2 col-form-label">Designation</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="Employee" class="form-control" id="designation" name="designation" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <input type="tel" placeholder="+91 9078234323" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <textarea placeholder="enter your address" class="form-control" id="address" name="address" rows="2" required></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="companyID" class="col-sm-2 col-form-label">Company ID</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="enter company id" class="form-control" id="companyID" name="companyID">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="identity" class="col-sm-2 col-form-label">Identity</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="identity" name="identity" required>
                    </div>
                    <option value="Aadhaar Card">Aadhaar Card</option>
                    <option value="Pan Card">Pan Card</option>
                    <option value="Driving License">Driving License</option>
                    <option value="Election Card">Election Card</option>
                    <option value="Passport">Passport</option>
                    </select>
                </div>               
                <div class="mb-3 row mt-3">
                    <label for="uploadId" class="col-sm-2 col-form-label">Upload Id</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="uploadId" name="uploadId" accept="image/*,.pdf" required>
                    </div>
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
