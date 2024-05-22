<?php


require('../navbar.php');
// Fetch the application_id from the URL

if(isset($_GET['id'])){
    $application_id = $_GET['id'];
    $checkPassStatusQuery = "select * from approval_level where application_id = $application_id";
    $application = $con->query($checkPassStatusQuery)->fetch_assoc();
    if(isset($application['application_id'])){

        $checkApplyTime = "select apply_time from pass_applications where application_id = $application_id";
        $applyTime = $con->query($checkApplyTime)->fetch_assoc();
        
        $userApplyTime = $applyTime['apply_time'];
        $contractorApproveTime = $application['contractor_approve_time'];
        $managerApproveTime = $application['manager_approve_time'];
        $clerkApproveTime = $application['clerk_approve_time'];
        $csoApproveTime = $application['incharge_approve_time'];

        $contractorApproved = isset($contractorApproveTime);
        $managerApproved = isset($managerApproveTime);
        $clerkApproved = isset($clerkApproveTime);
        $csoApproved = isset($csoApproveTime);

        // Function to calculate time difference in hours and minutes
        function timeDifference($start, $end) {
            $startTimestamp = strtotime($start);
            $endTimestamp = strtotime($end);
            $diff = $endTimestamp - $startTimestamp;
            
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            
            return "{$hours}h {$minutes}m";
        }

        $userToContractor = isset($userApplyTime) && isset($contractorApproveTime) ? timeDifference($userApplyTime, $contractorApproveTime) : '00';
        $contractorToManager = isset($contractorApproveTime) && isset($managerApproveTime) ? timeDifference($contractorApproveTime, $managerApproveTime) : '00';
        $managerToClerk = isset($managerApproveTime) && isset($clerkApproveTime) ? timeDifference($managerApproveTime, $clerkApproveTime) : '00';
        $clerkToCso = isset($clerkApproveTime) && isset($csoApproveTime) ? timeDifference($clerkApproveTime, $csoApproveTime) : '00';

        ?>
            <style>
                .timeline {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    position: relative;
                    margin-bottom: 2rem;
                }
                .timeline-step {
                    position: relative;
                    flex: 1;
                    text-align: center;
                    padding: 1rem;
                }
                
                .timeline-step p {
                    margin-top: 1rem;
                    font-size: 0.8rem;
                }
                .timeline-step:not(:last-child)::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 10px;
                    height: 10px;
                    background-color: #fff;
                    border: 2px solid #ccc;
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 1;
                }
                .timeline-step.approved::before {
                    background-color: green;
                }
                .timeline-step.not-approved::before {
                    background-color: red;
                }
                .timeline-step p.time {
                    font-size: 0.8rem;
                    color: #999;
                }
                @media (min-width: 767px) {
                    .timeline-step {
                        position: relative;
                        flex: 1;
                        text-align: center;
                    }
                    .timeline-step::before {
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 0;
                        width: 100%;
                        height: 2px;
                        background-color: #ccc;
                        z-index: -1;
                    }
                }
                @media (max-width: 767px) {
                    .timeline {
                        flex-direction: column;
                        align-items: stretch;
                    }
                    .timeline-step {
                        flex: none;
                        width: auto;
                        text-align: left;
                        margin-top: 10px;
                    }
                    .timeline-step::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        bottom: 0;
                        left: 90%;
                        width: 2px;
                        background-color: #ccc;
                        z-index: -1;
                    }
                    .timeline-step:not(:last-child)::after {
                        left: 90%;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container mt-5">
                <h2 class="mb-4 text-center">Track Application</h2>
                <?php
                if($application['reason']){
                    ?>
                    <div class="container">
                        <div class="text-center">
                            <p class="fw-bold">This Application Is Rejected. Below are the Rejection Details</p>
                            <table class="table table-stripped-column">
                                <tr>
                                    <th>Reason</th>
                                    <td><?php echo $application['reason']; ?></td>
                                </tr>
                                <tr>
                                    <th>Rejected By</th>
                                    <td><?php echo $application['rejected_by_role']; ?></td>
                                </tr>
                                <tr>
                                    <th>Rejected At</th>
                                    <td><?php echo $application['rejected_at']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="timeline">
                    <div class="timeline-step approved">
                        <p class="time"><?php echo $userApplyTime; ?></p>
                        <p>00:00</p>
                        <div class="icon text-primary"><i class="fas fa-user-tie"></i></div>
                        <p>User</p>
                    </div>
                    <div class="timeline-step <?php echo $contractorApproved ? 'approved' : 'not-approved'; ?>">
                        <p class="time"><?php echo $contractorApproveTime; ?></p>
                        <p><?php echo $userToContractor; ?></p>
                        <div class="icon text-primary"><i class="fas fa-user-tie"></i></div>
                        <p>Contractor</p>
                    </div>
                    <div class="timeline-step <?php echo $managerApproved ? 'approved' : 'not-approved'; ?>">
                        <p class="time"><?php echo $managerApproveTime; ?></p>
                        <p><?php echo $contractorToManager; ?></p>
                        <div class="icon text-primary"><i class="fas fa-briefcase"></i></div>
                        <p>Dept Manager</p>
                    </div>
                    <div class="timeline-step <?php echo $clerkApproved ? 'approved' : 'not-approved'; ?>">
                        <p class="time"><?php echo $clerkApproveTime; ?></p>
                        <p><?php echo $managerToClerk; ?></p>
                        <div class="icon text-primary"><i class="fas fa-user-cog"></i></div>
                        <p>Clerk</p>
                    </div>
                    <div class="timeline-step <?php echo $csoApproved ? 'approved' : 'not-approved'; ?>">
                        <p class="time"><?php echo $csoApproveTime; ?></p>
                        <p><?php echo $clerkToCso; ?></p>
                        <div class="icon text-primary"><i class="fas fa-user-shield"></i></div>
                        <p>CSO / Terminal</p>
                    </div>
                    <div class="timeline-step <?php echo $csoApproved ? 'approved' : 'not-approved'; ?>"></div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>            
    <?php 
    }else{
        echo "Application ID Not Found";
    }
}else{
    ?>
    <div class="container mt-5 shadow p-5">
        <form class="row row-cols-lg-auto g-3 align-items-center" action="" method="get" target="__blank">
            <div class="col-12">
                <div class="input-group">
                    <input type="number" class="form-control" name="id" id="applicationId" placeholder="Application ID">
                </div>            
            </div>    
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Check Status</button>
            </div>
        </form>
    </div>
    <?php
}
?>