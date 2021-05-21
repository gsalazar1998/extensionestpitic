<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
require_once("phpmailer/class.phpmailer.php");
require_once("funciones_generales.php");

function enviarMail($idCaso, $usuario, $cod, $idcaso){
	$mail = new phpmailer();
	$mail->PluginDir = "";
	$mail->Mailer = "smtp";
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPSecure = "ssl";
	$mail->SMTPAuth = true;
	$mail->Port = 465;

	//Le decimos cual es nuestro nombre de usuario y password
	$mail->Username = "helpdesk@tpitic.com.mx"; 
	$mail->Password = "tp1t1c123";

	//Indicamos cual es nuestra dirección de correo y el nombre que 
	//queremos que vea el usuario que lee nuestro correo
	$mail->From = "sistemas@tpitic.com.mx";
	$mail->FromName = "HelpDesk del Departamento de Sistemas";

	//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
	//una cuenta gratuita, por tanto lo pongo a 30  
	$mail->Timeout=30;

	//Indicamos cual es la dirección de destino del correo
	$mail->AddAddress($usuario."@tpitic.com.mx");
	//$mail->AddAddress("acota@tpitic.com.mx"); //CORREO DE ALEX

	//Asignamos asunto y cuerpo del mensaje
	//El cuerpo del mensaje lo ponemos en formato html, haciendo 
	//que se vea en negrita
	$mail->Subject = "Solicitud de autorizacion del caso ".$idCaso;
	$mail->Body = "
	<style>
	body{
		font-family: Tahoma, Verdana, Arial;
		font-size: 15px;
	}
	table {
		font-family: Tahoma, Verdana, Arial;
		font-size: 15px;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		border-left: 1px solid black;
		border-right: 1px solid black;
	}
	</style>
	Se ha solicitado su autorización para el siguiente caso:<br /><br />
	".obtenerInfoCaso($idCaso)."<br /><br />
	
	<span style='color:red;'><bold>SI TIENE ALGUNA DUDA O DESEA REALIZAR UN COMENTARIO PUEDE AGREGARLO <a href='http://sistemas.tpitic.com.mx/HelpDesk/php/index_m_caso_auto.php?cod=".$cod."&idcaso=".$idcaso."&uid=".$usuario."'>Aqu&iacute</a></bold></span><br /><br /><br />
	
	<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idcaso."&uid=".$usuario."&aut=1'><img src='http://sistemas.tpitic.com.mx/HelpDesk/img/autorizar_btn.png' border='0' /></a>&nbsp;&nbsp;&nbsp;
	
	<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idcaso."&uid=".$usuario."&aut=0'><img src='http://sistemas.tpitic.com.mx/HelpDesk/img/rechazar.png' border='0'/></a><br /><br />
	
	
	Si no puede ver los botonoes para <span style='color:green;'>AUTORIZAR</span> de click en la siguiente liga:<br />
	<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idcaso."&uid=".$usuario."&aut=1'>AUTORIZAR</a><br /><br />
	
	para <span style='color:red;'>NEGAR</span> de click esta otra liga:<br />
	<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idcaso."&uid=".$usuario."&aut=0'>NEGAR</a><br /><br />
	<span style='color:red'><strong>FAVOR DE NO RESPONDER A ESTE MENSAJE!!!</strong></span>";

	//Definimos AltBody por si el destinatario del correo no admite email con formato html 
	$mail->AltBody = "Por favor utilize un lector de correo que soporte HTML";
	$exito = $mail->Send();
	if(!$exito){
		$errores .= "<pre>Problemas enviando correo electrónico a ".$usuario."<br />";
		$errores .= $mail->ErrorInfo ."</pre>";	
	}else{

	} 
}

