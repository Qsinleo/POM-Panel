<?php
include_once "avoid_visit.php";
include_once "log.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    fopen("run.log","w");
    makelog("已清除日志","RESTART");
    header("Location: analyze.php");
}
?>