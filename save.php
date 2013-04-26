<?php
session_start();
include_once "mysql_config.php";
//$data=str_replace('"','\"',trim($_POST["data"]));
//$data=str_replace('"','\"',$_POST["data"]);
$data=rawurldecode($_POST["data"]);
//var_dump($_POST);
mysql_connect('localhost',$mysql_user,$mysql_pass);
mysql_select_db('itztag');
mysql_query('UPDATE sessions SET Data="'.$data.'" WHERE Name="'.$_SESSION["name"].'" AND Password="'.md5($_SESSION["pass"]).'";');
echo 'UPDATE sessions SET Data="'.$data.'" WHERE Name="'.$_SESSION["name"].'" AND Password="'.md5($_SESSION["pass"]).'";';
$_SESSION["data"]=$data;
?>