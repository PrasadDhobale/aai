<?php
session_start();
require('AdminNavbar.php');
?>
<div class="container mt-5">
<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Approved
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?approved=true">Approved</a></li>
                    <li><a class="dropdown-item" href="?all=true">All</a></li>
                </ul>
            </div>

            

            <form method="get" class="d-flex">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="request" id="approved" value="approved" autocomplete="off" <?= isset($_GET['request']) && $_GET['request'] == 'approved' ? 'checked' : '' ?>>
                    <label class="btn btn-outline-primary" for="approved">Approved</label>                    

                    <input type="radio" class="btn-check" name="request" id="all" value="all" autocomplete="off" <?= isset($_GET['request']) && $_GET['request'] == 'all' ? 'checked' : '' ?>>
                    <label class="btn btn-outline-primary" for="all">All</label>
                </div>
                <input type="date" name="from_date" class="form-control me-2" placeholder="From Date" value="<?= isset($_GET['from_date']) ? $_GET['from_date'] : '' ?>">
                <input type="date" name="to_date" class="form-control me-2" placeholder="To Date" value="<?= isset($_GET['to_date']) ? $_GET['to_date'] : '' ?>">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </nav>
</div>
<?php
require('../controller/pass.php');
?>