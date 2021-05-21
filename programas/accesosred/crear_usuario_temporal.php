<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	session_start();
	if(!isset($_SESSION['usuario'])){
		echo "<script>window.location = 'index.php'; </script>";
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
<!-- para validacion de formulario-->
	<link rel="stylesheet" href="../../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="../../js/validationEngine/css/template.css" type="text/css"/>
	<script src="../../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
	<script src="../../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Ventanas Modales OSX-->
	<link href="../../css/osx.css" rel="stylesheet" type="text/css" media="screen" />
	<script type='text/javascript' src='../../js/osx.js'></script>
	<script type='text/javascript' src='../../js/jquery.simplemodal.js'></script>
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
$(function(){
	$("#activar_btn").button();


	$("#msgDialog").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {//if(!$('#dialog').dialog( "isOpen" )) document.location.href = document.location.href;
			//$("#entry").html("se han efectuado las actualizaciones de su correo para sus vacaciones. si necesita editar la configuracion refresque la pagina con F5");
		}
	});
	
	$("#acceso_temporal").submit(function(){
		//$("#acceso_temporal").validationEngine('attach');
		if($("#acceso_temporal").validationEngine('validate')){
			$.ajax({
				url: 'agregar_usuario_temporal.php',
				type: 'POST',
				data: $("#acceso_temporal").serialize(),
				dataType: 'json',
				success: function(json) {
					if(json.error == 0){
						$("#msgDialog").html("Usuario dado de alta correctamente, aqui sus datos:<br />"+json.msg);
						$("#msgDialog").dialog('open');
					}else{
						$("#msgDialog").html("Ocurrio un error al dar de alta al usuario invitado");
						$("#msgDialog").dialog('open');
					}
				}
			})
		}
	})
});
</script>
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
					<h2 class="title"><a href="#">Crear usuario temporal para acceso a la red</a></h2>
					<div id="entry" class="entry">
						<form id="acceso_temporal" name="acceso_temporal" onsubmit="return false">
							<center>
								<table>
								<tr><td><label for="nombre">Primer Nombre:</label></td><td><input type="text" maxlength="15" id="nombre" name="nombre" class="validate[required,custom[onlyLetter]]"/></td>
								<tr><td><label for="apellido">Primer Apellido:</label></td><td><input type="text" maxlength="20" id="apellido" name="apellido" class="validate[required,custom[onlyLetter]]"/></td>
								<tr><td colspan="2" align="right"><br /><input type="submit"value="Aceptar" id="activar_btn"></td></tr>
								</table>
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
							</center>
						</form>
					</div>
				</div>
			</div>
			<!-- end #content -->
			<!--<div id="sidebar">
				<ul>
					<li>
						<div id="appsFrecuentes">
							<h3>Aplicaciones usualmente Descargadas</h3>
							<ul class="list2">
								<li><a href="apps/jre-6u19-windows-i586.exe"><img src="images/java.png" alt="" width="50" height="50" /></a></li>
								<li><a href="apps/KAV_2010-12.exe"><img src="images/kaspersky.png" alt="" width="50" height="50" /></a></li>
								<li class="nopad"><a href="apps/Putty.exe"><img src="images/putty.jpg" alt="" width="50" height="50" /></a></li>
								<li><a href="apps/winscp432setup.exe"><img style="padding:5px;" src="images/winscp.png" alt="" width="40" height="40" /></a></li>
								<li><a href="apps/firefox_Setup 4.0.1.exe"><img style="padding:5px;" src="images/firefox.jpg" alt="" width="40" height="40" /></a></li>
								<li class="nopad"><a href="apps/pandion_setup.msi"><img style="padding:5px;"src="images/pandion.jpg" alt="Pandion" width="40" height="40" /></a>
								<li><a href="apps/tightvnc-1.3.9-setup.exe"><img style="padding:5px;" src="images/vnc.jpg" alt="" width="40" height="40" /></a></li>
								<li><a href="apps/wrar401es.exe"><img style="padding:5px;" src="images/winrar.jpg" alt="" width="40" height="40" /></a></li>
								<li class="nopad"><a href="apps/TeamViewer_Setup.exe"><img style="padding:5px;" src="images/teamviewer.jpg" alt="" width="40" height="40" /></a></li>
							</ul>
						</div>
					</li>
					<li>
						<br /><br /><br /><br /><br /><br /><br /><br /><br />
						<h3>Portales Pitic</h3>
						<ul>
							<li><a href="https://tpitic.com.mx/plportal">Light</a></li>
							<li><a href="https://tpitic.com.mx/webpitic">Empleados</a></li>
							<li><a href="https://tpitic.com.mx/rh">Recursos Humanos</a></li>
							<li><a href="https://tpitic.com.mx/finanzas">Finanzas</a></li>
							<!--<li><a href="#">Lacus dapibus et interdum</a></li>
							<li><a href="#">Morbi sit amet magna  etiam</a></li>
							<li><a href="#">Maecenas sed sem lorem</a></li>
							<li><a href="#">Lacus dapibus interdum</a></li>
							<li><a href="#">Donec pede nisl dolore</a></li>
						</ul>
					</li>
				</ul>
			</div>-->
			<!-- end #sidebar -->
			<div style="clear: both;">&nbsp;</div>
			<div id="widebar">
				<div id="colA">
					<h3>Top Manuales</h3>
					<dl class="list1">
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man1" class='osx' style="color:red;">Levantar un Caso</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man2" class='osx' style="color:red;">Seguimiento de casos en el nuevo HELPDESK</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man3" class='osx'>Instalaci&oacute;n y configuraci&oacute;n de Java</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man4" class='osx'>Instalaci&oacute;n Antivirus Kaspersky</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man5" class='osx'>Configuraci&oacute;n correo con Outlook 2000</a></dd>
					</dl>
				</div>
				<div id="colB">
					<h3>Novedades y Anuncios</h3>
					<img src="../../images/banner01.jpg" width="450" height="150" />
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
<!-- EL DIV PARA LEVANTAR UN CASO /***************************************/
<div id="dialog" title="Registro de Casos" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;">
	Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br><br><br>
	<form id="levantacaso" >
		<table>
		<tr><td>Caso No.</td><td><?php// echo $mysql->getLastId(); ?><input type="hidden" id="nocaso" name="nocaso" value=
