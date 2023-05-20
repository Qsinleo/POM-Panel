<?php
include_once "avoid_visit.php";
include_once "log.php";
include_once "config.php";

//连接数据库
try {
    $con = mysqli_connect($dtbs_name,$dtbs_usrname,$dtbs_pswd,$dtbs_dtbs);
} catch (\Throwable $th) {
    makelog("连接数据库失败(".$th->getCode().")".$th->getMessage(),"ERROR","MYSQL");
    die("连接数据库失败。请检查您的配置，再试！（详细的错误信息，请于日志内查看。）");
}
makelog("成功连接了数据库。",part:"MYSQL");
?>
