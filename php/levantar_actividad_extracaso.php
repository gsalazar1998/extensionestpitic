<?php require_once("MySQL/ErrorManager.class.php"); ?>
<?php require_once("MySQL/MySQL.class.php"); ?>
<?php require_once("phpmailer/class.phpmailer.php"); ?>
<?php require_once("funciones_generales.php"); ?>
<?php
	$mysql = new MySQL();
	$mysql->connect();
	
	/*** obtenemos el numero del ultimo caso dadod e alta ***/
	$q = "SELECT max(ID_ACTIVIDAD) as ID_ACTIVIDAD FROM act_extra_casos";
	$res = $mysql->query($q);
	$row = mysql_fetch_object($res);
	$idcaso = $row->ID_ACTIVIDAD;
	
	$query = "INSERT INTO act_extra_casos VALUES(
		NULL, 
		'".trim(str_replace("'", "\'", addslashes($_POST['problema'])))."',
		'".$_POST['usuario']."',
		'".$_POST['nombre']."',
		'".$_POST['oficinas']."',
		'".$_POST['atendio']."',
		NOW(),
		NULL,
		NULL
	)";
	
	$result = $mysql->query($query);
	if($result){
		$titulo = "Se ha registrado la actividad";
		$cuerpo = $_POST['atendio']." registro una nueva actividad extra caso. Aqui su descripcion:<br /><br />".$_POST['problema'];
		$objFN = new FuncionesGrales();
		$objFN->enviarMail($titulo, $cuerpo, "cmburboa"); //CORREO SOPORTE
	
		echo "OK";
	}
?>
