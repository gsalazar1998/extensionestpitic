<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
session_start();
if(isset($_SESSION['usuario'])){
	if($_SESSION['nivel'] != 8 && $_SESSION['nivel'] != 7 && $_SESSION['nivel'] != "8A"){
		echo "<script>window.location = 'appsusuario.php'; </script>";
	}
	/*if($_SESSION['nivel']== 7){
		echo "<script>window.location = 'admin/operador.php'; </script>";
	}
	else{
		echo "<script>window.location = 'appsusuario.php'; </script>";
	}*/
}else{
	echo "<script>window.location = 'loginapps.php'; </script>";
}

require_once("php/MySQL/ErrorManager.class.php");
require_once("php/MySQL/MySQL.class.php"); 
require('php/funciones_generales.php');
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
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuerty UI -->
	<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
	<link type="text/css" href="js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="js/jqueryui/jquery-ui-1.8.11.custom.min.js"></script>
<!-- Libreria para subir archivos UPLOADIFY-->
	<link href="js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
	<script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<!-- para validacion de formulario-->
	<link rel="stylesheet" href="js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="js/validationEngine/css/template.css" type="text/css"/>
	<script src="js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Ventanas Modales OSX-->
	<link href="css/osx.css" rel="stylesheet" type="text/css" media="screen" />
	<script type='text/javascript' src='js/osx.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<!--flipSponsor libreria para mostrar animaciones en la lista de apps. -->
	<script type="text/javascript" src="js/flipsponsor/jquery.flip.min.js"></script>
	<script type="text/javascript" src="js/flipsponsor/scriptConfFlip.js"></script>
	<link type="text/css" rel="stylesheet" href="js/flipsponsor/stylesflip.css" media="screen" />
	
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
						url: "php/levantar_caso.php",
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
						url: "php/borrarArchivosSubidos.php",
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
						url: "php/borrarArchivosSubidos.php",
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
		$.getJSON("php/autocompletar_info.php?usuario="+$("#usuario").val(), function(data){ 
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
		'uploader'    	 : 'js/uploadify/uploadify.swf',
		'script'      	 : 'js/uploadify/uploadify.php?nocaso='+$("#nocaso").val(),
		'cancelImg'    : 'js/uploadify/cancel.png',
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
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<img src="images/logo.png" width="310px;" />
			</div>
			<div id="menu">
				<ul id="nav">
					<li><a href="index.php">INICIO</a></li>
					<li><a href="HelpDesk/index.php">HELPDESK</a>
					</li>
					<li><a href="#" style="color:orange;" id="utilidades" ><span>UTILIDADES</span></a>
						<ul class="submenu">
							<li><a id="as" href="#">Admin. Sistemas</a>
								<ul class="subsubmenu">
									<li><a href="#">Compendio de Utileria</a></li>
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
					<li><a href="#">VIDEOS Y AVISOS</a></li>
					<li style="padding-right:30px;">
						<a href="#" id="dialog_link">LEVANTAR CASO</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- end #header -->
		<div id="page">
				<div id="content"><br /><br /><h2>Compendio de Utileria...</h2></div>
				<div class="sponsorListHolder">
					<?php
						// Each sponsor is an element of the $sponsors array:

						$sponsors = array(
							array('Redes, Herramientas y Utilerias','Redes, Herramientas y utilerias',
								'<a href="apps/AdbeRdr1001_es_ES.exe">Adobe Acrobat Reader</a><br>
								<a href="apps/ChromeSetup.exe">Google Chrome</a><br>
								<a href="apps/firefox_Setup 4.0.1.exe">Firefox 4.0</a><br>
								<a href="apps/firefox_3.6.2.exe">Firefox 3.6.2</a><br>
								<a href="apps/npp.5.9.1.Installer.exe">Notepad++</a><br>
								<a href="apps/winscp432setup.exe">WinSCP</a><br>
								<a href="apps/Putty.exe">Putty</a><br>
								<a href="apps/TeamViewer_Setup.exe">Team Viewer</a><br>
								<a href="apps/tightvnc-1.3.9-setup.exe">Tight VNC</a><br>
								<a href="apps/wrar401es.exe">WinRAR</a><br>
								<a href="apps/nt3242a.exe">Net Term</a><br>
								<a href="apps/TortoiseSVN-1.6.16.21511-win32-svn-1.6.17.msi">TortoiseSVN</a><br>
								<a href="apps/dbvis_windows_8_0_1.exe">DBVisualizer</a><br>','toolbox.png'),
							array('Librer&iacute;as y SDK\'s','Librer&iacute;as y SDK\'s necesarias para los programas',
								'<a href="apps/j2re-1_4_2_05-windows-i586-p.exe">Java 1.4.2_05</a><br>
								<a href="apps/jre-6u19-windows-i586.exe">Java 6 Update 19</a><br>
								<a href="apps/myodbc-2.50.36-nt.exe">MyODBC 2.50.36</a>',"software.png"),
							array('Antiv&iacute;rus','Programas de Antiv&iacute;rus',
								'<a href="apps/KAV_2010-12.exe">Kaspersky</a><br>',"antivirus.png"),
							array('Comunicaci&oacute;n','Programas de comunicaci&oacute;n',
								'<a href="apps/pandion_setup.msi">Pandion</a><br>
								<a href="apps/pidgin-ppa_0.0.5_all.deb">Pidgin (Linux)</a><br>
								<a href="apps/pidgin-2.9.0.exe">Pidgin (Windows)</a><br>',"comunicaciones.png"),
						);

						// Se genera el orden random de las applicaciones:
						//shuffle($sponsors);
						
						// Creamos el cuadro (la imagencita) para cada una de las aplicaciones:
						$cadena = "";
						foreach($sponsors as $company){
							echo '
							<div class="sponsor" title="'.$company[0].'">
								<div class="sponsorFlip">
									<div class="sponsorTitle">'.$company[0].'</div>
									<img src="js/flipsponsor/img/'.$company[3].'" alt="'.$company[0].'" width="150px"/>
								</div>
								
								<div class="sponsorData">
									<div class="sponsorDescription">
										'.$company[1].'
									</div>
									<div class="sponsorURL">'
										.$company[2].
									'</div>
								</div>
							</div>';
						}
					?>
					<div class="clear"></div>
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
			</div>
			<!-- end #sidebar -->
			<div style="clear: both;">&nbsp;</div>
			<br /><br /><br /><br /><br />
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
						<dd><a href="#" id="man5" class='osx'>Configuraci&oacute;n correo con Outlook 2000</a></dd>
					</dl>
				</div>
				<div id="colB">
					<h3>Novedades y Anuncios</h3>
					<img src="images/banner01.jpg" width="450" height="150" />
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
<!-- EL DIV PARA LEVANTAR UN CASO /***************************************/-->
<div id="dialog" title="Registro de Casos" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;">
	Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br><br><br>
	<form id="levantacaso" >
		<table>
		<tr><td>Caso No.</td><td><?php echo $mysql->getLastId(); ?><input type="hidden" id="nocaso" name="nocaso" value=
<?php echo $mysql->getLastId(); ?>></td></tr>
		<tr><td><label for="usuario">Usuario *</label></td><td><input type="text" id="usuario" name="usuario" style="height:15px;" maxlength="30" size="30" class="validate[required,custom[onlyLetter]]"/>&nbsp;&nbsp;&nbsp;<label for="noempleado">No. Empleado</label>&nbsp;&nbsp;&nbsp;<input type="text" id="noempleado" name="noempleado" style="height:15px;" maxlength="4" size="5" class="validate[custom[onlyNumber]]"/></td></tr>
		<tr><td><label for="nombre">Nombre *</label></td><td><input type="text" id="nombre" name="nombre" style="height:15px;" maxlength="50" size="56"class="validate[required,custom[onlyLetterSp]]"/></td></tr>
		<tr><td><label for="correo">Email *</label></td><td><input type="text" id="correo" name="correo" style="height:15px;" maxlength="30" size="40" class="validate[required,custom[email]]"/></td></tr>
		<tr><td></tr>
		<tr><td>Telefono *</td><td><input type="text" id="lada" name="lada" style="height:15px;" size="3" maxlength="3" class="validate[required,custom[onlyNumber],minsize[2],maxsize[3]]"/><label for="numero">-</label><input type="text" id="numero" name="numero" style="height:15px;" maxlength="8" class="validate[required,custom[phone],minSize[7],maxSize[8]]" /><label for="ext">Ext *</label><input type="text" id="ext" name="ext" style="height:15px;" size="4" maxlength="4" class="validate[required,custom[phone],minSize[4],maxSize[4]"] /></td></tr>
		<tr><td><label for="depto">Departamento </label></td><td><?php echo $objFN->getDeptos(); ?></td></tr>
		<tr><td><label for="oficina">Oficina *</label></td><td><?php echo $objFN->getOficinas(); ?></td></tr>
		<tr><td><label for="problema">Problema *</label></td><td><input type="text" id="problema" name="problema" class="validate[required]" size="93" maxlength="50"/></td></tr>
		<tr><td><label for="descripcion">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" cols="90" rows="10" maxlength="1500" class="validate[required]" style="resize:none;"/></textarea></td></tr>
		 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br><br ><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
		</table>
	</form>
</div>
<div id="msgDialog" title="Mensaje de Informacion"></div>

<!--***********************************************************************-->
</body>
</html>
