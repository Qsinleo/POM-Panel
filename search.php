<?php
include_once "avoid_visit.php";
include_once 'conn.php';
{
    $query = "SELECT * FROM `data_main`,`data_count`,`data_analyze` WHERE data_main.id = data_count.id and data_main.id = data_analyze.id";
}
$res = mysqli_query($con,$query);
$posts = mysqli_fetch_all($res,MYSQLI_ASSOC);
makelog("查询了语句".$query);
?>