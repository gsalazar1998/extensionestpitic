<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
session_start();
if(isset($_SESSION['usuario'])){
	if($_SESSION['nivel'] == "8A"){
		echo "<script>window.location = 'http://sistemas.tpitic.com.mx/HelpDesk/admin/superadmin.php'; </script>";
	}
	if($_SESSION['nivel']== 7 || $_SESSION['nivel']== 8){
		echo "<script>window.location = 'http://sistemas.tpitic.com.mx/HelpDesk/admin/operador.php'; </script>";
	}
}else{
	echo "<script>window.location = 'http://sistemas.tpitic.com.mx/HelpDesk/index.php'; </script>";
}

require_once("../php/MySQL/ErrorManager.class.php");
require_once("../php/MySQL/MySQL.class.php"); 
require('../php/funciones_generales.php');
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
<link href="../css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuerty UI -->
	<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
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
	<script type="text/javascript" src="../js/casos_usuario.js"></script>
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
$(function(){
	//Caja que se lanza para levantar un caso	
	$('#dialog').dialog({
		autoOpen: false,
		width: 750,
		draggable: false,
		resizable: false,
		modal: true,
		closeOnEscape: false,
		buttons: {
			"Aceptar": function() {
				//evento pantalla(falso) para que aparezcan los errores.
				if($("#levantacaso").validationEngine('validate')){
					$.ajax({
						url: "../php/levantar_caso.php",
						data: $("#levantacaso").serialize()+"&adjuntos="+borrarAdj.slice(0,-1),
						type: "POST",
						success: function(data){
							if(data == "OK"){
								$('#levantacaso').validationEngine('hideAll');
								$("#dialog").dialog("close"); 
								$("#msgDialog").html("Se ha registrado su caso, en breve nos comunicamos con usted");
								$('#msgDialog').dialog('open');
							}
						},
						failure: function(){
							alert("Error en la peticion");
						}
					});
				}
				
				//$('#levantacaso').submit();
				$('#file_upload').uploadifyClearQueue();
			},

			"Cancelar": function() {
				$("#levantacaso").each (function(){this.reset();});
				try{
					$('#file_upload').uploadifyClearQueue();
					$('#levantacaso').validationEngine('hideAll');
					$.ajax({
						url: "../php/borrarArchivosSubidos.php",
						data: "borrar="+borrarAdj.slice(0,-1),
						type: "POST",
						success: function(data){
							
						},
						failure: function(){

						}
					});
					$(this).dialog("close");
				}catch(cancelException){
					$('#levantacaso').validationEngine('hideAll');
					$.ajax({
						url: "../php/borrarArchivosSubidos.php",
						data: "borrar="+borrarAdj.slice(0,-1),
						type: "POST",
						success: function(data){
							
						},
						failure: function(){

						}
					});
					$(this).dialog("close");
				}
			},
		}
	}).parent('.ui-dialog').find('.ui-dialog-titlebar-close').hide(); //con estas lineas se esconde el iconito de cerrar (osea la X)
	// Dialog Link
	$('#dialog_link').click(function(){
		$('#dialog').dialog('open');
		return false;
	});

	/*$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);*/


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
		close: function(event, ui) {if(!$('#dialog').dialog( "isOpen" )) document.location.href = document.location.href;}
	});
	
	/*$("#noempleado").change(function(){
		$.getJSON("php/autocompletar_info.php?noempleado="+$("#noempleado").val(), function(data){ 
			$.each(data, function(i, item){ 
				alert("#"+item.campo)
				$("#"+item.campo).val(item.value);
			}); 
		});
	});*/
	
	$("#usuario").change(function(){
		$.getJSON("../php/autocompletar_info.php?usuario="+$("#usuario").val(), function(data){ 
			$.each(data, function(i, item){ 
				$("#"+item.campo).val(item.value);
				$("#oficina option[value='"+item.valor+"']").attr('selected', 'selected');
			}); 
		});
	});
	//para subir archivos configuracion del elemento file
	var adjuntos = "";
	var borrarAdj = "";
	$('#file_upload').uploadify({
		'uploader'    	 : '../js/uploadify/uploadify.swf',
		'script'      	 : '../js/uploadify/uploadify.php?nocaso='+$("#nocaso").val(),
		'cancelImg'    : '../js/uploadify/cancel.png',
		'folder'      	 : 'uploads',
		'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
		'fileDesc'    	 : 'Archivos de Imagen',
		'buttonText'  : 'Adjuntar Archivo',
		'scriptData': { 'session': '<?php echo session_id();?>;'},
		'sizeLimit'   	 : 2097152,
		'onAllComplete' : function(event,data) {
					var adjuntosbr = adjuntos.replace(",", "<br>");
					$("#msgDialog").html("Se ha adjuntado correctamente el archivo(s)<br><br> "+adjuntosbr);
					$('#msgDialog').dialog('open');
					adjuntosbr="";
					adjuntos = "";
				},
		'onError'     : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
		'onComplete'  : function(event, ID, fileObj, response, data) {
			adjuntos += fileObj.name+",";
			borrarAdj += "CSO"+$("#nocaso").val()+"-"+fileObj.name+",";
			},
		'rollover'		 : false,
		'multi'			 : true,
		'hideButton'  : false
	});

	$("#levantacaso").validationEngine('validate');
});
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
					<li><a href="#" style="color:orange;">HELPDESK</a>
					</li>
					<li><a href="#" id="utilidades" ><span>UTILIDADES</span></a>
						<ul class="submenu">
							<li><a id="as" href="#">Admin. Sistemas</a>
								<ul class="subsubmenu">
									<li><a href="appsadmin.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">T&eacute;cnicos</a>
								<ul class="subsubmenu">
									<li><a href="appstecnicos.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">Usuarios</a>
								<ul class="subsubmenu">
									<li><a href="appsusuario.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#" id="programas" ><span>PROGRAMAS</span>
						<ul class="submenu">
							<li><a id="fin" href="#">Finanzas</a>
								<ul class="subsubmenu">
									<li><a href="programas/finanzas/facturas/subirfactura.php">Subir Factura</a></li>
								</ul>
							</li>
							<li><a id="grales" href="#">Generales</a>
								<ul class="subsubmenu">
									<li><a href="../../programas/vacaciones/index.php">Configurar Vacaciones</a></li>
									<li><a href="../../programas/passwords2/index.php">Cambiar Password</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#">VIDEOS Y AVISOS</a></li>
					<li style="padding-right:30px;">
						<a href="#" id="dialog_link">LEVANTAR CASO</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- end #header -->
		<div id="page">
			<div id="content" style="solid red 1px;">
				<br /><br />
				<h2 style="display:inline;">Seguimiento de casos. HelpDesk</h2>
				<!--<div class="post" style="border:solid black 1px; width:500px;">-->
					<!--* Panel de Seguimiento de casos ***************************************-->
					<a style="text-align:right;margin-right:80px;margin-top:10px;float:right;" href="../php/cerrarSesion.php">Cerrar Sesi&oacute;n HelpDesk</a>
					<br /><br />
							<div id="container" style="width:100%;">
								<div id="dynamic" class="fuente">
									<div id="tabs">
										<ul>
											<!--<li><a href="#tabs-1" class="fuente">Casos NO asignados</a></li>-->
											<li><a href="#tabs-2" class="fuente">Mis casos abiertos</a></li>
											<li><a href="#tabs-3" class="fuente">Mis casos sin confirmaci&oacute;n</a></li>
										</ul>
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
					<!-- TERMINA PANEL DEL SEGUIMIENTO DE CASOS -->
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
						<dd><a href="#" id="man5" class='osx'>Configuraci&oacute;n correo con Oulook 2000</a></dd>
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
	<div id="osx-modal-content" style="position:relative; z-index:1">
		<div id="osx-modal-title">Manual de Algo</div>
		<div class="close"><a href="#" class="simplemodal-close">x</a></div>
		<div id="osx-modal-data">
			<div id="cont_modal">
				<p>&nbsp;</p>
			</div>
		</div>
	</div><!-- end ventana modal -->
