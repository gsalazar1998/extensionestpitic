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
require_once("../../../php/MySQL/MySQL.class.dbmsql.php"); 
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
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
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
   
	$( "#fcad" ).datepicker();
	$( "#fverif" ).datepicker();
	$( "#finicial" ).datepicker();
	$( "#ffinal" ).datepicker();
	/***********************************/

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
				</ul>
			</div>
		</div>
		<!-- end #header -->
		<div id="page">
			<div id="content"><br /><br /><h2>Archivo de Facturas...</h2></div>
			<div style="clear: both;"></div>
			<div id="subeFactura" style="width:800px; position:relative; left:70px;">
				<form id="busquedaFacturas" action="listarArchivos.php" method="post">
				<table id="filtros" style="width:500px;">
				<tr><td><label for="proveedor">Proveedor</label></td><td><input type="text" id="proveedor" name="proveedor" /></td></tr>
				<tr><td><label for="rfc">RFC</label></td><td><input type="text" id="rfc" name="rfc" maxlength="13"/></td></tr>
				<tr><td><label for="uverifico">Usuario que verifico</label></td><td><input type="text" id="uverifico" name="uverifico" /></td></tr>
				<!--<tr><td><label for="fcad">Fecha Caducidad</label></td><td><input type="text" id="fcad" name="fcad" /></td></tr>-->
				<tr><td><label for="fverif">Fecha Verificaci&oacute;n</label></td><td><input type="text" id="fverif" name="fverif" /></td></tr>
				<tr><td colspan="2"><strong>Fechas De Registro:</strong></td></tr>
				<tr><td style="padding-left:12px;"><label for="finicial">Fecha Inicial</label></td><td><input type="text" id="finicial" name="finicial" /></td></tr>
				<tr><td style="padding-left:12px;"><label for="ffinal">Fecha Final</label></td><td><input type="text" id="ffinal" name="ffinal" /></td></tr>
				<tr><td colspan="2"><input type="submit" id="buscar_btn" name="buscar_btn" value="Buscar" align="rigth" /></td></tr>
				</table>
				</form>
				<div id="info">
					<?php
					//filtros de busqueda
					$proveedor = isset($_POST['proveedor']) ? $_POST['proveedor'] : null;
					$finicio = isset($_POST['finicial']) ? $_POST['finicial'] : null;
					$ffinal = isset($_POST['ffinal']) ? $_POST['ffinal'] : null;
					$fcaduc = isset($_POST['fcad']) ? $_POST['fcad'] : null;
					$fverif = isset($_POST['fverif']) ? $_POST['fverif'] : null;
					$rfc = isset($_POST['rfc']) ? $_POST['rfc'] : null;
					$uverifico = isset($_POST['uverifico']) ? $_POST['uverifico'] : null;
					//*************************************************/

					//Limito la busqueda
					$TAMANO_PAGINA = 5;

					//examino la página a mostrar y el inicio del registro a mostrar
					$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : null;
					if ($pagina == null) {
						$inicio = 0;
						$pagina=1;
					}
					else {
						$inicio = ($pagina - 1) * $TAMANO_PAGINA;
					}

					$ssql = "SELECT * FROM facturas WHERE 1 = 1";
					if($proveedor != null){
						$ssql .= " AND proveedor = '".$proveedor."'";
					}
					if($finicio != null && $ffinal){
						$ssql .= " AND FECHA_REGISTRO BETWEEN '".$finicio."' AND '".$ffinal."'";
					}
					/*if($fcaduc != null){
						$ssql .= " AND IMP_FECHA_CADUC = '".$fcaduc."'";
					}*/
					if($fverif != null){
						$ssql .= " AND FECHA_VERIF = '".$fverif."'";
					}
					if($rfc != null){
						$ssql .= " AND RFC = '".$rfc."'";
					}
					if($uverifico != null){
						$ssql .= " AND USUARIO_VERIF = '".$uverifico."'";
					}

					$conn = mysql_connect("dbmsql.transportespitic.com","adminusertpitic","adminusertpitic");
					mysql_select_db("facturas",$conn);
					$rs = mysql_query($ssql,$conn);
					$num_total_registros = mysql_num_rows($rs);

					//calculo el total de páginas
					$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);

					//construyo la sentencia SQL
					$ssql = "SELECT * FROM facturas WHERE 1 = 1";
					if($proveedor != null){
						$ssql .= " AND proveedor = '".$proveedor."'";
					}
					if($finicio != null && $ffinal){
						$ssql .= " AND FECHA_REGISTRO BETWEEN '".$finicio."' AND '".$ffinal."'";
					}
					/*if($fcaduc != null){
						$ssql .= " AND IMP_FECHA_CADUC = '".$fcaduc."'";
					}*/
					if($fverif != null){
						$ssql .= " AND FECHA_VERIF = '".$fverif."'";
					}
					if($rfc != null){
						$ssql .= " AND RFC = '".$rfc."'";
					}
					if($uverifico != null){
						$ssql .= " AND USUARIO_VERIF = '".$uverifico."'";
					}

					$ssql .= " limit " . $inicio . "," . $TAMANO_PAGINA;
					//$ssql .= " limit " . $TAMANO_PAGINA . "," . $inicio;
					$rs = mysql_query($ssql);
					$numero = mysql_num_rows($rs);
					if($numero > 0){
						$facturas = 
						"<br /><br /><br />
						<table style='border:solid black 1px; padding:5px;'>
						<thead><tr style='font-size:10px;font-weight:bold;'>
							<th>ID REGISTRO</th>
							<th>FECHA REGISTRO</th>
							<th>CUENTA COI</th>
							<th>RFC</th>
							<th>PROVEEDOR</th>
							<!--<th>FOLIOS AUTORIZADOS</th>-->
							<!--<th>FECHA CADUCIDAD</th>-->
							<th>FECHA VERIFICACION</th>
							<th>USUARIO VERIFICO</th>
							<th>PDF</th>
							<th>FACTURA</th>
						</tr></tehead>";
						
						$j = 0;
						while ($row = mysql_fetch_array($rs)){
							$facturas .= "<tr bgcolor='#";
								 if($j%2==0){
									$facturas .= "F5D0A9'";
								}else{
									$facturas .= "D0F5A9'";
								}
								$facturas .= " >
								<td>&nbsp;&nbsp;".$row['ID_REGISTRO']."&nbsp;&nbsp;</td>
								<td>&nbsp;&nbsp;".$row['FECHA_REGISTRO']."&nbsp;&nbsp;</td>
								<td>&nbsp;&nbsp;".$row['CUENTA_COI']."&nbsp;&nbsp;</td>
								<td>&nbsp;&nbsp;".$row['RFC']."&nbsp;&nbsp;</td>
								<td>&nbsp;&nbsp;".$row['PROVEEDOR']."&nbsp;&nbsp;</td>
								<!--<td>&nbsp;&nbsp;".$row['IMP_FOLIOS_AUT']."&nbsp;&nbsp;</td>-->
								<!--<td>&nbsp;&nbsp;".$row['IMP_FECHA_CADUC']."&nbsp;&nbsp;</td>-->
								<td>&nbsp;&nbsp;".$row['FECHA_VERIF']."&nbsp;&nbsp;</td>
								<td>&nbsp;&nbsp;".$row['USUARIO_VERIF']."&nbsp;&nbsp;</td>
								<td><a href='facturas/".$row['RUTA_IMG']."' target='_blank'>&nbsp;&nbsp;".$row['RUTA_IMG']."&nbsp;&nbsp;</a></td>
								<td>&nbsp;&nbsp;<a href='descargar_archivo.php?id=".$row['ID_REGISTRO']."&nombre=".$row['PROVEEDOR']."'>Descargar</a>&nbsp;&nbsp;</td>
							</tr>";
							
							$j++;
						}
						$facturas .= "</table>";
						echo $facturas;
						//cerramos el conjunto de resultado y la conexión con la base de datos
						mysql_free_result($rs);
						mysql_close($conn);
						
						$indexes = "";
						//muestro los distintos índices de las páginas, si es que hay varias páginas
						if ($total_paginas >= 1){
							for ($i=1;$i<=$total_paginas;$i++){
							   if ($pagina == $i)
								  //si muestro el índice de la página actual, no coloco enlace
								 $indexes .= $pagina . " ";
							   else
								  //si el índice no corresponde con la página mostrada actualmente, coloco el enlace para ir a esa página
								  $indexes.= "<a href='listarArchivos.php?pagina=" . $i . "'> " . $i . " </a>";
							}
						} 
						echo "<br />P&aacute;ginas:  ".$indexes;
						echo "<br /><br /><br /><a href='subirfactura.php'>Registrar una Factura</a>";
					}else{
						echo "<center><br /><br />No se encontraron resultados con los filtros espec&iacute;ficos</center>";
						echo "<br /><br /><br /><a href='subirfactura.php'>Registrar una Factura</a>";
					}
					?>
				</div>
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
</body>
</html>
