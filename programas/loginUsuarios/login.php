<?php
//=============================================//
//PHP DE LOGIN PARA CUALQUIER USUARIO 
//se establecen las variables de SESION
//nivel : que es el nivel de acceso de usuario
//usuario : es el usuario que se logueo
//=============================================//
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
		if($info[0]["uid"][0] == "acota" || $info[0]["uid"][0] == "cmburboa"){
			$_SESSION['usuario'] = $info[0]["uid"][0];
			$_SESSION['nivel'] = $info[0]["accesosdered"][0]."A";
			echo "OK";
		}else{
			$_SESSION['usuario'] = $info[0]["uid"][0];
			$_SESSION['nivel'] = $info[0]["accesosdered"][0];
			echo "OK";
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
	//$objResponse = new xajaxResponse();
	$algoritmo = substr($hash, 1, 3);
	if($algoritmo == 'SSH'){
		$hash = base64_decode(substr($hash, 6));
		$original_hash = substr($hash, 0, 20);
		$salt = substr($hash, 20);
		$new_hash = mhash(MHASH_SHA1, $password . $salt);
	//echo strlen($new_hash);
	//echo $new_hash = substr(hash('sha1', $password), 0, 20).$salt;
	//echo $new_hash;
	//echo strlen($new_hash);
 	if (strcmp($original_hash, $new_hash) == 0) {
		//echo "LOGUEADO";
		//$objResponse->alert("Logueado");
  		//header("Location: index.php");
		$status=true;
		
	} else {
		//echo "PASSWORD INCORRECTO";
		//$objResponse->alert("No Logueado");
  		//header("Location: algoqenoexista.php");
	//return $objResponse;
		$status=false;
       }
	   
	}else{
		$newPass = "{SHA}".base64_encode(sha1($password, TRUE));
		if(strcmp($hash, $newPass) == 0){
			$status = true;
		}else{
			$status = false;
		}
	}
	return $status;
	
}
?>
