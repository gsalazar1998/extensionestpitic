<?php 
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
session_start();
switch($_GET["tidf"]){
	case 1: echo obtenerNoAsignados(); break;
	case 2: echo obtenerAsignados($_SESSION['usuario']); break;
	case 3: echo obtenerInfoCaso($_POST['idcaso']); break;
	case 4: echo obtenerAbiertosPorMi($_SESSION['usuario']); break;
	case 5: echo obtenerInfoCasoPM($_POST['idcaso']); break;
	case 6: echo obtenerSinConfirmacionAdmin(); break;
	case 7: echo obtenerAsignadosTodos(); break;
	case 8: echo obtenerInfoCasoTodosAbiertos($_POST['idcaso']); break;
	case 9: echo obtenerInfoCasoSinResponderBoton($_POST['idcaso']); break;
	case 10: echo obtenerOfinaDeCaso($_POST['idcaso']); break;
}

function obtenerNoAsignados(){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ASIGNADO_A = 'NO' AND ESTADO_CASO =1 ORDER BY ID_CASO DESC";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.addslashes($row['PROBLEMA']).'","'.$row['OFICINA'].'","'.$row['DEPTO'].'","'.substr($row['FECHA_APERTURA'],0,10).'","'.substr($row['FECHA_APERTURA'], -8).'"],';
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{"aaData": []}';
	}
	return $nassign;
}



function obtenerAsignados($usuario){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ASIGNADO_A = '".$usuario."' AND ESTADO_CASO =1 ORDER BY ID_CASO DESC"; //A
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			$mysql = new MySQL();
			$mysql->connect(); 
			$q = "SELECT COUNT(*) AS RESPUESTAS FROM casos_respuestas WHERE ID_CASO = '".$row['ID_CASO']."'"; //A
			$rs = $mysql->query($q);
			$numero_resp = mysql_fetch_array($rs);
			
			if($row['ESTADO_AUTORIZACION'] != NULL || $row['AUTORIZADO_POR'] != NULL){
				$edo_caso = $row['ESTADO_AUTORIZACION'];
				$autorizo = $row['AUTORIZADO_POR'];
			}else{
				$edo_caso = "NO SOLICITADA";
				$autorizo = "NADIE";
			}
			$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.addslashes($row['PROBLEMA']).'","'.$row['OFICINA'].'","'.substr($row['FECHA_APERTURA'],0,10).'","<p>'.$numero_resp['RESPUESTAS'].'&nbsp;&nbsp;<img src=\"../img/icono-mensaje.gif\" width=\"15px\" height=\"10\"/></p>", "'.$edo_caso.'", "'.$autorizo.'"],';
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{ "aaData": []}';
	}
	return $nassign;
}

