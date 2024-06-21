<?php

// Calculate total number of rows
if($role == "contractor"){
    $totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM pass_applications WHERE contract_id = $roleId";
} else if($role == "manager"){
    $totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM pass_applications WHERE application_id IN (SELECT application_id FROM approval_level WHERE contractor_id != 0) AND contract_id = 0";
} else if($role == "incharge"){
    $totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM pass_applications WHERE application_id IN (SELECT application_id FROM approval_level WHERE manager_id != 0)";
} else if($role == "print"){
    $totalRowsQuery = "SELECT COUNT(*) AS total_rows FROM pass_applications WHERE application_id IN (SELECT application_id FROM approval_level WHERE incharge_id != 0)";
}

$totalRowsResult = $con->query($totalRowsQuery);
$totalRows = $totalRowsResult->fetch_assoc()['total_rows'];

// Define number of rows per page
$rowsPerPage = 10;

// Calculate total number of pages
$totalPages = ceil($totalRows / $rowsPerPage);

// Determine current page
if (!isset($_GET['page']) || $_GET['page'] < 1 || $_GET['page'] > $totalPages) {
    $currentPage = 1;
} else {
    $currentPage = $_GET['page'];
}

// Calculate the offset
$offset = ($currentPage - 1) * $rowsPerPage;

// Fetch data for the current page
if($role == "contractor"){
    $sql = "SELECT application_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, document_number, issue_date, contract_id, department_id, areaOfVisit, apply_time FROM pass_applications WHERE contract_id = $roleId ORDER BY apply_time DESC LIMIT $offset, $rowsPerPage";
} else if($role == "manager"){
    $sql = "SELECT application_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, document_number, issue_date, contract_id, other_contract, department_id, areaOfVisit, apply_time FROM pass_applications WHERE department_id = ".$_SESSION['manager']['dept_id']." AND application_id IN (SELECT application_id FROM approval_level WHERE contractor_id != 0) OR contract_id = 0 ORDER BY apply_time DESC LIMIT $offset, $rowsPerPage";
} else if($role == "incharge"){
    $sql = "SELECT application_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, document_number, issue_date, contract_id, other_contract, department_id, areaOfVisit, apply_time FROM pass_applications WHERE application_id IN (SELECT application_id FROM approval_level WHERE manager_id != 0) ORDER BY apply_time DESC LIMIT $offset, $rowsPerPage";
} else if($role == "print"){
    $sql = "SELECT application_id, purpose_of_visit, from_timestamp, to_timestamp, police_clearance, document_number, issue_date, contract_id, other_contract, department_id, areaOfVisit, apply_time FROM pass_applications WHERE application_id IN (SELECT application_id FROM approval_level WHERE incharge_id != 0) ORDER BY apply_time DESC LIMIT $offset, $rowsPerPage";
}

$result = $con->query($sql);

