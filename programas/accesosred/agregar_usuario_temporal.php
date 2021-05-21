<?php
session_start();
if(!isset($_SESSION['usuario'])){
	echo "<script>window.location = 'index.php'; </script>";
}
require_once("../../php/MySQL/ErrorManager.class.php");
require_once("../../php/MySQL/MySQL.class.php"); 
require('../../php/funciones_generales.php');
$objFN = new FuncionesGrales();
$mysql = new MySQL();
$conn = $mysql->connect();
if($conn){
	$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
	$apellido = isset($_POST['apellido']) ? $_POST['apellido'] : "";
	mysql_select_db('firewall', $conn);
	$usuario_temporal = substr($nombre,0,1).$apellido;
	$password = generarPassword(8);
	$password_crypted = md5($password);
	$query = "INSERT INTO usuarios_temporales(usuario, nombre, apellido, password, ip, tiempo_permitido, hora_conexion, usuario_autoriza)";
	$query .= " VALUES('".strtolower($usuario_temporal)."', '".strtoupper($nombre)."', '".strtoupper($apellido)."', '".$password_crypted."', null, '480', null, '".$_SESSION['usuario']."')";
	$rs = mysql_query($query)or die(mysql_error());
	if($rs){
		echo '{"error":0, "msg":"USUARIO: '.$usuario_temporal.', PASSWORD: '.$password.'"}';
	}
}else{
	echo '{"error":1, "msg":"no hay conexion"}';
}

function generarPassword($tamano) {
    $permitidos = "1234567890abcdefghijklmnopqrstuvwxyz";
    $password = "";
	$i = 0;
    while ($i < $tamano) {
        $password .= $permitidos{mt_rand(0,(strlen($permitidos)-1))};
        $i++;
    }
    return $password;
}
?>