function obtenerInfoCaso($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$respuestas = obtenerRespuestas($idCaso);
		$row = mysql_fetch_array($result);
		
		/******* se utiliza esto para obtener el nombre del departamento, primero se obtiene la clave y despues se hace la busqueda con ella ***/
		$mysql2 = new MySQL();
		$mysql2->connect(); 
		$q = "SELECT * FROM departamentos WHERE clave = '".$row['DEPTO']."'";
		$rs = $mysql2->query($q);
		$depto = mysql_fetch_array($rs);
		
		
		$archivosArr = explode(",", $row['ARCHIVOS']);
		$numArchivos = count($archivosArr);
		$nassign = "<table border='0' style='width:100%'>
		<tr><td class='tableLabels'>Caso No.</td><td class='tableLabels'>Fecha:</td><td class='tableLabels'>Hora</td></tr>
		<tr><td>".$row['ID_CASO']."<input type='hidden' id='num_caso' name='num_caso' value='".$row['ID_CASO']."' /></td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr><td class='tableLabels'>Abierto por: </td><td class='tableLabels'>Oficina</td><td class='tableLabels'>Depto.</td></tr>
		<tr><td>".$row['NOMBRE']." (".$row['USUARIO'].")</td><td>".$row['OFICINA']."</td><td>".$depto['DEPARTAMENTO']."</td>
		<tr><td colspan='2' class='tableLabels'>Problema: </td><td class='tableLabels'>T&eacute;lefono</td></tr>
		<tr><td colspan='2'>".$row['PROBLEMA']."</td><td>".$row['TELEFONO']."</td></tr>
		</table>
		<div id='accordionDesc' class='fuenteTable'>
			<h3><a href='#'>Descripcion del problema</a></h3>
			<div id='descripcionInfoCaso'>
				<table>
				<tr><td>".nl2br($row['DESCRIPCION'])."</td></tr>
				<tr><td><div id='respuestas'";
				if($respuestas != "<table style='width:100%;'></table>"){
					$nassign.= "style='background-color:#E6E6E6;border:1px solid blue;margin:10px;'";
				}
				$nassign.=">".nl2br($respuestas)."</div><input type='button' id='resp_btn' value='Responder'></button></td></tr>
				</table>
			</div>
		</div>";
		if($numArchivos > 0){
		$nassign .="
			<div id='accordionAdj'>
			<h3><a href='#'>Adjuntos</a></h3>
			<div id='gallery'>
				<ul>";
				foreach($archivosArr as $aA){
					$tipoArch = substr($aA,-4, 4);
					if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == ".jpeg"){
						$nassign .= "
						<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='#'>
								<img src='../uploads/".$aA."' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".pdf"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/icono_pdf.jpg' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".doc" or $tipoArch == "docx" or $tipoArch == ".odt"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/word_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".xls" or $tipoArch == "xlsx" or $tipoArch == ".ods"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/excel_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".ppt" or $tipoArch == "pptx" or $tipoArch == ".odp"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/powerpoint_icon.gif' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($aA == "NULL" or $aA == ""){
						$nassign .= "No Hay Archivos Adjuntos!";
					}else{
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/notepad.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}
				}
		$nassign.="</ul>
			<div id=adjOtrArDiv style='text-align:left;'><input id='file_upload' name='file_upload' type='file' /><span style='color:red;float:left;'>Maximo 2MB</span><br><br ><a href='javascript:$(\"#file_upload\").uploadifyUpload();' style='color:blue;'>Subir Archivos</a></div>
			</div>
		</div><br/> <!-- Fina div acordionAdj-->
		<div id='solicAuto' style='padding:15px;'><a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a></div>";
		}
	$nassign.= "<script>
		$(function() {
			$( '#solicAutoCancel' ).hide();
			
			$( '#resp_btn' ).button();
			$('#resp_btn').click(function(){
				$('#respuesta_ven').html(\"<form id='respuesta_form'>Respuesta:<br /><input type='hidden' name='id_caso' value='".$idCaso."'><textarea id='respuesta' name='respuesta' rows='5' maxlength='255' class='validate[required] sombras' style='resize:none;width:100%;'/></textarea></form>\");
				$('#respuesta_ven').dialog('open');
			});
			
			$( '#accordionAdj' ).accordion({
				collapsible: true,
				active: -1
			});
			
			$( '#accordionDesc' ).accordion({
				collapsible: true,
			});
			
			var adjuntos = '';
			var borrarAdj = '';
			$('#file_upload').uploadify({
				'uploader'    	 : '../js/uploadify/uploadify.swf',
				'script'      	 : '../js/uploadify/uploadify.php?nocaso='+".$row['ID_CASO'].",
				'cancelImg'      : '../js/uploadify/cancel.png',
				'folder'      	 : '../uploads',
				'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
				'fileDesc'    	 : 'Archivos de Ayuda',
				'buttonText'  	 : 'Adj. Otro Archivo',
				'scriptData': { 'session': '".session_id()."'},
				'sizeLimit'   	 : 2097152,
				'onAllComplete'  : function(event,data) {
							var adjuntosbr = adjuntos.replace(',', '<br>');
							$('#msgDialog').html('Se ha adjuntado correctamente el archivo(s)<br><br> '+adjuntosbr);
							$('#msgDialog').dialog('open');
							adjuntosbr='';
							adjuntos = '';
							$.ajax({
								url: '../php/agregarArchivos.php',
								data: 'agregar='+borrarAdj.slice(0,-1)+'&idc=".$row['ID_CASO']."',
								type: 'POST',
								success: function(data){
									if(data == 'OK'){
										$('#msgDialog').html('Archivo(s) subido con exito');
										$('#msgDialog').dialog('open');
									}
								},
								failure: function(){

								}
							});
						},
				'onError'   : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
				'onComplete'  : function(event, ID, fileObj, response, data) {
					adjuntos += fileObj.name+',';
					borrarAdj += 'CSO'+".$row['ID_CASO']."+'-'+fileObj.name+',';
					},
				'rollover'		 : false,
				'multi'			 : true,
				'hideButton'  	 : false
			});";
			foreach($archivosArr as $aA){
				if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == "jpeg"){
					$nassign .= 
					"$('#".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."').click(function(){
						$('#fotos').html(\"<img src='../uploads/".$aA."'/>\");
						$('#fotos').dialog('open');
					});";
				}elseif($tipoArch == "pdf"){

				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}elseif($tipoArch == "xls" or $tipoArch == "xlsx"){
				}elseif($tipoArch == "ppt" or $tipoArch == "pptx"){
				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}
			}
	$nassign.= "
		});
		
		$( '#solicAuto_link' ).live('click', function(){
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<tr><td><form id='solicAutoForm' onSubmit='return enviarSolAuto();'>Solicitar Autorizacion de: <input type='text' id='autorizacionde' name='autorizacionde' class='validate[required] sombras'/></form></td><td><a id='icono_envSolAuto' href='#' onclick='enviarSolAuto();'><img src='../img/email_icon.png'></a></td><td><a id='cancelAuto_link' href='#'><img src='../img/icono-cerrar.png' width='20px' height='20px' '/></a></td></tr>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		$( '#cancelAuto_link' ).live('click', function(){
			$('#solicAutoForm').validationEngine('hideAll')
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		function enviarSolAuto(){
			if($('#solicAutoForm').validationEngine('validate')){
				$.ajax({
					url:'../php/solicitarAutorizacion.php',
					data: $('#solicAutoForm').serialize()+'&id_caso='+".$idCaso.",
					type: 'POST',
					success: function(data){
						if(data == ''){
							$('#solicAuto').effect('highlight', {color:'#2EFE2E'}, 4000);
							$('#solicAuto').html('Se han enviado las solicitudes de autorizacion');
						}else{
							$('#solicAuto').effect('highlight', {color:'#FF0000'}, 4000);
							$('#solicAuto').html(data);
						}
					},
					failure: function(){
					}
				});
			}
			
			return false;
		}
	</script>";
	}else{
		$nassign = 'No se encontro informacion del caso';
	}
	return $nassign;
}

