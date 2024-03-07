<?php

$inc_path = __DIR__ . "/inc/";
include_once($inc_path . "_config.php");
include_once($inc_path . "_web_func.php");

$cache_path = __DIR__ . '/' . $cache_path_web;
include_once($inc_path . "_cache.php");

?>
