<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
require_once("phpmailer/class.phpmailer.php");
require_once("funciones_generales.php");

if(isset($_GET['idcaso'])){$id_caso = $_GET['idcaso'];}
if(isset($_GET['usuario'])){$usuario = $_GET['usuario'];}
function confirmarCierreCaso($id_caso, $usuario){
	$mysql = new MySQL();
	$mysql->connect(); 
	
	$q = "SELECT * FROM casos WHERE ID_CASO = ".$id_caso;
	$rs = $mysql->query($q);
	$row = mysql_fetch_array($rs);
	
	$query = "UPDATE casos SET ESTADO_CASO = '0', FECHA_CONFIRMACION = NOW() WHERE ID_CASO = ".$id_caso;
	$result = $mysql->query($query);
	if($result){
		$titulo = "Confirmacion de el cierre del caso ".$id_caso." \"".$row['PROBLEMA']."\"";
		$cuerpo = $usuario." ha confirmado el cierre del caso ".$id_caso."<br />Problema: \"".$row['PROBLEMA']."\"";
		$objFN = new FuncionesGrales();
		$tipo = "cierreCaso";
		$objFN->enviarMail($titulo, $cuerpo, $row['ASIGNADO_A'], $tipo);
		
		//creamos objeto de la clase Funciones generales y accedemos a la funcion borrar archivos
		//pasandole como parametro la cadena con los archivos que tiene registrados este caso
		$objFN = new FuncionesGrales();
		$objFN->borrarArchivos($row['ARCHIVOS']);
		//borramos el registro de los archivos en la base de datos "ejmplo.jpg,asd.doc,etc.ppt"
		$qry = "UPDATE casos SET ARCHIVOS = NULL WHERE ID_CASO = ".$id_caso;
		$rs = $mysql->query($qry);
		
		echo "OK";
	}
}

function reAbrirCaso($id_caso, $usuario){
	$mysql = new MySQL();
	$mysql->connect(); 

	$q = "SELECT * FROM casos WHERE ID_CASO = ".$id_caso." AND ESTADO_CASO = 2";
	$rs = $mysql->query($q);
	$row = mysql_fetch_array($rs);
	
	$query = "UPDATE casos SET ESTADO_CASO = '1', FECHA_CIERRE = NULL WHERE ID_CASO = ".$id_caso;
	$result = $mysql->query($query);
	if($result){
		$titulo = "Reapertura del caso ".$id_caso." \"".$row['PROBLEMA']."\"";
		$cuerpo = $usuario." ha reabierto el caso ".$id_caso."<br />Problema: \"".$row['PROBLEMA']."\"";
		$objFN = new FuncionesGrales();
		$objFN->enviarMail($titulo, $cuerpo, $row['ASIGNADO_A']);
		echo "OK";
	}
}

if(isset($_POST['ain'])){
	if($_POST['ain'] == "cl"){
		confirmarCierreCaso($_POST['id_caso'], $_POST['user']);
	}elseif($_POST['ain'] == "ro"){
		reAbrirCaso($_POST['id_caso'], $_POST['user']);
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />		
		<title>Caso Autorizado</title>
		<!--JQuery y JQueryUI -->
		<link type="text/css" href="../js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="../js/jqueryui/js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="../js/jqueryui/js/jquery-ui-1.8.11.custom.min.js"></script>
		
		<script>
		$(function() {
			<?php
			if(!isset($_POST['ain'])){
				echo "
				$('#msgDialog').html(\"Ya se ha cerrado este caso. Si realmente ya quedo corregido su problema de click en 'Confirmar' si no es asi vuelva a abrir el caso en 'Reabrir'\")
				$('#msgDialog').dialog({
					autoOpen: true,
					width: 400,
					draggable: true,
					resizeable: false,
					buttons:{
						'Confirmar': function(){
							$.ajax({
								url:'ver_cierra_caso.php',
								type: 'POST',
								data: 'ain=cl&id_caso=".$id_caso."&user=".$usuario."',
								success:function(data){
									$('#msgDialog2').html('Caso cerrado definitivamente!');
									$('#msgDialog2').dialog('open');
								}
							});
							$(this).dialog('close');
						},
						'Reabrir': function(){
							$.ajax({
								url:'ver_cierra_caso.php',
								type: 'POST',
								data: 'ain=ro&id_caso=".$id_caso."&user=".$usuario."',
								success:function(){
									$('#msgDialog2').html('Caso Reabierto!');
									$('#msgDialog2').dialog('open')
								}
							});
							$(this).dialog('close');
						}
					}
				});";
			}
			?>
			$('#msgDialog2').dialog({
				autoOpen: false,
				width: 400,
				draggable: true,
				resizeable: false,
				buttons:{
					'Aceptar': function(){	
						$(this).dialog('close');
					}
				},
			});
		});
	</script>
	<style type="text/css" title="currentStyle">		
			.fuente{ font: 70.5% "Trebuchet MS", sans-serif; margin: 5px;}
			.demoHeaders { margin-top: 2em; }
			.sombras {
				background:#f9f9f9;
				border: solid 1px #CCC;
				padding:4px 2px;
				font-family: 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif; 
				color:#333;
				font-size:12px;
				-moz-border-radius: 3px;
				-khtml-border-radius: 3px;
				-webkit-border-radius: 3px;
				box-shadow:inset 2px 2px 2px #CCC;
				-moz-box-shadow:inset 2px 2px 2px #CCC;
				-webkit-box-shadow:inset 2px 2px 2px #CCC;
			}
		</style>
	</head>
	<body class='fuente'>
	<div id="msgDialog" title="Autorizacion de casos"></div><!--Ventana de Dialogos -->
	<div id="msgDialog2" title="Clausura de casos"></div><!--Ventana de Dialogos -->
	</body>
</html>
