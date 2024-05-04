<?php
session_start();

if(!$_SESSION['isvisitorlogin']){
    header("Location: ../login.php");
}else{
    echo $_SESSION['visitor']['name'];
}
?>