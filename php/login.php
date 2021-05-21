<?php
require('conexionLDAP.php');

$objCon = new Conexion();

$user = isset($_POST['user']) ? $_POST['user'] : null;
$pass = isset($_POST['pass']) ? $_POST['pass'] : null;


$con = $objCon->conectarLDAP();
 

$bind=ldap_bind( $con, $objCon->dn, $objCon->password )or die("Can't bind to server.");
$srch =ldap_search($con, "dc=transportespitic,dc=com","uid=$user");
$numero=ldap_count_entries($con, $srch);
$info = ldap_get_entries($con, $srch);		   			   
if ($numero != 0){
	$loginacceso = $info[0]["accesosdered"][0];
	$passsaved = $info[0]["userpassword"][0];// asignamos el valor del password asignado en ldap a la variable $passsaved
	$logstate = validatePassword($pass,$passsaved);
	if($logstate){
		session_start();
			if($info[0]["uid"][0] == "cmburboa" || $info[0]["uid"][0] == "acota"){
				$_SESSION['usuario'] = $info[0]["uid"][0];
				$_SESSION['nivel'] = $info[0]["accesosdered"][0];
				echo $info[0]["accesosdered"][0];
			}else{
				$_SESSION['usuario'] = $info[0]["uid"][0];
				$_SESSION['nivel'] = $info[0]["accesosdered"][0];
				echo $info[0]["accesosdered"][0];
			}
	}
	else{
		echo "pincorrecto";
		//Hacer algo cuando el password no es correcto
	}			
}
else{
	//echo "{success: false, errors: { reason: 'No existe el usuario ".$user.".' }}";
	//hacer algo cuando no existe el usuario
}

function ValidatePassword($password, $hash) {
	$hash = base64_decode(substr($hash, 6));
	$original_hash = substr($hash, 0, 20);
	$salt = substr($hash, 20);
	$new_hash = mhash(MHASH_SHA1, $password . $salt);
	
 	if (strcmp($original_hash, $new_hash) == 0) {
		$status=true;
	}
	else {
		$status=false;
    }
	return $status;
}
?>
