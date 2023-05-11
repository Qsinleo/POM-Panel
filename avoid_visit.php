<?php
session_start();
if (key_exists("logined",$_SESSION)){
    if (!$_SESSION["logined"]){
    header("Location: index.php");
    }
}
?>