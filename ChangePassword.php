<?php
session_start();
// Function to decrypt data
function decryptData($encryptedData, $key) {
    $cipher = "aes-256-cbc"; // Using AES encryption with CBC mode
    $iv = "1234567890123456"; // Fixed IV (Initialization Vector)
    $encryptedData = base64_decode($encryptedData); // Decode from base64
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA, $iv); // Decrypt the data
    return $decryptedData;
}

// Function to decrypt the GET request parameters
function decryptGETParameters($key) {
    // Check if all required parameters are present
    if (isset($_GET['id']) && isset($_GET['email']) && isset($_GET['password'])) {
        // Retrieve the encrypted data from the GET request

        // Extract id, email, and password
        $role = $_GET['role'];
        $id = decryptData($_GET['id'], $key);
        $email = decryptData($_GET['email'], $key);
        $password = decryptData($_GET['password'], $key);

        // Return an associative array containing id, email, and password
        return array(
            'role' => $role,
            'id' => $id,
            'email' => $email,
            'password' => $password
        );
    } else {
        // If required parameter is missing, return null
        return null;
    }
}

// Function to verify credentials
function verifyCredentials($con, $role, $id, $email, $password) {
    
    $sql = '';
    if($role == 'manager')
        $sql = "SELECT email, password FROM managers WHERE manager_id = ?";
    else if($role == 'contractor')
        $sql = "SELECT email, password FROM contractors WHERE contractor_id = ?";

    // Prepare the SQL statement
    $stmt = $con->prepare($sql);

    // Bind parameters
    $stmt->bind_param("i", $id);

    // Execute the statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($dbEmail, $dbPassword);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Verify if the retrieved email and password match the provided ones
    if ($email === $dbEmail && $password === $dbPassword) {
        return true; // Credentials are correct
    } else {        
        return false; // Credentials are incorrect    
    }
}

// Usage example
$secretKey = "aaisecretkey";

// Decrypt the GET request parameters
$decryptedParameters = decryptGETParameters($secretKey);


if ($decryptedParameters !== null) {

    include('navbar.php');

    $role = $decryptedParameters['role'];
    $id = $decryptedParameters['id'];
    $email = $decryptedParameters['email'];
    $password = $decryptedParameters['password'];

    // Verify credentials
    
    $credentialsCorrect = verifyCredentials($con, $role, $id, $email, $password);

    if ($credentialsCorrect) {        
        
        // Prepare the SQL query using prepared statement
        $CheckUserQuery = '';
        if($role == 'manager')
            $CheckUserQuery = "SELECT * FROM managers WHERE manager_id = ?";
        else if($role == 'contractor')
            $CheckUserQuery = "SELECT * FROM contractors WHERE contractor_id = ?";
        
        $stmt = $con->prepare($CheckUserQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the row
        $row = $result->fetch_assoc();

        if($row) {
            
            if($role == 'manager'){
                
                $_SESSION['manager'] = $row;
                $_SESSION['isManagerLogin'] = true;
                
                if($row['dept_id'] == 1000 || $row['dept_id'] == 1001 || $row['dept_id'] == 1002)
                    $_SESSION['role'] = "incharge";
                else if($row['dept_id'] == 1003)
                    $_SESSION['role'] = "print";
                else
                    $_SESSION['role'] = "manager";
                
                $_SESSION['roleId'] = $row['manager_id'];
                $forgotPasswordUrl = 'department/change_password.php';
            }
            else if($role == 'contractor'){
                
                $_SESSION['contractor'] = $row;
                $_SESSION['isContractorLogin'] = true;
                $_SESSION['role'] = "contractor";
                $_SESSION['roleId'] = $row['contract_id'];
                $forgotPasswordUrl = 'contractor/change_password.php';
                
            }

            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">Change Password</h5>
                                <form action="#" method="POST" onsubmit="return submitForm()">
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Current Password</label>
                                        <input type="text" class="form-control" value="<?php echo $password; ?>" id="currentPassword" name="currentPassword" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                        <div id="passwordMismatch" class="text-danger" style="display: none;">Passwords do not match</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Change Password</button>
                                </form>
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
                        url: "<?php echo $forgotPasswordUrl; ?>",
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
                            window.location.href= "<?php echo $role == 'manager' ? 'manager' : 'contractor'; ?>";
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log the error
                            alert("An error occurred. Please try again later."); // Display error message
                        }
                    });
                }
            </script>
            <?php
        } else {
            // Invalid credentials, redirect to login page with alert
            echo "<script>alert('Invalid Credentials for manager.'); window.location.href='index.php';</script>";            
        }   
    } else {
        echo "<script>alert('Credentials are incorrect.'); window.location.href='index.php';</script>";
    }
} else {
    // Handle case where required parameter is missing
    echo "<script>alert('Error: Missing or invalid parameter.'); window.location.href='index.php';</script>";
}
?>