function obtenerInfoCasoTodosAbiertos($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$respuestas = obtenerRespuestas($idCaso);
		$row = mysql_fetch_array($result);
		
		/******* se utiliza esto para obtener el nombre del departamento, primero se obtiene la clave y despues se hace la busqueda con ella ***/
		$mysql = new MySQL();
		$mysql->connect(); 
		$q = "SELECT * FROM departamentos WHERE clave = '".$row['DEPTO']."'";
		$rs = $mysql->query($q);
		$depto = mysql_fetch_array($rs);
		
		$archivosArr = explode(",", $row['ARCHIVOS']);
		$numArchivos = count($archivosArr);
		$nassign = "<table border='0' style='width:100%'>
		<tr><td class='tableLabels'>caso No.</td><td class='tableLabels'>Fecha:</td><td class='tableLabels'>Hora</td></tr>
		<tr><td>".$row['ID_CASO']."</td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr><td class='tableLabels'>Abierto por: </td><td class='tableLabels'>Oficina</td><td class='tableLabels'>Depto.</td></tr>
		<tr><td>".$row['NOMBRE']." (".$row['USUARIO'].")</td><td>".$row['OFICINA']."</td><td>".$depto['DEPARTAMENTO']."</td>
		<tr><td colspan='2' class='tableLabels'>Problema: </td><td class='tableLabels'>T&eacute;lefono</td></tr>
		<tr><td colspan='2'>".$row['PROBLEMA']."</td><td>".$row['TELEFONO']."</td></tr>
		</table>
		<div id='accordionDescTA' class='fuenteTable'>
			<h3><a href='#'>Descripcion del problema</a></h3>
			<div id='descripcionInfoCaso'>
				<table>
				<tr><td>".nl2br($row['DESCRIPCION'])."</td></tr>
				<tr><td><div id='respuestas'";
				if($respuestas != "<table style='width:100%;'></table>"){
					$nassign.= "style='background-color:#E6E6E6;border:1px solid blue;margin:10px;'";
				}
				$nassign.=">".nl2br($respuestas)."</div><input type='button' id='resp_btn' value='Responder'></button></td></tr>
				</table>
			</div>
		</div>";
		if($numArchivos > 0){
		$nassign .="
			<div id='accordionAdjTA'>
			<h3><a href='#'>Adjuntos</a></h3>
			<div id='gallery'>
				<ul>";
				foreach($archivosArr as $aA){
					$tipoArch = substr($aA,-4, 4);
					if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == ".jpeg"){
						$nassign .= "
						<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='#'>
								<img src='../uploads/".$aA."' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".pdf"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/icono_pdf.jpg' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".doc" or $tipoArch == "docx" or $tipoArch == ".odt"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/word_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".xls" or $tipoArch == "xlsx" or $tipoArch == ".ods"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/excel_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".ppt" or $tipoArch == "pptx" or $tipoArch == ".odp"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/powerpoint_icon.gif' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($aA == "NULL" or $aA == ""){
						$nassign .= "No Hay Archivos Adjuntos!";
					}else{
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/notepad.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}
				}
		$nassign.="</ul>
			<div id=adjOtrArDiv style='text-align:left;'><input id='file_upload' name='file_upload' type='file' /><span style='color:red;float:left;'>Maximo 2MB</span><br><br ><a href='javascript:$(\"#file_upload\").uploadifyUpload();' style='color:blue;'>Subir Archivos</a></div>
			</div>
		</div><br/> <!-- Fina div acordionAdj-->";
		}
	$nassign.= "<script>
		$(function() {
			$( '#resp_btn' ).button();
			$('#resp_btn').click(function(){
				$('#respuesta_ven').html(\"<form id='respuesta_form'>Respuesta:<br /><input type='hidden' name='id_caso' value='".$idCaso."'><textarea id='respuesta' name='respuesta' rows='5' maxlength='255' class='validate[required] sombras' style='resize:none;width:100%;'/></textarea></form>\");
				$('#respuesta_ven').dialog('open');
			});
			
			$( '#accordionAdjTA' ).accordion({
				collapsible: true,
				active: -1
			});
			
			$( '#accordionDescTA' ).accordion({
				collapsible: true,
			});
			
			var adjuntos = '';
			var borrarAdj = '';
			$('#file_upload').uploadify({
				'uploader'    	 : '../js/uploadify/uploadify.swf',
				'script'      	 : '../js/uploadify/uploadify.php?nocaso='+".$row['ID_CASO'].",
				'cancelImg'      : '../js/uploadify/cancel.png',
				'folder'      	 : '../uploads',
				'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
				'fileDesc'    	 : 'Archivos de Ayuda',
				'buttonText'  	 : 'Adj. Otro Archivo',
				'scriptData': { 'session': '".session_id()."'},
				'sizeLimit'   	 : 2097152,
				'onAllComplete'  : function(event,data) {
							var adjuntosbr = adjuntos.replace(',', '<br>');
							$('#msgDialog').html('Se ha adjuntado correctamente el archivo(s)<br><br> '+adjuntosbr);
							$('#msgDialog').dialog('open');
							adjuntosbr='';
							adjuntos = '';
							$.ajax({
								url: '../php/agregarArchivos.php',
								data: 'agregar='+borrarAdj.slice(0,-1)+'&idc=".$row['ID_CASO']."',
								type: 'POST',
								success: function(data){
									if(data == 'OK'){
										$('#msgDialog').html('Archivo(s) subido con exito');
										$('#msgDialog').dialog('open');
									}
								},
								failure: function(){

								}
							});
						},
				'onError'   : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
				'onComplete'  : function(event, ID, fileObj, response, data) {
					adjuntos += fileObj.name+',';
					borrarAdj += 'CSO'+".$row['ID_CASO']."+'-'+fileObj.name+',';
					},
				'rollover'		 : false,
				'multi'			 : true,
				'hideButton'  	 : false
			});";
			foreach($archivosArr as $aA){
				if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == "jpeg"){
					$nassign .= 
					"$('#".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."').click(function(){
						$('#fotos').html(\"<img src='../uploads/".$aA."'/>\");
						$('#fotos').dialog('open');
					});";
				}elseif($tipoArch == "pdf"){

				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}elseif($tipoArch == "xls" or $tipoArch == "xlsx"){
				}elseif($tipoArch == "ppt" or $tipoArch == "pptx"){
				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}
			}
	$nassign.= "
		});
	</script>";
	}else{
		$nassign = 'No se encontro informacion del caso';
	}
	return $nassign;
}

