<?php
include '_config.php';

$isLogin = $_SESSION['manage_loginuser'] ?? false;
if ($isLogin) {
    header("location: {$weburl}Web_Manage/home/");exit;
} else {
    header("location: {$weburl}Web_Manage/login.php");exit;
}
?>
