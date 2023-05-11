<?php
include_once "avoid_visit.php";
include_once 'conn.php';
//连接数据库
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $query = "UPDATE data_analyze SET level=".$_REQUEST["level"].", excomm='".mysqli_real_escape_string($con,$_REQUEST["excomm"])."' WHERE id=".$_REQUEST["alter-with"];
    $res = mysqli_query($con,$query);
    makelog("更改了ID为".$_REQUEST["alter-with"]."的数据等级为".$_REQUEST["level"]."，简介为 ".$_REQUEST["excomm"]);
    header("Location: index.php");
}
?>