<?php
session_start();

if(!$_SESSION['isvisitorlogin']){
    header("Location: ../login.php");
}else{
    include 'VisitorNavbar.php';
    ?>
    <div class="container shadow mt-5 p-3">
        <h1><?php echo "Welcome ".$_SESSION['visitor']['name']; ?></h1>
    </div>

    <div class="container shadow mt-5">
    <div class="row row-cols-1 row-cols-md-3 g-5">
    <div class="col">
            <div class="card shadow text-white bg-success mb-3" onclick="location.href = 'pass.php'" style="max-width: 23rem; cursor: pointer">
                <div class="card-header">Request Vistor Pass</div>
                <div class="card-body">                    
                    <h5 class="card-title">Request Pass as Visitor.</h5>
                    <p class="card-text">Click here to request visitor pass.</p>
                    <button class="btn btn-outline-light">Request Visitor Pass</button>
                </div>
            </div>
        </div>     
        <div class="col">
            <div class="card shadow text-white bg-info mb-3" id="mv" style="max-width: 23rem; cursor: pointer">
                <div class="card-header">Verification</div>
                <div class="card-body">
                    <p class="card-text">Email Verification Status.</p>                    
                    <h5 class="pb-3">Email ID : Verified</h5>
                </div>
            </div>
        </div>
    </div>
    <?php
}?>