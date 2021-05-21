<?php
session_start();
$status = isset($_POST['status']) ? $_POST['status'] : "NO";
$mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : "";
$forward = isset($_POST['forward']) ? $_POST['forward'] : "";

$link = mysql_connect('dbmsql.transportespitic.com','feria','bodycombat') or die(mysql_error());

if($link){
	mysql_select_db('firewall', $link)or die(mysql_error());
	$result = mysql_query("SELECT COUNT(username) AS numero FROM correo_vacaciones WHERE username = '".$_SESSION['usuario']."'");
	$row = mysql_fetch_object($result);
	
	if($row->numero > 0){
		$query = "UPDATE correo_vacaciones SET ".
		"status = '".$status."', ".
		"mensaje = '".addslashes($mensaje)."', ".
		"forward = '".$forward."' WHERE username = '".$_SESSION['usuario']."'";
	}else{
		$query = "INSERT INTO correo_vacaciones VALUES('".$_SESSION['usuario']."','".$status."','".$mensaje."','".$forward."')";
	}
	
	$query;
	$rs = mysql_query($query) or die(mysql_error());
	if($rs){
		echo "OK";
	}else{
		echo "Hubo errores no se actualizo el status";
	}
}
?>