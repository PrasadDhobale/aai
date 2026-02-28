<?php
    
    // Enter your host name, database username, password, and database name.
    // If you have not set database password on localhost then set empty.
    require 'config.php';
    $con = '';
    if(BASE_URL == 'https://aai.compwallah.com/'){
        $con = mysqli_connect("localhost","u800744451_aai","Pr@$@d@151956","u800744451_aai");
    }else{
        $con = mysqli_connect("localhost","root","","aai");
    }
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to Connect to MySQL: " . mysqli_connect_error();
    }
?>