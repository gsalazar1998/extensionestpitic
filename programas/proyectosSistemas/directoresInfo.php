<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	session_start();
	/*if(!isset($_SESSION['usuario'])){
		echo "<script>window.location = 'index.php'; </script>";
	}*/
	$_SESSION["usuario"]="cmburboa";
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
	<script type='text/javascript' src='../../js/osx.js'></script>
	<script type='text/javascript' src='../../js/jquery.simplemodal.js'></script>
<!-- DataTables -->
	<script type="text/javascript" language="javascript" src="../../HelpDesk/js/datatables/media/js/jquery.dataTables.js"></script>
	<link type="text/css" href="../../HelpDesk/js/datatables/media/css/demo_page.css" rel="stylesheet" />
	<link type="text/css" href="../../HelpDesk/js/datatables/media/css/demo_table_jui.css" rel="stylesheet" />	
	<!--<link type="text/css" href="../js/datatables/css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />-->
	<style>
		.naranjaback{
			background-color: #FAAC58;
			color: white;
		}
		
		.yellowback{
			background-color: #BCBC0F;
			color: white;
		}
		
		.greenback{
			background-color: #0FBC65;
			color: white;
		}
		
		.centrado{
			text-align:center;
		}
	</style>
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
/********* para las fechas *********/
	var natDays = [[9, 16], [11, 20], [12, 25], [1, 1]];

	function nationalDays(date) {
			for (i = 0; i < natDays.length; i++) {
					if (date.getMonth() == natDays[i][0] - 1
					&& date.getDate() == natDays[i][1]) {
							return [false, natDays[i][2] + '_day'];
					}
			}
			return [true, ''];
	}

	function noWeekendsOrHolidays(date) {
			var noWeekend = $.datepicker.noWeekends(date);
			if (noWeekend[0]) {
					return nationalDays(date);
			} else {
					return noWeekend;
			}
	}

		
		
	 $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
      dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
      weekHeader: 'Sm',
      dateFormat: 'yy/mm/dd',
      firstDay: 0,
      isRTL: false,
      showMonthAfterYear: false,
	  beforeShowDay: noWeekendsOrHolidays,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
  
	/**================================================**/
	