<?php //echo $mysql->getLastId(); ?>></td></tr>
		<tr><td><label for="usuario">Usuario *</label></td><td><input type="text" id="usuario" name="usuario" style="height:15px;" maxlength="30" size="30" class="validate[required,custom[onlyLetter]]"/>&nbsp;&nbsp;&nbsp;<label for="noempleado">No. Empleado</label>&nbsp;&nbsp;&nbsp;<input type="text" id="noempleado" name="noempleado" style="height:15px;" maxlength="4" size="5" class="validate[custom[onlyNumber]]"/></td></tr>
		<tr><td><label for="nombre">Nombre *</label></td><td><input type="text" id="nombre" name="nombre" style="height:15px;" maxlength="50" size="56"class="validate[required,custom[onlyLetterSp]]"/></td></tr>
		<tr><td><label for="correo">Email *</label></td><td><input type="text" id="correo" name="correo" style="height:15px;" maxlength="30" size="40" class="validate[required,custom[email]]"/></td></tr>
		<tr><td></tr>
		<tr><td>Telefono *</td><td><input type="text" id="lada" name="lada" style="height:15px;" size="3" maxlength="3" class="validate[required,custom[onlyNumber],minsize[2],maxsize[3]]"/><label for="numero">-</label><input type="text" id="numero" name="numero" style="height:15px;" maxlength="8" class="validate[required,custom[phone],minSize[7],maxSize[8]]" /><label for="ext">Ext *</label><input type="text" id="ext" name="ext" style="height:15px;" size="4" maxlength="4" class="validate[required,custom[phone],minSize[4],maxSize[4]"] /></td></tr>
		<tr><td><label for="depto">Departamento </label></td><td><?php //echo $objFN->getDeptos(); ?></td></tr>
		<tr><td><label for="oficina">Oficina *</label></td><td><?php //echo $objFN->getOficinas(); ?></td></tr>
		<tr><td><label for="problema">Problema *</label></td><td><input type="text" id="problema" name="problema" class="validate[required]" size="93" maxlength="50"/></td></tr>
		<tr><td><label for="descripcion">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" cols="90" rows="10" maxlength="1500" class="validate[required]" style="resize:none;"></textarea></td></tr>
		 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br><br ><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
		</table>
	</form>
</div>-->
<div id="msgDialog" title="Mensaje de Informacion"></div>

<!--***********************************************************************-->
</body>
</html>
