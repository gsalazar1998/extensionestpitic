<?php
$mysqli = new mysqli("tidb.tpitic.com.mx","adminusertpitic","adminusertpitic","SISP");
if(mysqli_connect_errno()){
	echo "Connect failed: ".mysqli_connect_errno();
	exit();
}

$mysqli2 = new mysqli("dbmsql.transportespitic.com","adminusertpitic","adminusertpitic","globaldb");
if(mysqli_connect_errno()){
	echo "Connect failed: ".mysqli_connect_errno();
	exit();
}

$query = "SELECT ID_CASO, USUARIO FROM SISP.casos";
$result = $mysqli->query($query)or die($mysqli->error.__LINE__);
while($obj = $result->fetch_object()){
	$query2 = "SELECT oficina FROM globaldb.only_users WHERE username = '".$obj->USUARIO."'";
	$result2 = $mysqli2->query($query2)or die($mysqli2->error.__LINE__);
	$obj2 = $result2->fetch_object();
	if(isset($obj2->oficina)){
		$query3 = "UPDATE SISP.casos SET OFICINA = '".$obj2->oficina."' WHERE ID_CASO = '".$obj->ID_CASO."' AND USUARIO = '".$obj->USUARIO."'";
		$result3 = $mysqli->query($query3)or die($mysqli->error.__LINE__);
		if($result3){
			echo "Oficina cambiada para ".$obj->USUARIO." a ".$obj2->oficina."<br />";
		}
	}
}
?>