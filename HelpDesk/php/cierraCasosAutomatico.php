<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
require_once("phpmailer/class.phpmailer.php");
require_once("funciones_generales.php");

$mysql = new MySQL();
$mysql->connect();

$query = "SELECT ID_CASO FROM casos WHERE DATEDIFF(CURDATE(), FECHA_CIERRE) > 3 AND ESTADO_CASO = 2 ORDER BY ID_CASO";
$rs = $mysql->query($query);
$casos = "";
while($row = mysql_fetch_object($rs)){
	$casos .= $row->ID_CASO.",";
}
$casos = substr($casos,0,-1);

$query = "UPDATE casos SET CONFIRMO = 'NO', ESTADO_CASO = 0, FECHA_CONFIRMACION = NOW() WHERE ID_CASO IN(".$casos.")";
$res = $mysql->query($query);
if($res){
	echo "CAMBIOS EFECTUADOS, CASOS NO CONFIRMADOS MAYORES A 3 DIAS SE HAN CERRADO DEFINITIVAMENTE";
}
?>
