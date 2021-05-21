<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
require_once("phpmailer/class.phpmailer.php");
require_once("funciones_generales.php");

$id_caso = $_POST['idcaso'];
$sistema = $_POST['sistema'];

$mysql = new MySQL();
$mysql->connect(); 

$query = "UPDATE SISP.casos SET FEC_INICIO_REVISION = NOW(), TIPO_PROBLEMA = '".$sistema."' WHERE ID_CASO = ".$id_caso;
$result = $mysql->query($query) or die(mysql_error());
if($result){
	echo "OK";
}
?>