<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");
require('funciones_generales.php');
$objFN = new FuncionesGrales();
$idCaso = 40;

function obtenerInfoCaso($idCaso, $cod, $usuario){ 
	$mysql = new MySQL();
	$mysql->connect(); 

	$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	if($numero >0){
		$respuestas = obtenerRespuestas($idCaso);
		$row = mysql_fetch_array($result);
		$archivosArr = explode(",", $row['ARCHIVOS']);
		$numArchivos = count($archivosArr);
		$nassign = "<table border='0' style='width:100%'>
		<tr><td class='tableLabels'>caso No.</td><td class='tableLabels'>Fecha:</td><td class='tableLabels'>Hora</td></tr>
		<tr><td>".$row['ID_CASO']."</td><td>".substr($row['FECHA_APERTURA'],0,10)."</td><td>".substr($row['FECHA_APERTURA'], -8)."</td></tr>
		<tr><td class='tableLabels'>Abierto por: </td><td class='tableLabels'>Oficina</td><td class='tableLabels'>Depto.</td></tr>
		<tr><td>".$row['NOMBRE']." (".$row['USUARIO'].")</td><td>".$row['OFICINA']."</td><td>".$row['DEPTO']."</td>
		<tr><td colspan='3' class='tableLabels'>Problema: </td></tr>
		<tr><td colspan='3'>".$row['PROBLEMA']."</td></tr>
		</table>
		<div id='accordionDesc' class='fuenteTable' style='width:100%'>
			<h3 width=100%><a href='#'>Descripcion del problema</a></h3>
			<div id='descripcion'>
				<table>
				<tr><td>".$row['DESCRIPCION']."</td></tr>
				<tr><td><div id='respuestas'";
				if($respuestas != "<table style='width:100%;'></table>"){
					$nassign.= "style='background-color:#E6E6E6;border:1px solid blue;margin:10px;'";
				}
				$nassign.=">".$respuestas."</div><input type='button' id='resp_btn' value='Agregar Comentario'></button></td></tr>
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
			<div id='adjOtrArDiv' style='text-align:left;'><input id='file_upload' name='file_upload' type='file' /><span style='color:red;float:left;'>Maximo 2MB</span><br><br ><a href='javascript:$(\"#file_upload\").uploadifyUpload();' style='color:blue;'>Subir Archivos</a></div>
			</div>
		</div><br/> <!-- Fina div acordionAdj-->
		<div id='botones_autorizar'>
			<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idCaso."&uid=".$usuario."&aut=1'><img src='http://sistemas.tpitic.com.mx/HelpDesk/img/autorizar_btn.png' border='0' /></a>&nbsp;&nbsp;&nbsp;
	
	<a href='http://sistemas.tpitic.com.mx/HelpDesk/php/autorizar_caso.php?cod=".$cod."&idcaso=".$idCaso."&uid=".$usuario."&aut=0'><img src='http://sistemas.tpitic.com.mx/HelpDesk/img/rechazar.png' border='0'/></a>
		</div>";
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
		$respuestas .= "<tr><td><span style='color:red;'>".$row['USUARIO']."</span><br/>".$row['RESPUESTA']."<br /></td></tr>";
	}
	return $respuestas .= "</table>";
}

function obtenerSinConfirmacionAdmin(){
	$mysql = new MySQL();
	$mysql->connect();
	
	$query = "SELECT * FROM casos WHERE ESTADO_CASO = 2 AND ASIGNADO_A = '".$_SESSION['usuario']."'ORDER BY ID_CASO ASC";
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);
	if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			if($row['USUARIO'] != $_SESSION['usuario']){
				$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.$row['PROBLEMA'].'","'.substr($row['FECHA_APERTURA'],0,10).'","'.substr($row['FECHA_APERTURA'], -8).'", "&nbsp;"],';
			}else{
				$nassign .='["'.$row['ID_CASO'].'","'.$row['USUARIO'].'","'.$row['PROBLEMA'].'","'.substr($row['FECHA_APERTURA'],0,10).'","'.substr($row['FECHA_APERTURA'], -8).'", "<a href=\"../php/ver_cierra_caso.php?idcaso='.$row['ID_CASO'].'&usuario='.$_SESSION['usuario'].'\" targer=\"_blank\">Confirmar/Reabrir</a>"],';		
			}
		}

		$nassign = substr($nassign,0, -1)."]}";
	}else{
		$nassign = '{ "aaData": []}';
	}
	return $nassign;
}

session_start();
if(isset($_SESSION['usuario'])) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
				font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; margin: 5px;
				background-color: white;
				padding: 10px;
				width: 97.5%;
				height:200px;
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
				width: 97.5%;;
				height:150px;;
			}
			/************************************************/
			td {font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; margin: 5px;padding:5px;}
			.tableLabels{
				background-color:#FAAC58;
				color:white;
			}
			thead tfoot{
				margin: 5px;
			}

			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
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
		<script type="text/javascript" src="../js/casos_admin.js"></script>
		<!-- DataTables -->
		<script type="text/javascript" language="javascript" src="../js/datatables/media/js/jquery.dataTables.js"></script>
	</head>
	<body id="dt_example" class="fuente">
		<center><div style="width:70%;"><?php echo obtenerInfoCaso($_GET['idcaso'], $_GET['cod'], $_GET['uid']); ?></div></center>
		<div id="asignacaso_ventana" title="Asignar a.." class="fuente"></div><!--ventana para asignar/Reasignar caso -->
		<div id="msgDialog" title="Mensaje" class="fuente"></div><!--Ventana de Dialogos -->
		<div id="infoCaso" title="Informacion del caso" class="fuente">
		</div><!-- ventana que muestra la informacion del caso-->
		<div id="fotos" class="fuente"></div><!-- ventana de las fotos cuando se hacen grandes-->
		<div id="respuesta_ven" title="Agregar Comentario" class="fuente"></div>
	</body>
</html>
<?php }else {
		echo "<script>window.location = 'index_m_caso_auto.php'</script>";
	}
?>
