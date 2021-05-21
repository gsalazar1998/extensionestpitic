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
			if($info[0]["uid"][0] == "acota" || $info[0]["uid"][0] == "gesquivel"){
				$_SESSION['usuario'] = $info[0]["uid"][0];
				$_SESSION['nivel'] = "8A";//$info[0]["accesosdered"][0]."A";
				echo "8A";//$info[0]["accesosdered"][0]."A";
			}else{
				if($info[0]['uid'][0] == "eresendiz" ||  
				$info[0]['uid'][0] == "emonge" || 
				$info[0]['uid'][0] == "lquijada" ||
				$info[0]['uid'][0] == "fvargas" ||
				$info[0]['uid'][0] == "gsalazar" ||
				$info[0]['uid'][0] == "zsainz" ||
				$info[0]['uid'][0] == "cprado" ||
				$info[0]['uid'][0] == "mrios" ||
				$info[0]['uid'][0] == "jfelix" ||
				$info[0]['uid'][0] == "marivera" ||
				$info[0]['uid'][0] == "vocana" ||
				$info[0]['uid'][0] == "cquintero" ||
				$info[0]['uid'][0] == "hgonzalez" ||
				$info[0]['uid'][0] == "xilma" ||
				$info[0]['uid'][0] == "mabitia" ||
				$info[0]['uid'][0] == "amoreno" ||
				$info[0]['uid'][0] == "pvasquez" ||
				$info[0]['uid'][0] == "iaguiar" ||
				$info[0]['uid'][0] == "jfavila" ||
			        $info[0]['uid'][0] == "iperez" ||	
				$info[0]['uid'][0] == "cromero"){
					$_SESSION['usuario'] = $info[0]["uid"][0];
					$_SESSION['nivel'] = "8A";//$info[0]["accesosdered"][0]."A";
					echo "8A";//$info[0]["accesosdered"][0]."A";
				}else{
					$_SESSION['usuario'] = $info[0]["uid"][0];
					$_SESSION['nivel'] = $info[0]["accesosdered"][0];
					echo $info[0]["accesosdered"][0];
				}
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
/*
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
}*/

function ValidatePassword($password, $hash) {
	//$objResponse = new xajaxResponse();
	$algoritmo = substr($hash, 1, 3);
//echo $hash;
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