function obtenerInfoCaso($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$row = mysql_fetch_array($result);
		$info = "<table border='0' style='width:100%'>
		<tr bgcolor='#81BEF7'><td class='tableLabels'>CASO No.</td><td class='tableLabels'>FECHA:</td><td class='tableLabels'>HORA</td></tr>
		<tr><td>".$row['ID_CASO']."</td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr bgcolor='#81BEF7'><td class='tableLabels'>ABIERTO POR: </td><td class='tableLabels'>OFICINA</td><td class='tableLabels'>DEPTO.</td></tr>
		<tr><td>".$row['NOMBRE']." (".$row['USUARIO'].")</td><td>".$row['OFICINA']."</td><td>".$row['DEPTO']."</td>
		<tr bgcolor='#81BEF7'><td colspan='3' class='tableLabels'>PROBLEMA: </td></tr>
		<tr><td colspan='3'>".$row['PROBLEMA']."</td></tr>
		<tr bgcolor='#81BEF7'><td colspan='3' class='tableLabels'>DESCRIPCION: </td></tr>
		<tr><td colspan='3'>".$row['DESCRIPCION']."</td></tr>
		</table>";
	}
	
	return $info;
}

function generarCodigoAutorizacion($tamano) {
    $permitidos = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
    $i = 0;
    $codigo = "";
    while ($i < $tamano) {
        $codigo .= $permitidos{mt_rand(0,strlen($permitidos)-1)};
        $i++;
    }
    return $codigo;
}

function tieneSolicitudPendiente($idcaso, $usuario){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT COUNT(*) AS SOL_ENVIADAS FROM casos_autorizaciones WHERE AUTORIZADO_POR = '".$usuario."' AND ID_CASO = ".$idcaso;
	$result = $mysql->query($query);
	$row = mysql_fetch_array($result);
	$sol_enviadas = $row['SOL_ENVIADAS'];
	return $sol_enviadas;
}

function insertaAutorizacion($idCaso, $autorizado_por){
	$mysql = new MySQL();
	$mysql->connect(); 
	$cod = generarCodigoAutorizacion(15);
	$query = "INSERT INTO casos_autorizaciones VALUES('".$idCaso."', NOW(), '".$autorizado_por."', '".$cod."', NULL)";
	$result = $mysql->query($query);
	if($result){
		$qry = "UPDATE casos SET ESTADO_AUTORIZACION = 'ESPERANDO' WHERE ID_CASO = '".$idCaso."'";
		$rs = mysql_query($qry);
		enviarMail($_POST['id_caso'], $autorizado_por, $cod, $idCaso);
	}
}

$objFN = new FuncionesGrales();
$mysql = new MySQL();

$mysql->connect();
//se vera si ya se marco para revision
$rev = "SELECT FEC_INICIO_REVISION FROM SISP.casos WHERE ID_CASO = '".$_POST['id_caso']."'";
$resultrev = $mysql->query($rev);
$rowrev = mysql_fetch_array($resultrev);

$solicitados = explode(";", $_POST['autorizacionde']);
$errores = "";

foreach($solicitados as $sol){
	if($objFN->existeUsuario($sol)>0 && (!is_null($rowrev['FEC_INICIO_REVISION']))){
		$num_solicitudes = tieneSolicitudPendiente($_POST['id_caso'], $sol);
		if($num_solicitudes<1){
			insertaAutorizacion($_POST['id_caso'],$sol);
		}else{
			echo "<script>alert('Este caso ya tiene ".$num_solicitudes." solicitud(es) de autorizacion, se reenviara el correo')</script>";
			$query = "SELECT * FROM casos_autorizaciones WHERE ID_CASO = '".$_POST['id_caso']."'";
			$mysql->connect();
			$result = $mysql->query($query);
			$num = mysql_num_rows($result);
			if($num > 0){
				$row = mysql_fetch_array($result);
				enviarMail($_POST['id_caso'], $row['AUTORIZADO_POR'], $row['ID_SOLICITUD'], $row['ID_CASO']);
			}
		}
	}else{
		echo $errores .= "El usuario ".$sol." no existe o no se ha marcado el caso para revisi&oacute;n<br />";
	}
}
//insertaAutorizacion($_POST['id_caso'],$_POST['autorizacionde']);
?>
