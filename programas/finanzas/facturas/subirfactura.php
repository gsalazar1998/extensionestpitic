<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/*session_start();
if(isset($_SESSION['usuario'])){
	if($_SESSION['nivel'] != 8 && $_SESSION['nivel'] != 7 && $_SESSION['nivel'] != "8A"){
		echo "<script>window.location = 'appsusuario.php'; </script>";
	}
	/*if($_SESSION['nivel']== 7){
		echo "<script>window.location = 'admin/operador.php'; </script>";
	}
	else{
		echo "<script>window.location = 'appsusuario.php'; </script>";
	}
}else{
	echo "<script>window.location = 'loginapps.php'; </script>";
}*/

require_once("../../../php/MySQL/ErrorManager.class.php");
require_once("../../../php/MySQL/MySQL.class.php"); 
require('../../../php/funciones_generales.php');
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
<link href="../../../css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuerty UI -->
	<script type="text/javascript" src="../../../js/jquery-1.5.1.min.js"></script>
	<link type="text/css" href="../../../js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="../../../js/jqueryui/jquery-ui-1.8.11.custom.min.js"></script>
<!-- Libreria para subir archivos UPLOADIFY-->
	<link href="../../../js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="../../../js/uploadify/swfobject.js"></script>
	<script type="text/javascript" src="../../../js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<!-- para validacion de formulario-->
	<link rel="stylesheet" href="../../../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="../../../js/validationEngine/css/template.css" type="text/css"/>
	<script src="../../../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
	<script src="../../../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Ventanas Modales OSX-->
	<link href="../../../css/osx.css" rel="stylesheet" type="text/css" media="screen" />
	<script type='text/javascript' src='../../../js/osx.js'></script>
	<script type='text/javascript' src='../../../js/jquery.simplemodal.js'></script>