</div><!-- end #wrapper -->
<!-- EL DIV PARA LEVANTAR UN CASO /***************************************/-->
<div id="dialog" title="Registro de Casos" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;">
	Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br><br><br>
	<form id="levantacaso" >
		<table>
		<tr><td>Caso No.</td><td><?php echo $mysql->getLastId(); ?><input type="hidden" id="nocaso" name="nocaso" value=
<?php echo $mysql->getLastId(); ?>></td></tr>
		<tr><td><label for="usuario">Usuario *</label></td><td><input type="text" id="usuario" name="usuario" style="height:15px;" maxlength="30" size="30" class="validate[required,custom[onlyLetter]]"/>&nbsp;&nbsp;&nbsp;<label for="noempleado">No. Empleado</label>&nbsp;&nbsp;&nbsp;<input type="text" id="noempleado" name="noempleado" style="height:15px;" maxlength="4" size="5" class="validate[custom[onlyNumber]]"/></td></tr>
		<tr><td><label for="nombre">Nombre *</label></td><td><input type="text" id="nombre" name="nombre" style="height:15px;" maxlength="50" size="56"class="validate[required,custom[onlyLetterSp]]"/></td></tr>
		<tr><td><label for="correo">Email *</label></td><td><input type="text" id="correo" name="correo" style="height:15px;" maxlength="30" size="40" class="validate[required,custom[email]"/></td></tr>
		<tr><td></tr>
		<tr><td>Telefono *</td><td><input type="text" id="lada" name="lada" style="height:15px;" size="3" maxlength="3" class="validate[required,custom[onlyNumber],minsize[2],maxsize[3]]"/><label for="numero">-</label><input type="text" id="numero" name="numero" style="height:15px;" maxlength="8" class="validate[required,custom[phone],minSize[7]],maxSize[8]" /><label for="ext">Ext *</label><input type="text" id="ext" name="ext" style="height:15px;" size="4" maxlength="4" class="validate[required,custom[phone],minSize[4]],maxSize[4]" /></td></tr>
		<tr><td><label for="depto">Departamento </label></td><td><?php echo $objFN->getDeptos(); ?></td></tr>
		<tr><td><label for="oficina">Oficina *</label></td><td><?php echo $objFN->getOficinas(); ?></td></tr>
		<tr><td><label for="problema">Problema *</label></td><td><input type="text" id="problema" name="problema" class="validate[required]" size="93" maxlength="50"/></td></tr>
		<tr><td><label for="descripcion">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" rows="10" maxlength="1500" class="validate[required]" style="resize:none;"/></textarea></td></tr>
		 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br><br ><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
		</table>
	</form>
</div>
<div id="msgDialog" title="Mensaje de Informacion"></div>

<!--***********************************************************************-->
</body>
</html>
