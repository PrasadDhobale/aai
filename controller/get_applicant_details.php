<?php
require('../Connection.php');

if(isset($_POST['application_id'])) {
    $applicationId = $_POST['application_id'];

    $sql = "SELECT name, sdw, designation, phone, address, company_id, identity FROM pass_applications WHERE application_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $applicationId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='table'>";
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo "<tr><td><strong>Name:</strong></td><td>" . $row['name'] . "</td></tr>";
        echo "<tr><td><strong>SDW:</strong></td><td>" . $row['sdw'] . "</td></tr>";
        echo "<tr><td><strong>Designation:</strong></td><td>" . $row['designation'] . "</td></tr>";
        echo "<tr><td><strong>Phone:</strong></td><td>" . $row['phone'] . "</td></tr>";
        echo "<tr><td><strong>Address:</strong></td><td>" . $row['address'] . "</td></tr>";
        echo "<tr><td><strong>Company ID:</strong></td><td>" . $row['company_id'] . "</td></tr>";
        echo "<tr><td><strong>Identity:</strong></td><td>" . $row['identity'] . "</td></tr>";
    } else {
        echo "<tr><td colspan='2'>No details found for this application</td></tr>";
    }
    echo "</table>";

    $stmt->close();
}

$con->close();
?>
