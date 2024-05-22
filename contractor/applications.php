<?php
include('ContractorNavbar.php');

require_once('../controller/passOperations.php');

// Assuming $contract_id is defined elsewhere
$contract_id = $_SESSION['contractor']['contract_id']; // Example contract_id

// Pagination settings
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 10;

// Fetch pass details based on contract_id
$passDetails = fetchPassDetailsByContractId($contract_id, $page, $recordsPerPage);

// Total number of pass records
$totalRecords = getTotalPassRecords($contract_id);

// Calculate total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);
?>

<!-- Display pass details in a Bootstrap table -->
<div class="w-100">
    <h2>All Applications Details</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pass ID</th>
                    <th>Name</th>
                    <th>SDW</th>
                    <th>Designation</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Company ID</th>
                    <th>Identity</th>
                    <th>Purpose of Visit</th>
                    <th>From Timestamp</th>
                    <th>To Timestamp</th>
                    <th>Police Clearance</th>
                    <th>Document Number</th>
                    <th>Issue Date</th>
                    <th>Area of Visit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passDetails as $pass): ?>
                <tr>
                    <td><?php echo $pass['application_id']; ?></td>
                    <td><?php echo $pass['name']; ?></td>
                    <td><?php echo $pass['sdw']; ?></td>
                    <td><?php echo $pass['designation']; ?></td>
                    <td><?php echo $pass['phone']; ?></td>
                    <td><?php echo $pass['address']; ?></td>
                    <td><?php echo $pass['company_id']; ?></td>
                    <td><?php echo $pass['identity']; ?></td>
                    <td><?php echo $pass['purpose_of_visit']; ?></td>
                    <td><?php echo $pass['from_timestamp']; ?></td>
                    <td><?php echo $pass['to_timestamp']; ?></td>
                    <td><?php echo $pass['police_clearance']; ?></td>
                    <td><?php echo $pass['document_number']; ?></td>
                    <td><?php echo $pass['issue_date']; ?></td>
                    <td><?php echo $pass['areaOfVisit']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
