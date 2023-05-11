<?php
include_once "avoid_visit.php";
date_default_timezone_set('Asia/Shanghai');
$result = fopen("run.log",'a');
function makelog($info,$type="INFO",$part="main")
{   
    global $result;
    fwrite($result,"[".date('Y-m-d h:i:s', time())."] [".$type."] [".$part."] ".$info."\n");// 写入日志
}
makelog("生成了日志！");
?>