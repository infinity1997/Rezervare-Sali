<?php
session_start();
if (isset($_SESSION["username"])){
    session_unset();
    session_destroy();
    header("Location: login.php");
}
else{
    echo "You are not logged in.";
}
?>