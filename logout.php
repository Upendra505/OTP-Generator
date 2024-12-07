<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['email']);
    header('location:signin.php');
} else {
    header('location:signin.php');
}
?>