function obtenerAbiertosPorMi($usuario){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE USUARIO = '".$usuario."' AND ESTADO_CASO =1 ORDER BY ID_CASO DESC"; 
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);
	
	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			$mysql = new MySQL();
			$mysql->connect(); 
			$q = "SELECT COUNT(*) AS RESPUESTAS FROM casos_respuestas WHERE ID_CASO = '".$row['ID_CASO']."'"; 
			$rs = $mysql->query($q);
			$numero_resp = mysql_fetch_array($rs);
			
			if($row['ESTADO_AUTORIZACION'] != NULL || $row['AUTORIZADO_POR'] != NULL){
				$edo_caso = $row['ESTADO_AUTORIZACION'];
				$autorizo = $row['AUTORIZADO_POR'];
			}else{
				$edo_caso = "NO SOLICITADA";
				$autorizo = "NADIE";
			}
			$nassign .='["'.$row['ID_CASO'].'","'.$row['ASIGNADO_A'].'","'.addslashes($row['PROBLEMA']).'","'.substr($row['FECHA_APERTURA'],0,10).'","<p>'.addslashes($numero_resp['RESPUESTAS']).'&nbsp;&nbsp;<img src=\"../img/icono-mensaje.gif\" width=\"15px\" height=\"10\"/></p>", "'.$edo_caso.'", "'.$autorizo.'"],';
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{ "aaData": []}';
	}
	
	return $nassign;
}