if ($result->num_rows > 0) {
    ?>
    <div class="table-responsive">
        <table class="table">
            <!-- Table header -->
            <thead>
                <tr>
                    <th>ID</th>                    
                    <th>Purpose</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Identity</th>
                    <th>Clearance</th>
                    <th>Doc No</th>
                    <th>Issue Date</th>
                    <th>Contract</th>
                    <th>Dept</th>
                    <th>Area of Visit</th>
                    <th>Apply Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody>
                <?php
                // Output data rows
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                        echo "<td class='applicant-details' style='cursor: pointer;' data-id='" . $row["application_id"] . "'>" . $row["application_id"] . "</td>";
                        echo "<td>" . $row["purpose_of_visit"] . "</td>";
                        echo "<td>" . $row["from_timestamp"] . "</td>";
                        echo "<td>" . $row["to_timestamp"] . "</td>";
                        
                        echo "<td> <button title='View Image' class='btn btn-outline-primary view-identity' data-toggle='modal' data-target='#idModal' data-id='" . $row["application_id"] . "'><i class='fa fa-eye'></i></button></td>";

                        if($row['police_clearance'] === "yes"){
                            echo "<td><button title='View Image' class='btn btn-outline-primary view-clearance' data-toggle='modal' data-target='#clearanceModal' data-id='" . $row["application_id"] . "'><i class='fa fa-eye'></i></button></td>";
                        } else {
                            echo "<td>No</td>";
                        }
                        
                        echo "<td>" . $row["document_number"] . "</td>";
                        echo "<td>" . $row["issue_date"] . "</td>";
                        
                        // Associate Contract Name with ID
                        $getContractQuery = "SELECT contract_name FROM contracts WHERE contract_id = " . $row['contract_id'];
                        $contract = $con->query($getContractQuery)->fetch_assoc();
                        echo "<td>" . ($contract["contract_name"] == 'other' ? $row["other_contract"] : $contract["contract_name"]) . "</td>";

                        // Associate Department Name with ID
                        $getDeptQuery = "SELECT department_name FROM departments WHERE department_id = " . $row['department_id'];
                        $department = $con->query($getDeptQuery)->fetch_assoc();
                        echo "<td>" . $department["department_name"] . "</td>";

                        // Associate Area Name with ID
                        echo "<td>";
                        $areaOfVisitArray = explode(",", $row["areaOfVisit"]);

                        if (!empty($areaOfVisitArray)) {
                            $areaIds = implode(",", array_map('intval', $areaOfVisitArray)); // Sanitize input for safety
                            $getAreaQuery = "SELECT area_name FROM areas WHERE area_id IN ($areaIds)";
                            $Areas = $con->query($getAreaQuery);

                            if ($Areas && $Areas->num_rows > 0) {
                                $areaNames = [];
                                while ($area = $Areas->fetch_assoc()) {
                                    $areaNames[] = $area["area_name"];
                                }
                                echo implode(", ", $areaNames);
                            } else {
                                echo "No areas found";
                            }
                        } else {
                            echo "No areas specified";
                        }
                        echo "</td>";

                        echo "<td>" . $row["apply_time"] . "</td>";
                        
                        if($role == 'print'){
                            echo "<td><button class='btn btn-primary' onclick='openPrintPage(" . $row["application_id"] . ")'>Print</button></td>";
                        } else {
                            $isRejectedQuery = "SELECT reason, rejected_by_role, rejected_by_id, rejected_at FROM approval_level WHERE application_id = " . $row['application_id'];
                            $CheckIsRejected = $con->query($isRejectedQuery)->fetch_assoc();
                            $isRejected = $CheckIsRejected['reason'];
                            $rejectedBy = $CheckIsRejected['rejected_by_role'];
                            $rejectedById = $CheckIsRejected['rejected_by_id'];
                            $rejectedAt = $CheckIsRejected['rejected_at'];
                            
                            if(!$isRejected){
                                $isApprovedQuery = '';
                                $CheckIsApproved = '';
                                $approvedByUser = '';
                                $getApprovedByUser = '';
                                $isApproved = '';
                                
                                if($_SESSION['role'] == 'contractor'){
                                    $isApprovedQuery = "SELECT contractor_id FROM approval_level WHERE application_id = " . $row['application_id'];
                                    $CheckIsApproved = $con->query($isApprovedQuery)->fetch_assoc();
                                    $isApproved = $CheckIsApproved['contractor_id'];
                                    $getApprovedByUser = "SELECT contractor_name FROM contractors WHERE contractor_id = " . $isApproved;
                                    $approvedByUser = $con->query($getApprovedByUser)->fetch_assoc();
                                    $approvedByUser = $approvedByUser['contractor_name'];
                                } else if($_SESSION['role'] == 'manager'){
                                    $isApprovedQuery = "SELECT manager_id FROM approval_level WHERE application_id = " . $row['application_id'];
                                    $CheckIsApproved = $con->query($isApprovedQuery)->fetch_assoc();
                                    $isApproved = $CheckIsApproved['manager_id'];
                                    $getApprovedByUser = "SELECT first_name FROM managers WHERE manager_id = " . $isApproved;
                                    $approvedByUser = $con->query($getApprovedByUser)->fetch_assoc();
                                    $approvedByUser = $approvedByUser['first_name'];
                                } else if($_SESSION['role'] == 'incharge'){
                                    $isApprovedQuery = "SELECT incharge_id FROM approval_level WHERE application_id = " . $row['application_id'];
                                    $CheckIsApproved = $con->query($isApprovedQuery)->fetch_assoc();
                                    $isApproved = $CheckIsApproved['incharge_id'];
                                    $getApprovedByUser = "SELECT first_name FROM managers WHERE manager_id = " . $isApproved;
                                    $approvedByUser = $con->query($getApprovedByUser)->fetch_assoc();
                                    $approvedByUser = $approvedByUser['first_name'];
                                }
                                
                                if($isApproved){
                                    echo "<td class='text-success fw-bold'>Approved by $approvedByUser</td>";
                                } else {
                                    echo "<td class='row'><button title='edit details' class='col btn btn-outline-warning m-1 edit-application'><i class='fa fa-edit'></i></button>";
                                    echo "<button title='Approve' class='col btn btn-outline-success m-1 approve-btn' data-toggle='modal' data-target='#confirmationModal'><i class='fa fa-check'></i></button>";
                                    echo "<button title='Reject' class='col btn btn-danger m-1 reject-btn' data-toggle='modal' data-target='#rejectModal' data-id='" . $row["application_id"] . "'><i class='fa fa-xmark'></i></button></td>";
                                }
                            } else {
                                echo "<td class='text-danger fw-bold' onClick=\"alert('Reason : $isRejected \\nRejected By : $rejectedBy, $rejectedById \\nRejected At : $rejectedAt')\">Rejected</td>";
                            }
                        }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function openPrintPage(applicationId) {
            window.open('../print_pass.php?id=' + applicationId, '_blank');
        }
    </script>
    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Previous page button -->
            <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <!-- Page numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <!-- Next page button -->
            <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
<?php
} else {
    echo "<p>No applications found</p>";
}
?>
