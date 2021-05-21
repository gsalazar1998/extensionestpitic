<?php
session_start();
require('passwords.class.php');

$user = $_SESSION['usuario'];
$objPass = new Passwords();
//nos conectamos a la base de datos (sin parametros se conectara a la 120.170
$link = $objPass->connectWorkDb();
$conLDAP = $objPass->connectLDAP();
//obtenemos los dias que faltan para que expire el password
$dias_expira = $objPass->getExpireDaysOf($user, $link);
//nos traemos todos los datos del usuario en un arreglo indexado llamado info del LDAP
$info = $objPass->getInfoMySQL($user, $link);
/*print("<pre>");
print_r($info);
print("</pre>");*/
//comprobamos ya pedimos los passwords (porque los cachamos aqui mismo como por ejemplo action='' de un form)
if(isset($_POST['pax1']) and isset($_POST['pax2']) and isset($_POST['paxac'])){
	//obtenemos el primer campo de password encriptado y sin espacios para eso usamos trim()
	$password = md5(trim($_POST['pax1']));
	$passsecriptar = trim($_POST['pax1']);
	//obtenemos la confirmacion del password encriptada igual quitamos los espacios con trim()
	$confirmacion = md5(trim($_POST['pax2'])); 
	//obtenemos el password actual del usuario de la base de datos users_only
	$passAnterior = $objPass->getCurrentPass($link, $user);
	//validaciones para comprobar si el password es valido
	//como por ejemplo que sea mayor de 8 caracteres, no usuario, no nombre, etc..
	$isValid = $objPass->checkValidPassword($link, $info, $password, $confirmacion, $user, $passAnterior, $passsecriptar, md5($_POST['paxac']));
	//si el password es valido
	if($isValid){
		//guardamos el nuevo password en la base de datos de users_only
		$cambiado = $objPass->saveNewPass($link, $password, $user, $passAnterior);
		$objPass->changePassLDAP($user, $passsecriptar, $conLDAP, $objPass);
	}
	//en caso de que no hayamos pedido las variables de password aun, osea que sea la primera ves que entra el usuario
	//le decimos que le faltan tantos dias para que se vensa su password y si quiere actualizarlo. si esta vencido le muestra la pagina para 
	//actualizarlo
}
?>
<html>
<head>
	<title>
	</title>
</head>
<body>
<div id='test'>
</body>
</div>
</html>