function obtenerInfoCasoPM($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$respuestas = obtenerRespuestas($idCaso);
		$row = mysql_fetch_array($result);
		
		/******* se utiliza esto para obtener el nombre del departamento, primero se obtiene la clave y despues se hace la busqueda con ella ***/
		$mysql2 = new MySQL();
		$mysql2->connect(); 
		$q = "SELECT * FROM departamentos WHERE clave = '".$row['DEPTO']."'";
		$rs = $mysql2->query($q);
		$depto = mysql_fetch_array($rs);
		
		$archivosArr = explode(",", $row['ARCHIVOS']);
		$numArchivos = count($archivosArr);
		$nassign = "<table border='0' style='width:100%'>
		<tr><td class='tableLabels'>Caso No.</td><td class='tableLabels'>Fecha:</td><td class='tableLabels'>Hora</td></tr>
		<tr><td>".$row['ID_CASO']."</td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr><td class='tableLabels'>Asignado a: </td><td class='tableLabels'>Oficina</td><td class='tableLabels'>Depto.</td></tr>
		<tr><td>".$row['ASIGNADO_A']."</td><td>".$row['OFICINA']."</td><td>".$depto['DEPARTAMENTO']."</td>
		<tr><td colspan='2' class='tableLabels'>Problema: </td><td class='tableLabels'>T&eacute;lefono</td></tr>
		<tr><td colspan='2'>".$row['PROBLEMA']."</td><td>".$row['TELEFONO']."</td></tr>
		</table>
		<div id='accordionDesc' class='fuenteTable'>
			<h3><a href='#'>Descripcion del problema</a></h3>
			<div id='descripcionInfoCaso'>
				<table>
				<tr><td>".nl2br($row['DESCRIPCION'])."</td></tr>
				<tr><td><div id='respuestas'";
				if($respuestas != "<table style='width:100%;'></table>"){
					$nassign.= "style='background-color:#E6E6E6;border:1px solid blue;margin:10px;'";
				}
				$nassign.=">".nl2br($respuestas)."</div><input type='button' id='resp_btn' value='Responder'></button></td></tr>
				</table>
			</div>
		</div>";
		if($numArchivos > 0){
		$nassign .="
			<div id='accordionAdj'>
			<h3><a href='#'>Adjuntos</a></h3>
			<div id='gallery'>
				<ul>";
				foreach($archivosArr as $aA){
					$tipoArch = substr($aA,-4, 4);
					if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == ".jpeg"){
						$nassign .= "
						<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='#'>
								<img src='../uploads/".$aA."' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".pdf"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='img/icono_pdf.jpg' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".doc" or $tipoArch == "docx" or $tipoArch == ".odt"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='img/word_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".xls" or $tipoArch == "xlsx" or $tipoArch == ".ods"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='img/excel_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".ppt" or $tipoArch == "pptx" or $tipoArch == ".odp"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='img/powerpoint_icon.gif' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($aA == "NULL" or $aA == ""){
						$nassign .= "No Hay Archivos Adjuntos!";
					}else{
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='img/notepad.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}
				}
		$nassign.="</ul>
			<div id=adjOtrArDiv style='text-align:left;'><input id='file_upload' name='file_upload' type='file' /><span style='color:red;float:left;'>Maximo 2MB</span><br><br ><a href='javascript:$(\"#file_upload\").uploadifyUpload();' style='color:blue;'>Subir Archivos</a></div>
			</div>
		</div><br/> <!-- Fina div acordionAdj-->
		<div id='solicAuto' style='padding:15px;'><a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a></div>";
		}
	$nassign.= "<script>
		$(function() {
			$( '#solicAutoCancel' ).hide();
			
			$( '#resp_btn' ).button();
			$('#resp_btn').click(function(){
				$('#respuesta_ven').html(\"<form id='respuesta_form'>Respuesta:<br /><input type='hidden' name='id_caso' value='".$idCaso."'><textarea id='respuesta' name='respuesta' rows='5' maxlength='255' class='validate[required] sombras' style='resize:none;width:100%;'/></textarea></form>\");
				$('#respuesta_ven').dialog('open');
			});
			
			$( '#accordionAdj' ).accordion({
				collapsible: true,
				active: -1
			});
			
			$( '#accordionDesc' ).accordion({
				collapsible: true,
			});
			
			var adjuntos = '';
			var borrarAdj = '';
			$('#file_upload').uploadify({
				'uploader'    	 : '../js/uploadify/uploadify.swf',
				'script'      	 : '../js/uploadify/uploadify.php?nocaso='+".$row['ID_CASO'].",
				'cancelImg'      : '../js/uploadify/cancel.png',
				'folder'      	 : '../uploads',
				'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
				'fileDesc'    	 : 'Archivos de Ayuda',
				'buttonText'  	 : 'Adj. Otro Archivo',
				'scriptData': { 'session': '".session_id()."'},
				'sizeLimit'   	 : 2097152,
				'onAllComplete'  : function(event,data) {
							var adjuntosbr = adjuntos.replace(',', '<br>');
							$('#msgDialog').html('Se ha adjuntado correctamente el archivo(s)<br><br> '+adjuntosbr);
							$('#msgDialog').dialog('open');
							adjuntosbr='';
							adjuntos = '';
							$.ajax({
								url: '../php/agregarArchivos.php',
								data: 'agregar='+borrarAdj.slice(0,-1)+'&idc=".$row['ID_CASO']."',
								type: 'POST',
								success: function(data){
									if(data == 'OK'){
										$('#msgDialog').html('Archivo(s) subido con exito');
										$('#msgDialog').dialog('open');
									}
								},
								failure: function(){

								}
							});
						},
				'onError'   : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
				'onComplete'  : function(event, ID, fileObj, response, data) {
					adjuntos += fileObj.name+',';
					borrarAdj += 'CSO'+".$row['ID_CASO']."+'-'+fileObj.name+',';
					},
				'rollover'		 : false,
				'multi'			 : true,
				'hideButton'  	 : false
			});";
			foreach($archivosArr as $aA){
				if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == "jpeg"){
					$nassign .= 
					"$('#".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."').click(function(){
						$('#fotos').html(\"<img src='../uploads/".$aA."'/>\");
						$('#fotos').dialog('open');
					});";
				}elseif($tipoArch == "pdf"){

				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}elseif($tipoArch == "xls" or $tipoArch == "xlsx"){
				}elseif($tipoArch == "ppt" or $tipoArch == "pptx"){
				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}
			}
	$nassign.= "
		});
		
		$( '#solicAuto_link' ).live('click', function(){
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<tr><td><form id='solicAutoForm' onSubmit='return enviarSolAuto();'>Solicitar Autorizacion de: <input type='text' id='autorizacionde' name='autorizacionde' class='validate[required] sombras'/></form></td><td><a id='icono_envSolAuto' href='#' onclick='enviarSolAuto();'><img src='img/email_icon.png'></a></td><td><a id='cancelAuto_link' href='#'><img src='img/icono-cerrar.png' width='20px' height='20px' '/></a></td></tr>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		$( '#cancelAuto_link' ).live('click', function(){
			$('#solicAutoForm').validationEngine('hideAll')
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		function enviarSolAuto(){
			if($('#solicAutoForm').validationEngine('validate')){
				$.ajax({
					url:'../php/solicitarAutorizacion.php',
					data: $('#solicAutoForm').serialize()+'&id_caso='+".$idCaso.",
					type: 'POST',
					success: function(data){
						if(data == ''){
							$('#solicAuto').effect('highlight', {color:'#2EFE2E'}, 4000);
							$('#solicAuto').html('Se han enviado las solicitudes de autorizacion');
						}else{
							$('#solicAuto').effect('highlight', {color:'#FF0000'}, 4000);
							$('#solicAuto').html(data);
						}
					},
					failure: function(){
					}
				});
			}
			
			return false;
		}
	</script>";
	}else{
		$nassign = 'No se encontro informacion del caso';
	}
	return $nassign;
}


function obtenerRespuestas($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos_respuestas WHERE ID_CASO = '".$idCaso."' ORDER BY FECHA ASC";
	$result = $mysql->query($query);
	$respuestas = "<table style='width:100%;'>";
	while($row = mysql_fetch_array($result)){
		$respuestas .= "<tr><td><span style='color:red;'>".$row['USUARIO']."</span> <span style='color:green;'>".$row['FECHA']."</span><br/>".$row['RESPUESTA']."<br /></td></tr>";
	}
	return $respuestas .= "</table>";
}

function obtenerSinConfirmacionAdmin(){
	$mysql = new MySQL();
	$mysql->connect();
	
	$query = "SELECT * FROM casos WHERE ESTADO_CASO = 2 AND (ASIGNADO_A = '".$_SESSION['usuario']."' OR USUARIO = '".$_SESSION['usuario']."') ORDER BY ID_CASO ASC";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);
	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			if($row['USUARIO'] != $_SESSION['usuario']){
				$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.addslashes($row['PROBLEMA']).'","'.substr($row['FECHA_CIERRE'],0,10).'","'.substr($row['FECHA_CIERRE'], -8).'", "&nbsp;"],';
			}else{
				$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.addslashes($row['PROBLEMA']).'","'.substr($row['FECHA_CIERRE'],0,10).'","'.substr($row['FECHA_CIERRE'], -8).'", "<a href=\"../php/ver_cierra_caso.php?idcaso='.$row['ID_CASO'].'&usuario='.$_SESSION['usuario'].'\" targer=\"_blank\">Confirmar/Reabrir</a>"],';		
			}
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{ "aaData": []}';
	}
	return $nassign;
}

