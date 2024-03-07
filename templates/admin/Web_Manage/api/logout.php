<?php
include('_config.php');

coderAdmin::loginOut();

header("Location: {$weburl}Web_Manage/login.php");
?>
