<?php
	session_start();
	require_once("php/MySQL/ErrorManager.class.php");
	require_once("php/MySQL/MySQL.class.php"); 
	require('php/funciones_generales.php');
	$objFN = new FuncionesGrales();
	$mysql = new MySQL();
	$mysql->connect(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Levantar Caso</title>
		<!--JQuery y JQueryUI -->
		<link type="text/css" href="js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jqueryui/js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jqueryui/js/jquery-ui-1.8.11.custom.min.js"></script>
		<!--libreria para subir archivos-->
		<link href="js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="js/uploadify/swfobject.js"></script>
		<script type="text/javascript" src="js/uploadify/jquery.uploadify.v2.1.4.min.js"></script><!-- end of uploadfile -->
		<!-- para validacion de formulario-->
		<link rel="stylesheet" href="js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
		<link rel="stylesheet" href="js/validationEngine/css/template.css" type="text/css"/>
		<script src="js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script><!--end validacion-->
		<!--configuraciones-->
		<!--<script type="text/javascript" src="js/levantacaso.js"></script>-->
		<style type="text/css">
			.fuente{ font: 62.5% "Trebuchet MS", sans-serif; margin: 5px;}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
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
	</head>
	<body class="fuente">
		<p><a href="#" id="dialog_link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Levantar Caso</a></p>

		<div id="dialog" title="Registro de Casos">
			Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br><br><br>
			<form id="levantacaso">
				<table>
				<tr><td>Caso No.</td><td><?php echo $mysql->getLastId(); ?><input type="hidden" id="nocaso" name="nocaso" value=
<?php echo $mysql->getLastId(); ?>></td></tr>
				<tr><td><label for="nombre">Nombre *</label></td><td><input type="text" id="nombre" name="nombre" style="height:15px;" maxlength="50" size="56"class="validate[required,custom[onlyLetterSp]] sombras"/></td></tr>
				<tr><td><label for="usuario">Usuario *</label></td><td><input type="text" id="usuario" name="usuario" style="height:15px;" maxlength="30" size="30" class="validate[required,custom[onlyLetter]] sombras"/>&nbsp;&nbsp;&nbsp;<label for="noempleado">No. Empleado</label>&nbsp;&nbsp;&nbsp;<input type="text" id="noempleado" name="noempleado" style="height:15px;" maxlength="4" size="5" class="validate[custom[onlyNumber]] sombras"/></td></tr>
				<tr><td><label for="correo">Email *</label></td><td><input type="text" id="correo" name="correo" style="height:15px;" maxlength="30" size="40" class="validate[required,custom[email] sombras"/></td></tr>
				<tr><td></tr>
				<tr><td>Telefono *</td><td><input type="text" id="lada" name="lada" style="height:15px;" size="3" maxlength="3" class="validate[required,custom[onlyNumber],minsize[2],maxsize[3]] sombras"/><label for="numero">-</label><input type="text" id="numero" name="numero" style="height:15px;" maxlength="8" class="validate[required,custom[phone],minSize[7]],maxSize[8] sombras" /><label for="ext">Ext *</label><input type="text" id="ext" name="ext" style="height:15px;" size="4" maxlength="4" class="validate[required,custom[phone],minSize[4]],maxSize[4] sombras" /></td></tr>
				<tr><td><label for="depto">Departamento </label></td><td><?php echo $objFN->getDeptos(); ?></td></tr>
				<tr><td><label for="oficina">Oficina *</label></td><td><?php echo $objFN->getOficinas(); ?></td></tr>
				<tr><td><label for="problema">Problema *</label></td><td><input type="text" id="problema" name="problema" class="validate[required] sombras" style="height:15px;" size="101%" maxlength="100"/></td></tr>
				<tr><td><label for="descripcion" style="font-size:9px;">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" cols="100" rows="10" maxlength="1000" class="validate[required] sombras" style="resize:none;"/></textarea></td></tr>
				 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br><br ><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
				</table>
			</form>
		</div>
		<div id="msgDialog" title="Mensaje de Informacion" style="font-family: 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif; 14px; "></div>
	</body>
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
					},
				}
			}).parent('.ui-dialog').find('.ui-dialog-titlebar-close').hide(); //con estas lineas se esconde el iconito de cerrar (osea la X)
			// Dialog Link
			$('#dialog_link').click(function(){
				$('#dialog').dialog('open');
				return false;
			});

			$('#dialog_link, ul#icons li').hover(
				function() { $(this).addClass('ui-state-hover'); }, 
				function() { $(this).removeClass('ui-state-hover'); }
			);


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
			
			$("#noempleado").change(function(){
				$.getJSON("php/autocompletar_info.php?noempleado="+$("#noempleado").val(), function(data){ 
					$.each(data, function(i, item){ 
						alert("#"+item.campo)
						$("#"+item.campo).val(item.value);
					}); 
				});
			});
			
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
</html>