function obtenerAsignadosTodos(){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ASIGNADO_A != 'NO' AND ESTADO_CASO != 0 AND ESTADO_CASO != 3 ORDER BY ID_CASO DESC";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			$mysql = new MySQL();
			$mysql->connect(); 
			$q = "SELECT COUNT(*) AS RESPUESTAS FROM casos_respuestas WHERE ID_CASO = '".$row['ID_CASO']."'"; //A
			$rs = $mysql->query($q);
			$numero_resp = mysql_fetch_array($rs);
			
			if($row['ESTADO_AUTORIZACION'] != NULL || $row['AUTORIZADO_POR'] != NULL){
				$edo_caso = $row['ESTADO_AUTORIZACION'];
				$autorizo = $row['AUTORIZADO_POR'];
			}else{
				$edo_caso = "NO SOLICITADA";
				$autorizo = "NADIE";
			}
			
			$estado = "";
			if($row['ESTADO_CASO']==2){$estado = "ESPERANDO CONF.";}
			if($row['ESTADO_CASO']==1){$estado = "ABIERTO";}
			if($row['ESTADO_AUTORIZACION']=="ESPERANDO" && $row['ESTADO_CASO']==1){$estado = "ESPERANDO AUTORIZACION";}
			if($row['ESTADO_AUTORIZACION']=="AUTORIZADO" && $row['ESTADO_CASO']==1){$estado = "AUTORIZADO";}
			
			$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.addslashes($row['PROBLEMA']).'","'.$row['ASIGNADO_A'].'","'.$row['OFICINA'].'","'.substr($row['FECHA_APERTURA'],0,10).'","'.substr($row['FECHA_CIERRE'],0,10).'", "<p>'.$estado/*$numero_resp['RESPUESTAS'].'&nbsp;&nbsp;<img src=\"../img/icono-mensaje.gif\" width=\"15px\" height=\"10\"/></p>*/.'", "'.$autorizo.'"],';
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{ "aaData": []}';
	}
	return $nassign;
}