$(function(){
	$( "#tabs" ).tabs();
	$("#finicio").datepicker();
	$("#comentar_ventana").dialog({
		autoOpen: false,
		width: 500,
		draggable: true,
		resizeable: false,
		buttons:{
			"Comentar": function(){
				$.ajax({
					url:"obtenerInfoProyectos.php?flag=4",
					type:"POST",
					data:"idcaso="+selecteds+"&comentario="+$("#comentariotext").val(),
					success:function(data){
						if(data=="OK"){
							$("#msgDialog").html("Comentario agregado Correctamente");
							$.ajax({
								url: "obtenerInfoProyectos.php?flag=5",
								type: "post",
								data: "idcaso="+selecteds,
								success:function(data){
									$("#comResp").html(data);
									$("#comResp").effect("highlight", {}, 3000);
									$('#comentariotext').val('');
									$('#comentariotext').animate({scrollTop: '5000000px'},1500);
								},
								failure:function(){
									alert("Hubo un error en la peticion de obtener respuestas");
								}
							})	
						}
					}
				})
				$(this).dialog("close");
			},
			"Cancelar": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

//cuando se escribe un comentario para rechazar el caso 
//en esta parte se hace una llamada ajax al php con el flag 6 para llamar la funcion qeu cambia
//el estatus del caso a 0 (cerrado)al dar click en Aceptar
	$("#rechazadoComent").dialog({
		autoOpen: false,
		width: 500,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				$validacion = $("#comentRechForm").validationEngine('validate');
				if($validacion){
					$.ajax({
						url :'obtenerInfoProyectos.php?flag=6',
						type: 'post',
						data: "idcaso="+selecteds+"&rechCom="+$("#explicacionRechazo").val(),
						success: function(data){
							if(data=="OK"){
								$("#msgDialog").html("El caso ha sido rechazado!");
								$('#comentRechForm').validationEngine('hideAll');
								$("#infoProyecto").dialog("close");
								proyectosOpe.fnReloadAjax(); 
								$("#explicacionRechazo").val("");
								$("#msgDialog").dialog("open");
							}
						},
						failure: function(){	
						
						}
					});
					$(this).dialog("close");
				}
			},
			"Cancelar": function(){
				$('#comentRechForm').validationEngine('hideAll');
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});
	
	$("#reprogramarProy").dialog({
		autoOpen: false,
		width: 300,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				/* $validacion = $("#comentRechForm").validationEngine('validate');
				if($validacion){
					$.ajax({
						url :'obtenerInfoProyectos.php?flag=6',
						type: 'post',
						data: "idcaso="+selecteds+"&rechCom="+$("#explicacionRechazo").val(),
						success: function(data){
							if(data=="OK"){
								$("#msgDialog").html("El caso ha sido rechazado!");
								$('#comentRechForm').validationEngine('hideAll');
								$("#infoProyecto").dialog("close");
								proyectosOpe.fnReloadAjax(); 
								$("#explicacionRechazo").val("");
								$("#msgDialog").dialog("open");
							}
						},
						failure: function(){	
						
						}
					});
					$(this).dialog("close");
				} */
			},
			"Cancelar": function(){
				$('#comentRechForm').validationEngine('hideAll');
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

	$("#msgDialog").dialog({
		autoOpen: false,
		width: 300,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				proyectosOpe.fnReloadAjax();
				proyectosAdm.fnReloadAjax();
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

	$("#infoProyecto").dialog({
		autoOpen: false,
		width: 800,
		height:700,
		draggable: true,
		resizeable: false,
		buttons:{
			/*"Asignar": function(){
				$("#asignaproy_ventana").dialog('open');
				//$(this).dialog("close");
			},*/
			"Rechazar": function(){
				$("#rechazadoComent").dialog('open');
			},
			"Cerrar ventana": function(){
				$(this).dialog("close");
				//location.reload(true);
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});
	
	$("#asignaproy_ventana").dialog({
		autoOpen: false,
		width: 300,
		draggable: true,
		resizeable: false,
		buttons:{
			"Asignar": function(){
				$.ajax({
					url: "obtenerInfoProyectos.php?flag=3",
					type: "POST",
					data: "usuario="+$("#userAsigna").val()+"&"+"idcaso="+selecteds,
					success:function(data){
						if(data == "OK"){
							$("#msgDialog").html('proyecto asignado');
							$("#msgDialog").dialog('open');
							$("#infoProyecto").dialog('close');
							proyectosOpe.fnReloadAjax(); 
						}
					},
					failure:function(){
						alert("Hubo un error en la peticion ajax");
					}
				})
				
				$(this).dialog("close");
			},
			"Cancelar": function(){
				$(this).dialog("close");
				//location.reload(true);
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});
	
	//tabla 1
	var proyectosOpe;
	var gaiSelectedOpe =  [];
	var selectedsOpe = "";
	
	$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw ){
		if ( typeof sNewSource != 'undefined' && sNewSource != null )
		{
			oSettings.sAjaxSource = sNewSource;
		}
		this.oApi._fnProcessingDisplay( oSettings, true );
		var that = this;
		var iStart = oSettings._iDisplayStart;
	
		oSettings.fnServerData( oSettings.sAjaxSource, null, function(json) {
			/* Clear the old information from the table */
			that.oApi._fnClearTable( oSettings );
		
			/* Got the data - add it to the table */
			for ( var i=0 ; i<json.aaData.length ; i++ )
			{
				that.oApi._fnAddData( oSettings, json.aaData[i] );
			}
		
			oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
			that.fnDraw( that );
		
			if ( typeof bStandingRedraw != 'undefined' && bStandingRedraw === true )
			{
				oSettings._iDisplayStart = iStart;
				that.fnDraw( false );
			}
		
			that.oApi._fnProcessingDisplay( oSettings, false );
		
			/* Callback user function - for event handlers etc */
			if ( typeof fnCallback == 'function' && fnCallback != null )
			{
				fnCallback( oSettings );
			}
		} );
	}
	
	var proyectosOpe = $('#proyectosOpe').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"aaSorting": [[ 1, "asc" ]],
		"sAjaxSource": 'obtenerInfoProyectos.php?flag=1&area=O',
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		if ( jQuery.inArray(aData[0], gaiSelectedOpe) != -1 ){
					$(nRow).addClass('row_selected');
			}
				return nRow;
		},
		"bAutoWidth": false,
		"aoColumns": [
			{ "bVisible": false }, /* Columna de ID */
			{"sWidth":"5%"},
			{"sWidth":"10%"},
			{"sWidth":"20%"},
			{"sWidth":"20%"},
			{"sWidth":"20%"},
		],
	});
	
	$('#proyectosOpe tbody tr ').live('click', function () {
		//$("#link_asignar").html("&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		//$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
		//"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		
		var aData = proyectosOpe.fnGetData(this );
		var iId = aData[0];
		selecteds = iId;
		
		$('#proyectosOpe tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, gaiSelectedOpe) == -1 )
		{
			gaiSelectedOpe[0] = iId;
			numcaso = iId;
			//alert(gaiSelectedOpe.length);
			/*if(gaiSelectedOpe.length>0){
				if(gaiSelectedOpe.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}*/
		}
		else
		{
			/*gaiSelectedOpe = jQuery.grep(gaiSelectedOpe, function(value) {
				return value != iId;
			});*/
			
			//alert(gaiSelectedOpe.length);
			/*if(gaiSelectedOpe.length>0){
				if(gaiSelectedOpe.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}
			if(gaiSelectedOpe.length == 0){
				$("#link_asignar").html("");
			}*/
		}
		
		//$("#asignacaso_ventana").html("casos: "+gaiSelectedOpe+"<br /><br /><form id='asignarcasoa' onsubmit='return: false'><input type='hidden' id='casos' name='casos' value='"+gaiSelectedOpe+"' /><table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'><option value='cmburboa'>cmburboa</option><option value='acota'>acota</option><option value='oamaya'>oamaya</option><option value='fmorelos'>fmorelos</option><option value='iaguiar'>iaguiar</option><option value='gesquivel'>gesquivel</option><option value='cquintero'>cquintero</option><option value='evalenzuela'>evaluenzuela</option><option value='zalvarez'>zalvarez</option><option value='jferia'>jferia</option><option value='dwong'>dwong</option></select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
		//========================= EN ESTA PARTE HARE LA LLAMADA AL PHP QUE ME TRAERA TODA LA INFORMACION DEL CASO ======================//
		$.ajax({
			url: 'obtenerInfoProyectos.php?flag=8',
			type: 'post',
			data: 'idcasodes='+gaiSelectedOpe,
			success: function(data){
				$("#infoProyecto").html(data);
				$('#infoProyecto').dialog('open');
			},
			failure: function(data){
			}
		})
		
		//$('#infoProyecto').dialog('open');
	});
	
	
	
	//PROYECTOS ADMINISTRACION ===============================================
	//tabla 2 (Administrativos)
	var proyectosAdm;
	var gaiSelectedAdm =  [];
	var selectedsAdm = "";	
	
	var proyectosAdm = $('#proyectosAdm').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"aaSorting": [[ 1, "asc" ]],
		"sAjaxSource": 'obtenerInfoProyectos.php?flag=1&area=A',
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		if ( jQuery.inArray(aData[0], gaiSelectedAdm) != -1 ){
					$(nRow).addClass('row_selected');
			}
				return nRow;
		},
		"bAutoWidth": false,
		"aoColumns": [
			{ "bVisible": false }, /* Columna de ID */
			{"sWidth":"5%"},
			{"sWidth":"10%"},
			{"sWidth":"20%"},
			{"sWidth":"20%"},
			{"sWidth":"20%"},
		],
	});
	
	$('#proyectosAdm tbody tr ').live('click', function () {
		//$("#link_asignar").html("&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		//$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
		//"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		
		var aData = proyectosAdm.fnGetData(this );
		var iId = aData[0];
		selecteds = iId;
		
		$('#proyectosAdm tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, gaiSelectedAdm) == -1 )
		{
			gaiSelectedAdm[0] = iId;
			numcaso = iId;
			//alert(gaiSelectedAdm.length);
			/*if(gaiSelectedAdm.length>0){
				if(gaiSelectedAdm.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}*/
		}
		else
		{
			/*gaiSelectedAdm = jQuery.grep(gaiSelectedAdm, function(value) {
				return value != iId;
			});*/
			
			//alert(gaiSelectedAdm.length);
			/*if(gaiSelectedAdm.length>0){
				if(gaiSelectedAdm.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}
			if(gaiSelectedAdm.length == 0){
				$("#link_asignar").html("");
			}*/
		}
		
		//$("#asignacaso_ventana").html("casos: "+gaiSelectedAdm+"<br /><br /><form id='asignarcasoa' onsubmit='return: false'><input type='hidden' id='casos' name='casos' value='"+gaiSelectedOpe+"' /><table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'><option value='cmburboa'>cmburboa</option><option value='acota'>acota</option><option value='oamaya'>oamaya</option><option value='fmorelos'>fmorelos</option><option value='iaguiar'>iaguiar</option><option value='gesquivel'>gesquivel</option><option value='cquintero'>cquintero</option><option value='evalenzuela'>evaluenzuela</option><option value='zalvarez'>zalvarez</option><option value='jferia'>jferia</option><option value='dwong'>dwong</option></select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
		//========================= EN ESTA PARTE HARE LA LLAMADA AL PHP QUE ME TRAERA TODA LA INFORMACION DEL CASO ======================//
		$.ajax({
			url: 'obtenerInfoProyectos.php?flag=8',
			type: 'post',
			data: 'idcasodes='+gaiSelectedAdm,
			success: function(data){
				$("#infoProyecto").html(data);
				$('#infoProyecto').dialog('open');
			},
			failure: function(data){
			}
		})
		
		//$('#infoProyecto').dialog('open');
	});
});

function deshabilitarcontroles(){
	indice = document.vacacionesCorreo.status.options[document.vacacionesCorreo.status.selectedIndex].value;
	
	if(indice == 3){
		document.vacacionesCorreo.forward.disabled=true;
		document.vacacionesCorreo.mensaje.disabled=true;
	}else{
		document.vacacionesCorreo.forward.disabled=false;
		document.vacacionesCorreo.mensaje.disabled=false;
	}
}
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
			<div>
				<div class="post">
					<h2 class="title" style="width:500px;position:relative; left:80px;"><a href="#">Solicitudes para proyectos</a></h2>
					<div id="entryt" class="entry" style="width:840px;position:relative; left:60px;">
						<div id="tabs">
										<ul>
											<li><a href="#tab-1" class="fuente">Operativos</a></li>
											<li><a href="#tab-2" class="fuente">Administrativos</a></li>

										</ul>
							<div id="tab-1" style="padding: 0 0 0 0;">
								<table cellpadding="0" cellspacing="0" border="0" style="color: black;" class="display" id="proyectosOpe" style="width:100%;">
									<thead>
										<tr>
											<th>ID</th>
											<th>Prioridad</th>
											<th>Usuario</th>
											<th>Oficina</th>
											<th>Direcci&oacute;n</th>
											<th>Fecha Inicio</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
										</tr>
									</tfoot>
									<tbody>
					
									</tbody>
								</table>
							</div>
							<div id="tab-2" style="padding: 0 0 0 0;">
								<table cellpadding="0" cellspacing="0" border="0" style="color: black;" class="display" id="proyectosAdm" style="width:100%;">
									<thead>
										<tr>
											<th>ID</th>
											<th>Prioridad</th>
											<th>Usuario</th>
											<th>Oficina</th>
											<th>Direcci&oacute;n</th>
											<th>Fecha Inicio</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
										</tr>
									</tfoot>
									<tbody>
					
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br /><br /><br /><br />
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
<div id="msgDialog" title="Mensaje de Informaci&oacute;n"></div>
<div id="reprogramarProy" title="Mensaje de Informaci&oacute;n">
</div>
<div id="comentar_ventana" title="Ingrese aqu&iacute; su comentario">
	<textarea id="comentariotext" name="comentariotext" style="width:96%;height:250px;"></textarea>
</div>
<!--<div id="asignaproy_ventana" title="Asignar Proyecto a.." class="fuente" ></div>
	<form id="asignar" name="asignar" style="display:none;">
		<center><select id="userAsigna" name="userAsigna" style="width:200px;">
		<option value="cmburboa">cmburboa</option>
		<option value="acota">acota</option>
		<option value="cquintero">cquintero</option>
		<option value="iaguiar">iaguiar</option>
		</select></center>
	</form>
</div>ventana para asignar/Reasignar proyecto -->
<div id="rechazadoComent" title="Explicaci&oacute;n de porque se rechaza">
	<form id="comentRechForm" name="comentRechForm">
	Escriba a continuaci&oacute;n la raz&oacute;n de la no factibilidad del proyecto o el motivo por el que se rechaza:<br /><br />
	<textarea id="explicacionRechazo" name="explicacionRechazo" style="width:96%;height:250px;" class="validate[required] fuente"></textarea>
	</form>
</div><!-- ventana que Sale cuando se rechaza un proyecto-->
<div id="infoProyecto" title="Informaci&oacute;n del Proyecto" class="fuente"></div><!-- ventana que muestra la informacion del caso-->
<!--***********************************************************************-->
</body>
</html>