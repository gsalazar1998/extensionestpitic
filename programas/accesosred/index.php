<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
session_start();
if(isset($_SESSION['usuario'])){
	echo "<script>window.location = 'crear_usuario_temporal.php'; </script>";
}

require_once("../../php/MySQL/ErrorManager.class.php");
require_once("../../php/MySQL/MySQL.class.php"); 
require('../../php/funciones_generales.php');
$objFN = new FuncionesGrales();
$mysql = new MySQL();
$mysql->connect();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Soporte - Transportes Pitic</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../../css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuerty UI -->
	<script type="text/javascript" src="../../js/jquery-1.5.1.min.js"></script>
	<link type="text/css" href="../../js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="../../js/jqueryui/jquery-ui-1.8.11.custom.min.js"></script>
<!-- Libreria para subir archivos UPLOADIFY-->
	<link href="../../js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="../../js/uploadify/swfobject.js"></script>
	<script type="text/javascript" src="../../js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<!-- para validacion de formulario-->
	<link rel="stylesheet" href="../../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="../../js/validationEngine/css/template.css" type="text/css"/>
	<script src="../../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
	<script src="../../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Ventanas Modales OSX-->
	<link href="../../css/osx.css" rel="stylesheet" type="text/css" media="screen" />
	<script type='text/javascript' src='../js/osx.js'></script>
	<script type='text/javascript' src='../../js/jquery.simplemodal.js'></script>
<!--configuraciones-->
	<script type="text/javascript" src="login.js"></script>
<!-- DataTables -->
	<script type="text/javascript" language="javascript" src="../js/datatables/media/js/jquery.dataTables.js"></script>
	<link type="text/css" href="../js/datatables/media/css/demo_page.css" rel="stylesheet" />
	<link type="text/css" href="../js/datatables/media/css/demo_table_jui.css" rel="stylesheet" />	
	<!--<link type="text/css" href="../js/datatables/css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />-->
	
<script type="text/javascript">
function mainmenu(){
	// Oculto los submenus
	$(" #nav ul ").css({display: "none"});
	// Defino que submenus deben estar visibles cuando se pasa el mouse por encima
	$(" #nav li").hover(function(){
		$(this).find('ul:first:hidden').css({visibility: "visible",display: "none"}).slideDown(400);
		},function(){
			$(this).find('ul:first').slideUp(400);
	});
}
$(document).ready(function(){
    mainmenu();
});
</script>
<script type="text/javascript">
$(function(){});
</script>
<style>
<style type="text/css" title="currentStyle">
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
	width: 560px;;
	height:150px;;
}
/************************************************/
.tInfoCasos{padding:5px;}
.tableLabels{
	background-color:#FAAC58;
	color:white;
}

.demoHeaders { margin-top: 2em; }

#asignacaso_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#asignacaso_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

#casos_info_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#info_caso span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

#casos_info_linkPM {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#info_casoPM span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

ul#icons {margin: 0; padding: 0;}
ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
ul#icons span.ui-icon {float: left; margin: 0 4px;}
</style>
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<img src="../../images/logo.png" width="310px;" />
			</div>
			<div id="menu">
				<ul id="nav">
					<li><a href="../../index.php">INICIO</a></li>
					<li><a href="../../HelpDesk/index.php">HELPDESK</a>
					</li>
					<li><a href="#" id="utilidades" ><span>UTILIDADES</span></a>
						<ul class="submenu">
							<li><a id="as" href="#">Admin. Sistemas</a>
								<ul class="subsubmenu">
									<li><a href="../../appsadmin.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">T&eacute;cnicos</a>
								<ul class="subsubmenu">
									<li><a href="../../appstecnicos.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">Usuarios</a>
								<ul class="subsubmenu">
									<li><a href="../../appsusuario.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#" id="programas" style="color:orange;"><span>PROGRAMAS</span>
						<ul class="submenu">
							<li><a id="fin" href="#">Finanzas</a>
								<ul class="subsubmenu">
									<li><a href="../programas/finanzas/facturas/subirfactura.php">Subir Factura</a></li>
								</ul>
							</li>
							<li><a id="grales" href="#">Generales</a>
								<ul class="subsubmenu">
									<li><a href="#">Configurar Vacaciones</a></li>
									<li><a href="../passwords2/index.php">Cambiar Password</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#">VIDEOS Y AVISOS</a></li>
				</ul>
			</div>
		</div>
		<!-- end #header -->
		<div id="page">
			<div id="content">
				<div class="post">
					<h2>Crear usuario temporal para acceso a la red</h2>
					<!--Div que mostrara un error en caso de haberlo-->
					<center>
					<div id="errorLogin">Por favor ingrese su usuario y contrase&ntilde;a para continuar</div><br />
					<div id="loginForm">
						<form id="login">
						<center><table class="fuenteTable">
							<tr><td><label for="user" style="color:#6B6B6B;font-weight:bold;">Usuario</label></td><td><input type="text" id="user" name="user" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif"/></td></tr>
							<tr><td><label for="pass" style="color:#6B6B6B;font-weight:bold;">Password</label></td><td><input type="password" id="pass" name="pass" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif"/></td></tr>
							<tr><td colspan=2 align="right"><input type="submit"  id="sub" class="ui-state-default ui-corner-all" value="Entrar" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif"></td></tr>
						</table></center>
						</form>
					</div>
					<p>&nbsp;&nbsp;</p>
					<p>&nbsp;&nbsp;</p>
					<p>&nbsp;&nbsp;</p>
					<div id="info" name="info">
					</div>
					<img style="float:right; position:relative; top:-150px; right:10px;" src="../../images/padlock.png" width="150px"/>
				</div>
			</div>
			<!-- end #content -->
			<div style="clear: both;">&nbsp;</div>
			<div id="widebar">
				<div id="colA">
					<h3>Top Manuales</h3>
					<dl class="list1">
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man1" class='osx' style="color:red;">Levantar un Caso</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man2" class='osx' style="color:red;">Seguimiento de casos en el nuevo HelpDesk</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man3" class='osx'>Instalaci&oacute;n y configuraci&oacute;n de Java</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man4" class='osx'>Instalaci&oacute;n Antivirus Kaspersky</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man5" class='osx'>Configuraci&oacute;n correo con Oulook 2000</a></dd>
					</dl>
				</div>
				<div id="colB">
					<h3>Novedades y Anuncios</h3>
					<img src="../images/banner01.jpg" width="450" height="150" />
				</div>
				<div style="clear: both;">&nbsp;</div>
			</div>
			<!-- end #widebar -->
		</div>
		<!-- end #page -->
	</div>
	<!-- end #wrapper2 -->
	<div id="footer">
		<p>Desarrollo y Mantenimiento del Portal por <a href="mailto:cmburboa@tpitic.com.mx">Misael Burboa</a> <br />(c) 2011 Transportes Pitic. 
	</div>
	
	<!-- Ventana Modal -->
	<div id="osx-modal-content">
		<div id="osx-modal-title">Manual de Algo</div>
		<div class="close"><a href="#" class="simplemodal-close">x</a></div>
		<div id="osx-modal-data">
			<div id="cont_modal">
				<p>&nbsp;</p>
			</div>
		</div>
	</div><!-- end ventana modal -->
</div><!-- end #wrapper -->
<div id="msgDialog" title="Mensaje de Informacion"></div>
</body>
</html>
