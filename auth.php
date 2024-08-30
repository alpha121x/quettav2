<?php
session_start();
if (! $_SESSION['is_logged_in']) {
    // echo "session is started";
    header('Location:login.php');
}
?>