function obtenerInfoCasoSinResponderBoton($idCaso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$respuestas = obtenerRespuestas($idCaso);
		$row = mysql_fetch_array($result);
		
		/******* se utiliza esto para obtener el nombre del departamento, primero se obtiene la clave y despues se hace la busqueda con ella ***/
		$mysql2 = new MySQL();
		$mysql2->connect(); 
		$q = "SELECT * FROM departamentos WHERE clave = '".$row['DEPTO']."'";
		$rs = $mysql2->query($q);
		$depto = mysql_fetch_array($rs);
		
		
		$archivosArr = explode(",", $row['ARCHIVOS']);
		$numArchivos = count($archivosArr);
		$nassign = "<table border='0' style='width:100%'>
		<tr><td class='tableLabels'>Caso No.</td><td class='tableLabels'>Fecha:</td><td class='tableLabels'>Hora</td></tr>
		<tr><td>".$row['ID_CASO']."</td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr><td class='tableLabels'>Abierto por: </td><td class='tableLabels'>Oficina</td><td class='tableLabels'>Depto.</td></tr>
		<tr><td>".$row['NOMBRE']." (".$row['USUARIO'].")</td><td>".$row['OFICINA']."</td><td>".$depto['DEPARTAMENTO']."</td>
		<tr><td colspan='2' class='tableLabels'>Problema: </td><td class='tableLabels'>T&eacute;lefono</td></tr>
		<tr><td colspan='2'>".$row['PROBLEMA']."</td><td>".$row['TELEFONO']."</td></tr>
		</table>
		<div id='accordionDesc' class='fuenteTable'>
			<h3><a href='#'>Descripcion del problema</a></h3>
			<div id='descripcionInfoCaso'>
				<table>
				<tr><td>".$row['DESCRIPCION']."</td></tr>
				<tr><td><div id='respuestas'";
				if($respuestas != "<table style='width:100%;'></table>"){
					$nassign.= "style='background-color:#E6E6E6;border:1px solid blue;margin:10px;'";
				}
				$nassign.=">".$respuestas."</div><div style='background-color:#E6E6E6;margin-top:20px;padding:10px;'>Para responder al caso antes tiene que asignarse.</div></td></tr>
				</table>
			</div>
		</div>";
		if($numArchivos > 0){
		$nassign .="
			<div id='accordionAdj'>
			<h3><a href='#'>Adjuntos</a></h3>
			<div id='gallery'>
				<ul>";
				foreach($archivosArr as $aA){
					$tipoArch = substr($aA,-4, 4);
					if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == ".jpeg"){
						$nassign .= "
						<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='#'>
								<img src='../uploads/".$aA."' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".pdf"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/icono_pdf.jpg' width='72' height='72' alt='' />
							</a>
						</li>";
					}elseif($tipoArch == ".doc" or $tipoArch == "docx" or $tipoArch == ".odt"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/word_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".xls" or $tipoArch == "xlsx" or $tipoArch == ".ods"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/excel_icon.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($tipoArch == ".ppt" or $tipoArch == "pptx" or $tipoArch == ".odp"){
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/powerpoint_icon.gif' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}elseif($aA == "NULL" or $aA == ""){
						$nassign .= "No Hay Archivos Adjuntos!";
					}else{
						$nassign .= 
						"<li>
							<a title='".$aA."' id='".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."' href='../uploads/".$aA."' target='_blank'>
								<img src='../img/notepad.png' width='72' height='72' alt='".$aA."' />
							</a>
						</li>";
					}
				}
		$nassign.="</ul>
			<div id=adjOtrArDiv style='text-align:left;'><input id='file_upload' name='file_upload' type='file' /><span style='color:red;float:left;'>Maximo 2MB</span><br><br ><a href='javascript:$(\"#file_upload\").uploadifyUpload();' style='color:blue;'>Subir Archivos</a></div>
			</div>
		</div><br/> <!-- Fina div acordionAdj-->
		<div id='solicAuto' style='padding:15px;'><a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a></div>";
		}
	$nassign.= "<script>
		$(function(){
			$( '#solicAutoCancel' ).hide();
			
			$( '#resp_btn' ).button();
			$('#resp_btn').click(function(){
				$('#respuesta_ven').html(\"<form id='respuesta_form'>Respuesta:<br /><input type='hidden' name='id_caso' value='".$idCaso."'><textarea id='respuesta' name='respuesta' rows='5' maxlength='255' class='validate[required] sombras' style='resize:none;width:100%;'/></textarea></form>\");
				$('#respuesta_ven').dialog('open');
			});
			
			$( '#accordionAdj' ).accordion({
				collapsible: true,
				active: -1
			});
			
			$( '#accordionDesc' ).accordion({
				collapsible: true,
			});
			
			var adjuntos = '';
			var borrarAdj = '';
			$('#file_upload').uploadify({
				'uploader'    	 : '../js/uploadify/uploadify.swf',
				'script'      	 : '../js/uploadify/uploadify.php?nocaso='+".$row['ID_CASO'].",
				'cancelImg'      : '../js/uploadify/cancel.png',
				'folder'      	 : '../uploads',
				'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
				'fileDesc'    	 : 'Archivos de Ayuda',
				'buttonText'  	 : 'Adj. Otro Archivo',
				'scriptData': { 'session': '".session_id()."'},
				'sizeLimit'   	 : 2097152,
				'onAllComplete'  : function(event,data) {
							var adjuntosbr = adjuntos.replace(',', '<br>');
							$('#msgDialog').html('Se ha adjuntado correctamente el archivo(s)<br><br> '+adjuntosbr);
							$('#msgDialog').dialog('open');
							adjuntosbr='';
							adjuntos = '';
							$.ajax({
								url: '../php/agregarArchivos.php',
								data: 'agregar='+borrarAdj.slice(0,-1)+'&idc=".$row['ID_CASO']."',
								type: 'POST',
								success: function(data){
									if(data == 'OK'){
										$('#msgDialog').html('Archivo(s) subido con exito');
										$('#msgDialog').dialog('open');
									}
								},
								failure: function(){

								}
							});
						},
				'onError'   : function (event,ID,fileObj,errorObj) {alert(errorObj.type + ' Error: ' + errorObj.info);},
				'onComplete'  : function(event, ID, fileObj, response, data) {
					adjuntos += fileObj.name+',';
					borrarAdj += 'CSO'+".$row['ID_CASO']."+'-'+fileObj.name+',';
					},
				'rollover'		 : false,
				'multi'			 : true,
				'hideButton'  	 : false
			});";
			foreach($archivosArr as $aA){
				if($tipoArch == ".jpg" or $tipoArch == ".png" or $tipoArch == ".gif" or $tipoArch == "jpeg"){
					$nassign .= 
					"$('#".preg_replace('/[^A-Za-z0-9_]/', '', substr($aA,0,-4))."').click(function(){
						$('#fotos').html(\"<img src='../uploads/".$aA."'/>\");
						$('#fotos').dialog('open');
					});";
				}elseif($tipoArch == "pdf"){

				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}elseif($tipoArch == "xls" or $tipoArch == "xlsx"){
				}elseif($tipoArch == "ppt" or $tipoArch == "pptx"){
				}elseif($tipoArch == "doc" or $tipoArch == "docx"){
				}
			}
	$nassign.= "
		});
		
		$( '#solicAuto_link' ).live('click', function(){
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<tr><td><form id='solicAutoForm' onSubmit='return enviarSolAuto();'>Solicitar Autorizacion de: <input type='text' id='autorizacionde' name='autorizacionde' class='validate[required] sombras'/></form></td><td><a id='icono_envSolAuto' href='#' onclick='enviarSolAuto();'><img src='../img/email_icon.png'></a></td><td><a id='cancelAuto_link' href='#'><img src='../img/icono-cerrar.png' width='20px' height='20px' '/></a></td></tr>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		$( '#cancelAuto_link' ).live('click', function(){
			$('#solicAutoForm').validationEngine('hideAll')
			$( '#solicAuto' ).hide(500, function(){
				$( '#solicAuto' ).html(\"<a id='solicAuto_link' style='color:red; font-weight:bold; size:14px;' href='#'>Solicitar Autorizacion</a>\");
				$( '#solicAuto' ).show(500);
			});
		});
		
		function enviarSolAuto(){
			if($('#solicAutoForm').validationEngine('validate')){
				$.ajax({
					url:'../php/solicitarAutorizacion.php',
					data: $('#solicAutoForm').serialize()+'&id_caso='+".$idCaso.",
					type: 'POST',
					success: function(data){
						if(data == ''){
							$('#solicAuto').effect('highlight', {color:'#2EFE2E'}, 4000);
							$('#solicAuto').html('Se han enviado las solicitudes de autorizacion');
						}else{
							$('#solicAuto').effect('highlight', {color:'#FF0000'}, 4000);
							$('#solicAuto').html(data);
						}
					},
					failure: function(){
					}
				});
			}
			
			return false;
		}
	</script>";
	}else{
		$nassign = 'No se encontro informacion del caso';
	}
	return $nassign;
}

function obtenerOfinaDeCaso($num_caso){
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT oficina FROM casos WHERE ID_CASO = '".$num_caso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);
	
	if($numero >0){
		//$nassign = '{"datos":[';
		while($row = mysql_fetch_array($result)){
			$nassign = $row['oficina'];
		}

		//$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{"datos": []}';
	}
	return $nassign;
}
?>
