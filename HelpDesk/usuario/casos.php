<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!--<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />-->
		
		<title>Mostrando los casos no asignados</title>
		<style type="text/css" title="currentStyle">
			@import "../js/datatables/media/css/demo_page.css";
			@import "../js/datatables/media/css/demo_table_jui.css";
			@import "../js/datatables/css/jquery-ui-1.8.4.custom.css";
			

			.respuestas{
				background-color:#E6E6E6;
				border:1px solid blue;
				margin:10px;
			}
			
			#gallery {
				background-color: white;
				padding: 10px;
				width: 750px;
				height:150px;
				text-align: center;
			}
			#gallery ul { list-style: none; }
			#gallery ul li { display: inline; }
			#gallery ul img {
				border: 5px solid white;
				border-width: 5px 5px 20px;
			}
			#gallery ul a:hover img {
				border: 5px solid blue;
				border-width: 5px 5px 20px;
				color: blue;
			}
			#gallery ul a:hover { color: blue; }
			
			#descripcion {
				background-color: white;
				padding: 10px;
				width: 744px;;
				height:150px;;
			}
			/************************************************/
			td {padding:5px;}
			.tableLabels{
				background-color:#FAAC58;
				color:white;
			}
			thead tfoot{
				font: 12px 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif;  margin: 5px;
			}
			.fuente{ font: 12px 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif;  margin: 5px;}

			.demoHeaders { margin-top: 2em; }

			#asignacaso_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
			#asignacaso_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

			#casos_info_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
			#info_caso span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
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

		<!--JQuery y JQueryUI -->
		<link type="text/css" href="../js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="../js/jqueryui/js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="../js/jqueryui/js/jquery-ui-1.8.11.custom.min.js"></script>
		<!-- para validacion de formulario-->
		<link rel="stylesheet" href="../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
		<link rel="stylesheet" href="../js/validationEngine/css/template.css" type="text/css"/>
		<script src="../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
		<script src="../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script><!--end validacion-->
		<!--libreria para subir archivos-->
		<link href="../js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="../js/uploadify/swfobject.js"></script>
		<script type="text/javascript" src="../js/uploadify/jquery.uploadify.v2.1.4.min.js"></script><!-- end of uploadfile -->
		<!--configuraciones-->
		<script type="text/javascript" src="../js/casos_usuario.js"></script>
		<!-- DataTables -->
		<script type="text/javascript" language="javascript" src="../js/datatables/media/js/jquery.dataTables.js"></script>
		<!--<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
			});-->
		</script>
	</head>
	<body id="dt_example">
	<?php
	require_once("../php/MySQL/ErrorManager.class.php");
	require_once("../php/MySQL/MySQL.class.php");
	if(isset($_SESSION['usuario'])) {
	?><div id="cerrarSesion" style="text-align:right;"><a style="text-align:right;margin-right:80px;margin-top:10px;" href="../php/cerrarSesion.php">Cerrar Sesi&oacute;n</a></div>
		<div id="container" style="width:90%;">
			<div id="dynamic" class="fuente">
				<div id="tabs">
					<ul>
						<!--<li><a href="#tabs-1" class="fuente">Casos NO asignados</a></li>-->
						<li><a href="#tabs-2" class="fuente">Mis casos abiertos</a></li>
						<li><a href="#tabs-3" class="fuente">Mis casos sin confirmaci&oacute;n</a></li>
					</ul>
					
					<!--<div id="tabs-1" style="padding: 0 0 0 0;">
<table cellpadding="0" cellspacing="0" border="0" class="display fuente" id="cnoasignados"  >
	<thead>
		<tr>
			<th width="10%">ID</th>
			<th width="15%">Usuario</th>
			<th width="30%">Problema</th>
			<th width="5%">Oficina</th>
			<th width="10%">Depto.</th>
			<th width="20%">Fecha</th>
			<th width="20%">Hora</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		</tr>
	</tfoot>
	<tbody>
		
	</tbody>
</table>
<p><span id="link_asignar" name="link_asignar" class="fuente"></span>
				</div>termina div tabs-1-->
				<div id="tabs-2" style="padding: 0 0 0 0;">
					<table cellpadding="0" cellspacing="0" border="0" class="display fuente" id="casignados" style="width:100%;">
						<thead>
							<tr>
								<th>ID</th>
								<th>Asignado a:</th>
								<th>Problema</th>
								<th>Fecha</th>
								<th>Mensajes</th>
								<th>Estado Autorizaci&oacute;n</th>
								<th>Autorizo/Rechazo</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							</tr>
						</tfoot>
						<tbody>
		
						</tbody>
					</table>
					<p><span id="info_caso" name="link_asignar"></span>
				</div><!--termina tabs-2-->
				<div id="tabs-3" style="padding: 0 0 0 0;">
					<table cellpadding="0" cellspacing="0" border="0" class="display fuente" id="csinconfirmacion" style="width:100%;">
						<thead>
							<tr>
								<th>ID</th>
								<th>Cerrado por:</th>
								<th>Problema</th>
								<th>Fecha Cierre</th>
								<th>Hora Cierre</th>
								<th>Verificar</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							</tr>
						</tfoot>
						<tbody>
		
						</tbody>
					</table>
				</div><!-- termina tabs-3-->
				</div><!-- termina tabs-->
			</div><!-- termina dynamic-->
		</div><!-- termina container-->
<div id="msgDialog" title="Mensaje" class="fuente"></div><!--Ventana de Dialogos -->
<div id="infoCaso" title="Informacion del caso" class="fuente"></div><!-- ventana que muestra la informacion del caso-->
<div id="fotos" class="fuente"></div><!-- ventana de las fotos cuando se hacen grandes-->
<div id="respuesta_ven" title="Responder" class="fuente"></div>
	</body>
	<?php 
	}else {
		echo "<script>window.location = '../index.php'</script>";
	}
	?>
</html>
