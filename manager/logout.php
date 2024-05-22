<?php
    session_start();
    $_SESSION['isManagerLogin'] = false;
    echo "<script>alert('Logged Out Successfully..')</script>";
    echo "<script>location.href =  'login.php'</script>";
?>