<!--flipSponsor libreria para mostrar animaciones en la lista de apps. -->
	<script type="text/javascript" src="../../../js/flipsponsor/jquery.flip.min.js"></script>
	<script type="text/javascript" src="../../../js/flipsponsor/scriptConfFlip.js"></script>
	<link type="text/css" rel="stylesheet" href="../../../js/flipsponsor/stylesflip.css" media="screen" />
	
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
	/********* para las fechas *********/
	 $.datepicker.regional['es'] = {
      /*closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',*/
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
      dayNamesShort: ['Dom','Lun','Mar','Mi&eacute','Juv','Vie','S&aacute;b'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
      weekHeader: 'Sm',
      dateFormat: 'yy/mm/dd',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
   
	$( "#ano_aprobacion" ).datepicker({changeYear: true, yearRange: '2000:', dateFormat: "yy"});
	$( "#fec_autorizacion" ).datepicker();
	/***********************************/
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
						url: "../../../../php/borrarArchivosSubidos.php",
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
						url: "../../../../php/borrarArchivosSubidos.php",
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
	/*var adjuntos = "";
	var borrarAdj = "";
	$('#file_upload').uploadify({
		'uploader'    	 : '../../../js/uploadify/uploadify.swf',
		'script'      	 : '../../../js/uploadify/uploadify.php?nocaso='+$("#nocaso").val(),
		'cancelImg'    : '../../../js/uploadify/cancel.png',
		'folder'      	 : 'uploads',
		'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
		'fileDesc'    	 : 'Archivos de Imagen',
		'buttonText'  : 'Adjuntar Archivo',
		'scriptData': { 'session': '<?php echo session_id();?>;'},
		'sizeLimit'   	 : 2097152,
		'onAllComplete' : function(event,data) {
					var adjuntosbr = adjuntos.replace(",", "<br />");
					a("Se ha adjuntado correctamente el archivo(s)<br /><br /> "+adjuntosbr);
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
	
	/************************* PARA LA PARTE DE SUBIR FACTURAS **************************************/	
	/*var adjuntos = "";
	var adjRename = "";
	var pdfs = "";
	var xmls = "";
	$('#subir_factura').uploadify({
		'uploader'    	 : '../../../js/uploadify/uploadify.swf',
		'script'      	 : 'uploadify.php',
		'cancelImg'    : '../../../js/uploadify/cancel.png',
		'folder'      	 : 'facturas',
		'fileExt'     	 : '*.pdf;*.xml',
		'fileDesc'    	 : '',
		'buttonText'  : 'Adjuntar Facturas',
		'scriptData': { 'session': '<?php echo session_id();?>;'},
		'sizeLimit'   	 : 2097152,
		'onAllComplete' : function(event,data) {
				$.ajax({
					url: "guardarFacturas.php",
					data: $("#factura").serialize()+"&xmls="+xmls.slice(0,-1)+"&pdfs="+pdfs.slice(0,-1),
					type: "POST",
					success: function(data){
						if(data == "OK"){
							$('#msgDialog').html("Factura Archivada con exito");
							$('#msgDialog').dialog("open");
						}
					},
					failure: function(){
						alert("Error en la peticion");
					}
				});
		},
		'onError'     : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
		'onComplete'  : function(event, ID, fileObj, response, data) {
				alert(fileObj.name);
				if(fileObj.name.slice(-3) == "pdf"){
					pdfs += fileObj.name+",";
				}
				if(fileObj.name.slice(-3) == "xml"){
					xmls += fileObj.name+",";
				}
			},
		'rollover'		 : false,
		'multi'			 : true,
		'queueSizeLimit' : 2,
		'hideButton'  : false
	});*/
	
	
	/*********************************************************************************************/
	function openWindow(pagina) {
		var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
		window.open(pagina,"",opciones);
	}
});
</script>
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<img src="../../../images/logo.png" width="310px;" />
			</div>
			<div id="menu">
				<ul id="nav">
					<li><a href="../../../index.php">INICIO</a></li>
					<li><a href="../../../HelpDesk/index.php">HELPDESK</a>
					</li>
					<li><a href="#" style="color:orange;" id="utilidades" ><span>UTILIDADES</span></a>
						<ul class="submenu">
							<li><a id="as" href="#">Admin. Sistemas</a>
								<ul class="subsubmenu">
									<li><a href="../../../appsadmin.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">T&eacute;cnicos</a>
								<ul class="subsubmenu">
									<li><a href="../../../appstecnicos.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">Usuarios</a>
								<ul class="subsubmenu">
									<li><a href="../../../appsusuario.php">Compendio de Utileria</a></li>
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
				<div id="content"><br /><br /><h2>Captura y Registro de Facturas...</h2></div>
				<div style="clear: both;"></div>
				<div id="subeFactura" style="width:800px; position:relative; left:80px;">
					<form id="factura" enctype="multipart/form-data" action="guardarFacturas.php" method="POST"> <!-- onsubmit="return false"-->
					<center><table>
					<tr><td style="padding-left:10px;"><label for="tipo">Tipo de Factura:</label></td><td>
						<select id="tipo" name="tipo" onchange="javascript:$('#divform').load('forms/'+this.value+'.html');">
							<option value='' selected>Seleccione</option>
							<option value='cfd'>CFD</option>
							<option value='cfdi'>CFDI</option>
							<option value='cbb'>CBB</option>
						</select></td>
					</tr>
					</table>
					<div id="divform"></div>
					<!--<tr><td style="padding-left:10px;"><label for="cuenta_coi">Cuenta COI:</label></td><td><input type="text" id="cuenta_coi" name="cuenta_coi" maxlength="19"/><input type="button" value="Elegir Proveedor" onclick="javascript:window.open('elijeproveedor.php','','width=600,height=300,scrollbars=yes');"</td></tr>
					<tr><td style="padding-left:10px;"><label for="rfc">RFC:</label></td><td><input type="text" id="rfc" name="rfc" maxlength="13"/></td></tr>
					<tr><td style="padding-left:10px;"><label for="proveedor">Nombre Proveedor:</label></td><td><input type="text" id="proveedor" name="proveedor" maxlength="100"/></td></tr>
					<tr><td><strong>IMPRESA</strong></td></td>
						<tr><td style="padding-left:10px;"><label for="folios_aut">Folios Autorizados:</label></td><td><input type="text" id="folios_aut" name="folios_aut" /></td></tr>
						<tr><td style="padding-left:10px;"><label for="ano_aprobacion">A&ntilde;o de Aprobaci&oacute;n:</label></td><td><input type="text" id="ano_aprobacion" name="ano_aprobacion" readonly='readonly'/></td></tr>
					<tr><td><strong>VERIFICACI&Oacute;N</strong></td></tr>
						<tr><td style="padding-left:10px;"><label for="fec_autorizacion">Fecha Autorizaci&oacute;n:</label></td><td><input type="text" id="fec_autorizacion" name="fec_autorizacion" readonly='readonly'/></td></tr>
						<tr><td style="padding-left:10px;"><label for="verif_usuario">Usuario:</label></td><td><input type="text" id="verif_usuario" name="verif_usuario" /></td></tr>-->
					
					<!--<br /><input id="subir_factura" name="subir_factura" type="file"/>-->
					<!--<a id="subirArchivos" href="javascript:$('#subir_factura').uploadifyUpload();" >Subir Archivos</a><br /><br />-->
					<a id="listadoFacturas" href="listarArchivos.php">Ver listado de facturas</a></center>
					</form>
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
					<img src="../../../images/banner01.jpg" width="450" height="150" />
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
	Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br /><br /><br />
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
		<tr><td><label for="descripcion">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" cols="90" rows="10" maxlength="1500" class="validate[required]" style="resize:none;"></textarea></td></tr>
		 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br /><br /><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
		</table>
	</form>
</div>
<div id="msgDialog" title="Mensaje de Informacion"></div>

<!--***********************************************************************-->
</body>
</html>
