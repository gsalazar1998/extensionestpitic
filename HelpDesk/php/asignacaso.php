<?php require_once("MySQL/ErrorManager.class.php"); ?>
<?php require_once("MySQL/MySQL.class.php"); ?>
<?php require_once("funciones_generales.php"); ?>
<?php
	session_start();
	$mysql = new MySQL();
	$mysql->connect(); 
	$usuarioasig = $_POST['usuarioasig'];
	$query = "UPDATE casos set ASIGNADO_A = '".$_POST['usuarioasig']."', FEC_INICIO_REVISION = null, FECHA_ASIGNACION = NOW() WHERE ID_CASO IN(".$_POST['casos'].")";
	$result = $mysql->query($query);
	if($result){
		$titulo = "SE LE HAN ASIGNADO CASOS NUEVOS ";
		$cuerpo = "Se le han asignado los siguientes casos:<br /><br />".$_POST['casos'];
		$tipo = "asignacion";
		$objFN = new FuncionesGrales();
		$objFN->enviarMail($titulo,$cuerpo,$usuarioasig,$tipo);
		echo "OK";
		
		$casos = explode(',', $_POST['casos']);
		foreach($casos as $c){
			$query = "INSERT INTO SISP.log_casos VALUES('".$c."','ASIGNADO A ".$_POST['usuarioasig']."','".$_SESSION['usuario']."', NOW())";
			$result = $mysql->query($query);
		}
	}
?>
