<?php
include_once "log.php";
// 登出
session_start();
foreach ($_SESSION as $r){
    unset($r);
}
session_destroy();
makelog("已登出");
header("Location: index.php");
?>