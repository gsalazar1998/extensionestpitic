<?php require_once("MySQL/ErrorManager.class.php"); ?>
<?php require_once("MySQL/MySQL.class.php"); ?>
<?php require_once("phpmailer/class.phpmailer.php"); ?>
<?php require_once("funciones_generales.php"); ?>
<?php
	$mysql = new MySQL();
	$mysql->connect();
	
	/*** obtenemos el numero del ultimo caso dado e alta ***/
	$q = "SELECT max(ID_CASO) as ID_CASO FROM casos";
	$res = $mysql->query($q);
	$row = mysql_fetch_object($res);
	$idcaso = $row->ID_CASO;
	
	$query = "INSERT INTO casos VALUES(
		NULL, 
		'".trim(str_replace("'", "\'", addslashes($_POST['problema'])))."',
		'".trim(str_replace("'", "\'", addslashes($_POST['descripcion'])))."',
		NULL,
		'".$_POST['usuario']."',
		'".$_POST['nombre']."',
		'".$_POST['correo']."',
		'".$_POST['noempleado']."',
		'(".$_POST['lada'].")-".$_POST['numero']."-".$_POST['ext']."',
		'".$_POST['depto']."',
		'".$_POST['oficinas']."',
		'NO',
		'".$_POST['adjuntos']."',
		'1',
		NOW(),
		NULL,
		NULL,
		NULL,
		NULL,
		NULL,
		'0',
		NULL,
		NULL
	)";
	
	$result = $mysql->query($query);
	if($result){
		$titulo = "Se ha dado de alta un nuevo caso";
		$cuerpo = $_POST['nombre']." (".$_POST['usuario'].") Ha dado de alta un nuevo caso. Aqui su descripcion:<br /><br />".$_POST['descripcion'];
		$objFN = new FuncionesGrales();
		$objFN->enviarMail($titulo, $cuerpo, "support"); //CORREO SOPORTE
		
		$titulo = "Se ha registrado su caso correctamente";
		$cuerpo = "Hola ".$_POST['nombre']." (".$_POST['usuario'].").<br /><br />Ha quedado registrado su caso.<br />Num. de Caso: ".((int)$idcaso+1)."<br />Aqu&iacute; la descripci&oacute;n del problema que escribi&oacute;:<br /><br />".$_POST['descripcion'];
		$objFN = new FuncionesGrales();
		$objFN->enviarMail($titulo, $cuerpo, $_POST['usuario']); //CORREO USUARIO QUE LEVANTO EL CASO
		echo "OK";
	}
?>
