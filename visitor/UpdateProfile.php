<?php
session_start();

if(!$_SESSION['isvisitorlogin']){
    header("Location: ../login.php");
}else{
    include 'VisitorNavbar.php';


    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitForm'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $sdw = $_POST['sdw'];
        $designation = $_POST['designation'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $companyID = $_POST['companyID'];
        $identity = $_POST['identity'];

        // Handle file upload
        if(isset($_FILES['uploadId'])){
            $file_tmp = $_FILES['uploadId']['tmp_name'];

            // Check if file exists and is readable
            if (!file_exists($file_tmp) || !is_readable($file_tmp)) {
                $upload_id = $_SESSION['visitor']['upload_id'];
            }else{

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
                $upload_id = "data:$file_mime_type;base64,$file_data_base64";
            }
        }
        // Update visitor details in the database
        $sql = "UPDATE visitor_data SET name = ?, sdw = ?, designation = ?, phone = ?, address = ?, company_id = ?, identity = ?, upload_id = ? WHERE id = ?";

        // Prepare the SQL statement
        $stmt = $con->prepare($sql);

        // Check if the statement preparation was successful
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssssssssi", $name, $sdw, $designation, $phone, $address, $companyID, $identity, $upload_id, $_SESSION['visitor']['id']);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to a success page or display a success message
                $_SESSION['visitor']['name'] = $name;
                $_SESSION['visitor']['sdw'] = $sdw;
                $_SESSION['visitor']['designation'] = $designation;
                $_SESSION['visitor']['phone'] = $phone;
                $_SESSION['visitor']['address'] = $address;
                $_SESSION['visitor']['company_id'] = $companyID;
                $_SESSION['visitor']['identity'] = $identity;
                $_SESSION['visitor']['upload_id'] = $upload_id;
                
                echo "<script>alert('Profile Updated Successfully'); window.location.href='index.php';</script>";
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        } else {
            echo "Error: Unable to prepare SQL statement.";
        }

        // Close the statement
        $stmt->close();
    }


    ?>
    <div class="container shadow mt-5 p-5">
        <h5 class="card-title text-center mb-4">Update Profile : <?php echo $_SESSION['visitor']['name']; ?></h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" value="<?php echo $_SESSION['visitor']['name'] ; ?>" name="name" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="sdw" class="col-sm-2 col-form-label">S/D/W Of</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="sdw" value="<?php echo $_SESSION['visitor']['sdw'] ; ?>" name="sdw" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="designation" class="col-sm-2 col-form-label">Designation</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="designation" value="<?php echo $_SESSION['visitor']['designation'] ; ?>" name="designation" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    <input type="tel" class="form-control" id="phone" value="<?php echo $_SESSION['visitor']['phone'] ; ?>" name="phone" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="address" class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $_SESSION['visitor']['address']; ?></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="companyID" class="col-sm-2 col-form-label">Company ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="companyID" value="<?php echo $_SESSION['visitor']['company_id'] ; ?>" name="companyID">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="identity" class="col-sm-2 col-form-label">Identity</label>
                <div class="col-sm-10">
                    <select class="form-select" id="identity" name="identity" required>
                        <option value="Aadhaar Card" <?php if($_SESSION['visitor']['identity'] == "Aadhaar Card") echo "selected"; ?>>Aadhaar Card</option>
                        <option value="Pan Card" <?php if($_SESSION['visitor']['identity'] == "Pan Card") echo "selected"; ?>>Pan Card</option>
                        <option value="Driving License" <?php if($_SESSION['visitor']['identity'] == "Driving License") echo "selected"; ?>>Driving License</option>
                        <option value="Election Card" <?php if($_SESSION['visitor']['identity'] == "Election Card") echo "selected"; ?>>Election Card</option>
                        <option value="Passport" <?php if($_SESSION['visitor']['identity'] == "Passport") echo "selected"; ?>>Passport</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">                
                <label for="uploadId" class="col-sm-2 col-form-label">Upload Id</label>
                <div class="col-sm-10">
                    <img class="rounded mx-auto w-75" src="<?php echo $_SESSION['visitor']['upload_id']; ?>" width="250" height="250" />
                </div>
                <input type="file" class="form-control" id="uploadId" name="uploadId" accept="image/*,.pdf">
            </div>
            <button type="submit" name="submitForm" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
<?php
}?>
