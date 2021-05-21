<?php
require_once('../php/conexionLDAP.php');
require_once('../php/funciones_generales.php');
$objFN = new FuncionesGrales();
$objConLDAP = new Conexion();

$con = $objConLDAP->conectarLDAP();
$filter = "(voiceip=*)";
$srch=ldap_search($con, "ou=People,dc=transportespitic,dc=com",$filter);
$numero=ldap_count_entries($con, $srch);
$info = ldap_get_entries($con, $srch);
$count = ldap_count_entries($con, $srch);
for($i=0; $i<$count; $i++){
	if($info[$i]['voiceip'][0] != 'NO'){
		//echo $info[$i]['uid'][0]." ".$info[$i]['voiceip'][0]." | ".$info[$i]['extension'][0]."<br />";
		$dn = "extensiontelefono=".$info[$i]['extension'][0].",ou=Telefonos,ou=groups,dc=transportespitic,dc=com";
		$update['iptelefono'] = $info[$i]['voiceip'][0];
		$update['mactelefono'] = $info[$i]['voicemac'][0];
		$mod = ldap_modify($con, $dn, $update);
		if($mod){
			echo "usuario ".$info[$i]['uid'][0]." cambiado<br />";
		}
	}
}
?>