<?php
require('conexionLDAP.php');
if(isset($_GET['noempleado'])){
	getInfoId($_GET['noempleado']);
}elseif(isset($_GET['usuario'])){
	getInfoUser($_GET['usuario']);
}

function getInfoId($noempleado){
	$objLDAP = new Conexion();
	$ds = $objLDAP->conectarLDAP();

	$r2=ldap_bind( $ds,"cn=feria,dc=transportespitic,dc=com", "sistemaspitic" )or die("Can't bind to server.");
	$dn = "dc=transportespitic,dc=com";

	$sr=ldap_search($ds, $dn, "noempleado=".$noempleado);
	$info = ldap_get_entries($ds, $sr);
	$entradas = $info["count"];

	/*print("<pre>");
	print_r($info);
	print("</pre>");*/
	if($entradas > 0){
		//$data = '{ "data": [';
		$data ='[{"campo":"nombre","value":"'.$info[0]['cn'][0].'"},{"campo":"usuario","value":"'.$info[0]['uid'][0].'"},{"campo":"noempleado","value":"'.$info[0]['noempleado'][0].'"},{"campo":"correo","value":"'.$info[0]['uid'][0].'@tpitic.com.mx"},{"campo":"oficina","value":"'.$info[0]['oficina'][0].'"}]';
		//$data .= "]}";
		echo $data;
	}else{}
}

function getInfoUser($usuario){
	$objLDAP = new Conexion();
	$ds = $objLDAP->conectarLDAP();

	$r2=ldap_bind( $ds,"cn=feria,dc=transportespitic,dc=com", "sistemaspitic" )or die("Can't bind to server.");
	$dn = "dc=transportespitic,dc=com";

	$sr=ldap_search($ds, $dn, "uid=".$usuario);
	$info = ldap_get_entries($ds, $sr);
	$entradas = $info["count"];

	/*print("<pre>");
	print_r($info);
	print("</pre>");*/
	if($entradas > 0){
		//$data = '{ "data": [';
		$data ='[{"campo":"nombre","value":"'.$info[0]['cn'][0].'"},{"campo":"usuario","value":"'.$info[0]['uid'][0].'"},{"campo":"noempleado","value":"'.$info[0]['noempleado'][0].'"},{"campo":"correo","value":"'.$info[0]['uid'][0].'@tpitic.com.mx"},{"campo":"oficina","value":"'.$info[0]['oficina'][0].'"}]';
		//$data .= "]}";
		echo $data;
	}else{}
}
?>
