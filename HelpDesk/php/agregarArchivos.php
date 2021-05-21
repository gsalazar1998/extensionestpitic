<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php"); 
$mysql = new MySQL();
$mysql->connect(); 

$query = "SELECT ARCHIVOS FROM casos WHERE ID_CASO = ".$_POST['idc'];
$rs = $mysql->query($query);
$row = mysql_fetch_array($rs);
if($row['ARCHIVOS'] != "" && $row['ARCHIVOS'] != null){
	$nuevos = $row['ARCHIVOS'].",".$_POST['agregar'];
}else{
	$nuevos = $_POST['agregar'];
}

$query = "UPDATE casos SET ARCHIVOS = '".$nuevos."' WHERE ID_CASO = ".$_POST['idc'];
$rs = $mysql->query($query);
if($rs){
	echo "OK";
}


?>
