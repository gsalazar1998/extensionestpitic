$(function(){
	$( "#tabs" ).tabs(); //convertimos los divs en pestañas (div tabs-1, 2 ,3)
	$( "#fotos" ).dialog({
		width: 'auto',
		autoOpen: false,
		modal: true
	});
	/********* para las fechas *********/
	 $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      weekHeader: 'Sm',
      dateFormat: 'yy/mm/dd',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
   
	$( "#f_ini" ).datepicker();
	$( "#f_fin" ).datepicker();
	$( "#sub_btn" ).button();
	
	$("#sub_btn").click(function(){
		//TReporteCasos.fnReloadAjax();
		$.ajax({
			url: "../php/reporteCasos.php?opc=2",
			data: $("#rpt_casos").serialize(),
			type: "post",
			success:function(data){
				$("#info_casoRPT").html(data);
			},
			failure: function(){}
		});
		//$("#resultados").html("asdasdasd");
	});
	/***********************************/
	
	//tabla 1
	var oTable;
	var gaiSelected =  [];
	var selecteds = "";
	
	//tabla 2
	var casosAiertos;
	var asiSelected = [];
	var numCasoReasignado = "";
	
	//tabla3
	var abiertosPorMi;
	var selectedAPM = [];
	
	//tabla 4
	var sinConfirmacion;
	var selectedSC = [];
	
	//tabla 5
	var casosAiertosT5; //el T5 del final es por tabla 5
	var asiSelectedT5 = [];
	var numCasoReasignadoT5 = "";
	
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


	/* Inicializa la tabla*/
	var oTable = $('#cnoasignados').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_admin.php?tidf=1',
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		if ( jQuery.inArray(aData[0], gaiSelected) != -1 ){
					$(nRow).addClass('row_selected');
			}
				return nRow;
		},
		"aoColumns": [
			{ "bVisible": 1 }, /* Columna de ID */
			null,
			null,
			null,
			null,
			null,
			null
		]
	});

	/* configuramos el Evento de click */
	$('#cnoasignados tbody tr ').live('click', function () {
		$("#link_asignar").html("&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		//$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
		//"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
		
		var aData = oTable.fnGetData(this );
		var iId = aData[0];
		
		if ( jQuery.inArray(iId, gaiSelected) == -1 )
		{
			gaiSelected[gaiSelected.length++] = iId;
			selecteds += iId+",";
			//alert(gaiSelected.length);
			if(gaiSelected.length>0){
				if(gaiSelected.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}
		}
		else
		{
			gaiSelected = jQuery.grep(gaiSelected, function(value) {
				return value != iId;
			});
			
			//alert(gaiSelected.length);
			if(gaiSelected.length>0){
				if(gaiSelected.length>1){
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>");
				}else{
					$("#link_asignar").html("<a href='#' id='asignacaso_link' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Asignar</a>"+
					"&nbsp;<a href='#' id='detalles' class='ui-state-default ui-corner-all'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Detalles</a>");
				}
			}
			if(gaiSelected.length == 0){
				$("#link_asignar").html("");
			}
		}
		
		$("#asignacaso_ventana").html(
			"casos: "+gaiSelected+"<br /><br />"+
			"<form id='asignarcasoa' onsubmit='return: false'>"+
			"<input type='hidden' id='casos' name='casos' value='"+gaiSelected+"' />"+
			"<table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'>"+
			"<optgroup label='SISTEMAS'>"+
				"<option value='cmburboa'>cmburboa</option>"+
				"<option value='acota'>acota</option>"+
				"<option value='fmorelos'>fmorelos</option>"+
				"<option value='iaguiar'>iaguiar</option>"+
				"<option value='gesquivel'>gesquivel</option>"+
				"<option value='cquintero'>cquintero</option>"+
				"<option value='jjara'>jjara</option>"+
				"<option value='eresendiz'>eresendiz</option>"+
				"<option value='jfavila'>jfavila</option>"+
				"<option value='mrios'>mrios</option>"+
				"<option value='dwong'>dwong</option>"+
			"<optgroup label='COBRANZA'>"+
				"<option value='vocana'>vocana</option>"+
			"</optgroup>"+
			"<optgroup label='OPERACIONES'>"+
				"<option value='lagonzalez'>lagonzalez</option>"+
				"<option value='fcuen'>fcuen</option>"+
				"<option value='oamaya'>oamaya</option>"+
				"<option value='eluna'>eluna</option>"+
			"</optgroup>"+
			"<optgroup label='AUDITORIA'>"+
				"<option value='lruiz'>lruiz</option>"+
			"</optgroup>"+
			"<optgroup label='TRANSPORTE'>"+
				"<option value='vurias'>vurias</option>"+
			"</optgroup>"+
			"</select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
		$(this).toggleClass('row_selected');
	});
	
	//ventana de informacion del caso
	$('#detalles').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=9",
			data: "idcaso="+gaiSelected,
			type: "POST",
			success: function(data){
				//$("#asignacaso_ventana").dialog("close");
				$('#infoCasoNA').html('');
				$('#infoCasoNA').html(data);
				$('#infoCasoNA').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});
	
	
	//ventana de informacion del caso que se abrira en el caso de los reportes.
	$('#detallesReportes').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=9",
			data: "idcaso="+selectedRPT[0],
			type: "POST",
			success: function(data){
				//$("#asignacaso_ventana").dialog("close");
				$('#infoCasoReporte').html(data);
				$('#infoCasoReporte').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});
	
	
	
	//Caja que se lanza para asignar o reasignar un caso	
	$("#asignacaso_ventana").dialog({
		autoOpen: false,
		width: 300,
		draggable: true,
		resizable: true,
		modal: false,
		closeOnEscape: true,
		buttons: {
			"Aceptar" : function(){
				$.ajax({
					url: "../php/asignacaso.php",
					data: $("#asignarcasoa").serialize(),
					type: "POST",
					success: function(data){
						if(data == "OK"){
							$("#asignacaso_ventana").dialog("close"); 
							$("#msgDialog").html("Se ha asignado el caso a "+$("#usuarioasig").val());
							$('#msgDialog').dialog('open');
							$("#link_asignar").html("");
							gaiSelected.length = 0;
							//alert(gaiSelected.length);
							$(this).dialog('close');
						}
					},
					failure: function(){
						alert("Error en la peticion");
					}
				});
			},
			"Cancelar": function(){
				$(this).dialog("close");
			}
		}
	});

	$('#asignacaso_link').live('click',function(){
		/*if(undefined==window.gaiSelected){
			$("#asignacaso_ventana").html("Por favor seleccione un caso para asignar");
		}*/
		$('#asignacaso_ventana').dialog('open');
		return false;
	});

/****************Aqui comienza la segunda tabla de casos abiertos ***************/
var casosAiertos = $('#casignados').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_admin.php?tidf=2', //aca me quede
		/*"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		if ( jQuery.inArray(aData[0], asiSelected) != -1 ){
					$(nRow).addClass('row_selected');
			}
				return nRow;
		},*/
		"aoColumns": [
			{ "bVisible": 1 }, /* Columna de ID */
			null,
			null,
			null,
			null,
			null,
			null,
			null
		]
	});

	// Evento de click 
	$('#casignados tbody tr ').live('click', function () {
		$("#info_caso").html("<a href='#' id='casos_info_link' class='ui-state-default ui-corner-all fuente'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
		var aData = casosAiertos.fnGetData(this );
		var iId = aData[0];
		
		$('#casignados tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, asiSelected) == -1 )
		{
			asiSelected[0] = iId;
			numCasoReasignado = iId;
			/*if(asiSelected.length != 1 ){
				$("#info_caso").html("");
			}*/
		}
		else
		{
			/*
			asiSelected = jQuery.grep(asiSelected, function(value) {
				return value != iId;
			});
			if(asiSelected.length != 1 ){
				$("#info_caso").html("");
			}
			*/
		}
		
		$("#asignacaso_ventana").html(
			"casos: "+numCasoReasignado+"<br /><br />"+
			"<form id='asignarcasoa' onsubmit='return: false'>"+
			"<input type='hidden' id='casos' name='casos' value='"+numCasoReasignado+"' />"+
			"<table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'>"+
			"<optgroup label='SISTEMAS'>"+
				"<option value='cmburboa'>cmburboa</option>"+
				"<option value='acota'>acota</option>"+
				"<option value='fmorelos'>fmorelos</option>"+
				"<option value='iaguiar'>iaguiar</option>"+
				"<option value='gesquivel'>gesquivel</option>"+
				"<option value='cquintero'>cquintero</option>"+
				"<option value='jjara'>jjara</option>"+
				"<option value='eresendiz'>eresendiz</option>"+
				"<option value='jfavila'>jfavila</option>"+
				"<option value='mrios'>mrios</option>"+
				"<option value='dwong'>dwong</option>"+
			"<optgroup label='COBRANZA'>"+
				"<option value='vocana'>vocana</option>"+
			"</optgroup>"+
			"<optgroup label='OPERACIONES'>"+
				"<option value='lagonzalez'>lagonzalez</option>"+
				"<option value='fcuen'>fcuen</option>"+
				"<option value='oamaya'>oamaya</option>"+
				"<option value='eluna'>eluna</option>"+
			"</optgroup>"+
			"<optgroup label='AUDITORIA'>"+
				"<option value='lruiz'>lruiz</option>"+
			"</optgroup>"+
			"<optgroup label='TRANSPORTE'>"+
				"<option value='vurias'>vurias</option>"+
			"</optgroup>"+
			"</select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
	});

/************************ Ventana infoCaso *************************/
	$("#infoCaso").dialog({
		autoOpen: false,
		width: 800,
		draggable: true,
		resizeable: false,
		buttons:{
			"Reasignar": function(){
				$("#asignacaso_ventana").dialog('open');
				$(this).dialog("close");
			},
			"Cerrar caso": function(){
				$("#cierracaso_ven").dialog('open');
			},
			"Cerrar ventana": function(){
				$(this).dialog("close");
				location.reload(true);
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

	$('#info_caso').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=3",
			data: "idcaso="+asiSelected[0],
			type: "POST",
			success: function(data){
				$("#asignacaso_ventana").dialog("close");
				$('#infoCaso').html(data);
				$('#infoCaso').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});

	
	/*********************** ventana como infoCasoNA ***********************/
	
	$("#infoCasoNA").dialog({
		autoOpen: false,
		width: 800,
		draggable: true,
		resizeable: false,
		buttons:{
			"Reasignar": function(){
				$("#asignacaso_ventana").dialog('open');
				$(this).dialog("close");
			},
			/*"Cerrar caso": function(){
				$("#cierracaso_ven").dialog('open');
			},*/
			"Cerrar ventana": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

	$('#info_casoNA').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=3",
			data: "idcaso="+asiSelected[0],
			type: "POST",
			success: function(data){
				$("#asignacaso_ventana").dialog("close");
				$('#infoCaso').html(data);
				$('#infoCaso').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});
	
	/*********************** ventana como infoCasoReporte ***********************/
	
	$("#infoCasoReporte").dialog({
		autoOpen: false,
		width: 800,
		draggable: true,
		resizeable: false,
		buttons:{
			/*"Reasignar": function(){
				$("#asignacaso_ventana").dialog('open');
				$(this).dialog("close");
			},
			"Cerrar caso": function(){
				$("#cierracaso_ven").dialog('open');
			},*/
			"Cerrar ventana": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});	

/************************** infoCaso para la TABLA 5 (T5) Todos los casos abiertos y esperando confirmacion ***********************/

$("#infoCasoT5").dialog({
		autoOpen: false,
		width: 800,
		draggable: true,
		resizeable: false,
		buttons:{
			"Reasignar": function(){
				$("#asignacaso_ventana").dialog('open');
				$(this).dialog("close");
			},
			"Cerrar caso": function(){
				$("#cierracaso_venT5").dialog('open');
			},
			"Cerrar ventana": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			//$(this).dialog("close");
		}
	});

	/*$('#infoCasoT5').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=8",
			data: "idcaso="+asiSelectedT5[0],
			type: "POST",
			success: function(data){
				$("#asignacaso_ventana").dialog("close");
				$('#infoCasoT5').html(data);
				$('#infoCasoT5').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});*/




	
/*******************AQUI COMIENZA LA TERCERA TABLA DE "Casos levantados por mi*********************/
	var abiertosPorMi = $('#cabiertosxmi').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_admin.php?tidf=4',
		"aoColumns": [
			{ "bVisible": 1 },
			null,
			null,
			null,
			null,
			null,
			null
		]
	});

	// Evento de click 
	$('#cabiertosxmi tbody tr ').live('click', function () {
		$("#info_casoPM").html("<a href='#' id='casos_info_linkPM' class='ui-state-default ui-corner-all fuente'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
		var aDataPM = abiertosPorMi.fnGetData(this );
		var iId = aDataPM[0];
		
		$('#cabiertosxmi tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, selectedAPM) == -1 )
		{
			selectedAPM[0] = iId;
		}
		else{}
		
		$("#asignacaso_ventana").html(
			"casos: "+selectedAPM[0]+"<br /><br />"+
			"<form id='asignarcasoa' onsubmit='return: false'>"+
			"<input type='hidden' id='casos' name='casos' value='"+gaiSelected+"' />"+
			"<table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'>"+
			"<optgroup label='SISTEMAS'>"+
				"<option value='cmburboa'>cmburboa</option>"+
				"<option value='acota'>acota</option>"+
				"<option value='fmorelos'>fmorelos</option>"+
				"<option value='iaguiar'>iaguiar</option>"+
				"<option value='gesquivel'>gesquivel</option>"+
				"<option value='cquintero'>cquintero</option>"+
				"<option value='jjara'>jjara</option>"+
				"<option value='eresendiz'>eresendiz</option>"+
				"<option value='jfavila'>jfavila</option>"+
				"<option value='mrios'>mrios</option>"+
				"<option value='dwong'>dwong</option>"+
			"<optgroup label='COBRANZA'>"+
				"<option value='vocana'>vocana</option>"+
			"</optgroup>"+
			"<optgroup label='OPERACIONES'>"+
				"<option value='lagonzalez'>lagonzalez</option>"+
				"<option value='fcuen'>fcuen</option>"+
				"<option value='oamaya'>oamaya</option>"+
				"<option value='eluna'>eluna</option>"+
			"</optgroup>"+
			"<optgroup label='AUDITORIA'>"+
				"<option value='lruiz'>lruiz</option>"+
			"</optgroup>"+
			"<optgroup label='TRANSPORTE'>"+
				"<option value='vurias'>vurias</option>"+
			"</optgroup>"+
			"</select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");

		});
	
	
	$('#info_casoPM').live('click',function(){
		$.ajax({
			url: "../php/casos_admin.php?tidf=5",
			data: "idcaso="+selectedAPM[0],
			type: "POST",
			success: function(data){
				//$("#asignacaso_ventana").dialog("close");
				$('#infoCaso').html(data);
				$('#infoCaso').dialog('open');
			},
			failure: function(){
				alert("Error en la peticion");
			}
		});
		return false;
	});

/****************Aqui comienza la TERCERA TABLA casos SIN CONFIRMACION ***************/
var sinConfirmacion = $('#TFaltaConfirmacion').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_admin.php?tidf=6', //aca me quede
		/*"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
		if ( jQuery.inArray(aData[0], asiSelected) != -1 ){
					$(nRow).addClass('row_selected');
			}
				return nRow;
		},*/
		"aoColumns": [
			{ "bVisible": 1 }, /* Columna de ID */
			null,
			null,
			null,
			null,
			null
		]
	});

	// Evento de click 
	$('#TFaltaConfirmacion tbody tr ').live('click', function () {
		$("#info_caso").html("<a href='#' id='casos_info_link' class='ui-state-default ui-corner-all fuente'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
		var aData = sinConfirmacion.fnGetData(this );
		var iId = aData[0];
		
		$('#TFaltaConfirmacion tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, asiSelected) == -1 )
		{
			selectedSC[0] = iId;
			numCasoReasignado = iId;
		}
		else{}
		$("#asignacaso_ventana").html(
			"casos: "+numCasoReasignado+"<br /><br />"+
			"<form id='asignarcasoa' onsubmit='return: false'>"+
			"<input type='hidden' id='casos' name='casos' value='"+numCasoReasignado+"' />"+
			"<table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'>"+
			"<optgroup label='SISTEMAS'>"+
				"<option value='cmburboa'>cmburboa</option>"+
				"<option value='acota'>acota</option>"+
				"<option value='fmorelos'>fmorelos</option>"+
				"<option value='iaguiar'>iaguiar</option>"+
				"<option value='gesquivel'>gesquivel</option>"+
				"<option value='cquintero'>cquintero</option>"+
				"<option value='jjara'>jjara</option>"+
				"<option value='eresendiz'>eresendiz</option>"+
				"<option value='jfavila'>jfavila</option>"+
				"<option value='mrios'>mrios</option>"+
				"<option value='dwong'>dwong</option>"+
			"<optgroup label='COBRANZA'>"+
				"<option value='vocana'>vocana</option>"+
			"</optgroup>"+
			"<optgroup label='OPERACIONES'>"+
				"<option value='lagonzalez'>lagonzalez</option>"+
				"<option value='fcuen'>fcuen</option>"+
				"<option value='oamaya'>oamaya</option>"+
				"<option value='eluna'>eluna</option>"+
			"</optgroup>"+
			"<optgroup label='AUDITORIA'>"+
				"<option value='lruiz'>lruiz</option>"+
			"</optgroup>"+
			"<optgroup label='TRANSPORTE'>"+
				"<option value='vurias'>vurias</option>"+
			"</optgroup>"+
			"</select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
	});

/*******************AQUI COMIENZA LA TABLA DE REPORTE CASOS*********************/
	/*var TReporteCasos = $('#TReporte_casos').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		/*data: $("#rpt_casos").serialize(),
			type: "post",*//*
		"sAjaxSource": '../php/reporteCasos.php?opc=2',
		"aoColumns": [
			{ "bVisible": 1 },
			null,
			null,
			null,
			null,
			null,
			null
		]
	});

	// Evento de click 
	$('#TReporte_casos tbody tr ').live('click', function () {
		$("#info_casoRPT").html("<a href='#' id='casos_info_linkPM' class='ui-state-default ui-corner-all fuente'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
		var aData = abiertosPorMi.fnGetData(this );
		var iId = aData[0];
		
		$('#TReporte_casos tbody tr ').removeClass("row_selected");
		$(this).addClass("row_selected");
		if ( jQuery.inArray(iId, selectedAPM) == -1 )
		{
			selectedAPM[0] = iId;
		}
		else{}
	});*/
	
/**************** COMIENZA LA TABLA 5 (todos los casos abiertos) *******************/

var casosAiertosT5 = $('#todosAbiertos').dataTable({
	"bJQueryUI": true,
	"sPaginationType": "full_numbers",
	"bProcessing": true,
	"sAjaxSource": '../php/casos_admin.php?tidf=7', //aca me quede
	/*"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
	if ( jQuery.inArray(aData[0], asiSelected) != -1 ){
				$(nRow).addClass('row_selected');
		}
			return nRow;
	},*/
	"aoColumns": [
		{ "bVisible": 1 }, /* Columna de ID */
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null
	]
});

// Evento de click 
$('#todosAbiertos tbody tr ').live('click', function () {
	$("#info_casoT5").html("<a href='#' id='casos_info_linkT5' class='ui-state-default ui-corner-all fuente'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
	var aData = casosAiertosT5.fnGetData(this );
	var iId = aData[0];
	
	$('#todosAbiertos tbody tr ').removeClass("row_selected");
	$(this).addClass("row_selected");
	if ( jQuery.inArray(iId, asiSelected) == -1 )
	{
		asiSelectedT5[0] = iId;
		numCasoReasignadoT5 = iId;
		/*if(asiSelected.length != 1 ){
			$("#info_caso").html("");
		}*/
	}
	else
	{
		/*
		asiSelected = jQuery.grep(asiSelected, function(value) {
			return value != iId;
		});
		if(asiSelected.length != 1 ){
			$("#info_caso").html("");
		}
		*/
	}
	$("#asignacaso_ventana").html(
			"casos: "+numCasoReasignadoT5+"<br /><br />"+
			"<form id='asignarcasoa' onsubmit='return: false'>"+
			"<input type='hidden' id='casos' name='casos' value='"+numCasoReasignadoT5+"' />"+
			"<table align='center'><td><div id='asignar'>a:&nbsp;<select id='usuarioasig' name='usuarioasig'>"+
			"<optgroup label='SISTEMAS'>"+
				"<option value='cmburboa'>cmburboa</option>"+
				"<option value='acota'>acota</option>"+
				"<option value='fmorelos'>fmorelos</option>"+
				"<option value='iaguiar'>iaguiar</option>"+
				"<option value='gesquivel'>gesquivel</option>"+
				"<option value='cquintero'>cquintero</option>"+
				"<option value='jjara'>jjara</option>"+
				"<option value='eresendiz'>eresendiz</option>"+
				"<option value='jfavila'>jfavila</option>"+
				"<option value='mrios'>mrios</option>"+
				"<option value='dwong'>dwong</option>"+
			"<optgroup label='COBRANZA'>"+
				"<option value='vocana'>vocana</option>"+
			"</optgroup>"+
			"<optgroup label='OPERACIONES'>"+
				"<option value='lagonzalez'>lagonzalez</option>"+
				"<option value='fcuen'>fcuen</option>"+
				"<option value='oamaya'>oamaya</option>"+
				"<option value='eluna'>eluna</option>"+
			"</optgroup>"+
			"<optgroup label='AUDITORIA'>"+
				"<option value='lruiz'>lruiz</option>"+
			"</optgroup>"+
			"<optgroup label='TRANSPORTE'>"+
				"<option value='vurias'>vurias</option>"+
			"</optgroup>"+
			"</select></div><div id='otro'></td></tr><tr><td><div id='otro_usuario'><a id='other_user' onclick='cambiaCombo()'>Otro usuario..</a></div></td></tr></table></form>");
//$(this).toggleClass('row_selected');
});


$("#infoCaso").dialog({
	autoOpen: false,
	width: 800,
	draggable: true,
	resizeable: false,
	buttons:{
		"Reasignar": function(){
			$("#asignacaso_ventana").dialog('open');
			$(this).dialog("close");
		},
		"Cerrar caso": function(){
			$("#cierracaso_ven").dialog('open');
			if(asiSelected[0] != "undefined" && asiSelected[0] != null && asiSelected[0] != ""){
				$("#num_caso").val(asiSelected[0]);
			}
			//con esta peticion ajax obtendremos la oficina que tiene registrada el caso
			$.ajax({
				url: "../php/casos_admin.php?tidf=10",
				data: "idcaso="+document.getElementById('num_caso').value,
				type: "POST",
				dataType: "html",
				success: function(data){
					$("#oficinasCierraCaso").val(data);
				},
				failure: function(){
					alert("Error en la peticion");
				}
			});
		},
		"Cerrar ventana": function(){
			$(this).dialog("close");
		}
	},
	close: function(event, ui) {
		//$(this).dialog("close");
	}
});

$('#info_casoT5').live('click',function(){
	$.ajax({
		url: "../php/casos_admin.php?tidf=8",
		data: "idcaso="+asiSelectedT5[0],
		type: "POST",
		success: function(data){
			$("#asignacaso_ventana").dialog("close");
			$('#infoCasoT5').html(data);
			$('#infoCasoT5').dialog('open');
		},
		failure: function(){
			alert("Error en la peticion");
		}
	});
	return false;
});

/***************************************************************************/


	//ventana de mensaje que al cerrar refresca las tablas
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
		close: function(event, ui) {
			asiSelected = [];
			oTable.fnReloadAjax(); 
			casosAiertos.fnReloadAjax();
			abiertosPorMi.fnReloadAjax();
			sinConfirmacion.fnReloadAjax();
			casosAiertosT5.fnReloadAjax();
		}
	});
	
	//ventana de mensaje sin refrescar las tablas
	$("#msjDialog").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
		}
	});
	
/*************** PARA LA VENTANA DE RESPONDER(COMENTAR) A UN CASO *********************/
	$("#respuesta_ven").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Responder": function() {
				//evento pantalla(falso) para que aparezcan los errores.
				if($("#respuesta_form").validationEngine('validate')){
					$.ajax({
						url: "../php/respuestasACasos.php?opcCaso=1",
						data: $("#respuesta_form").serialize(),
						type: "POST",
						success: function(data){
							//if(data == "OK"){
								$('#respuestas').addClass('respuestas');
								$('#respuestas').html(data);
								$('#respuesta_form').validationEngine('hideAll');
								$('#respuesta_ven').dialog('close');
								$("#msjDialog").html("Se ha agregado la respuesta al caso");
								$('#msjDialog').dialog('open');
							//}
						},
						failure: function(){
							alert("Error en la peticion");
						}
					});
				}
			},
			"Cancelar": function(){
				$('#respuesta_form').validationEngine('hideAll');
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {

		}
	});
	
	
/**************************************/
$('#refresh_asignados').click(function(){
	oTable.fnReloadAjax(); 
	casosAiertos.fnReloadAjax();
	abiertosPorMi.fnReloadAjax();
	sinConfirmacion.fnReloadAjax();
	casosAiertosT5.fnReloadAjax();
})	
	
/*********************VENTANA PARA CERRAR EL CASO TABLA (MIS CASOS ABIERTOS TAB 2)*****************************/
	$("#cierracaso_ven").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				if($("#cerrarcasoSC").validationEngine('validate')){
					$.ajax({
						url: "../php/cerrar_caso.php?opcCaso=1",
						data: "idcaso="+$("#num_caso").val()+"&sistema="+document.cerrarcasoSC.sistemas.options[document.cerrarcasoSC.sistemas.selectedIndex].value+"&finalComment="+$("#comentarioFinal").val()+"&oficina="+document.cerrarcasoSC.oficinasCierraCaso.options[document.cerrarcasoSC.oficinasCierraCaso.selectedIndex].value,
						type: "POST",
						success: function(data){
							if(data == "OK"){
								$('#cierracaso_ven').dialog("close");
								$('#msgDialog').html("Se ha cerrado el caso por su parte, espere la confirmacion del usuario que levanto el caso.");
								$('#msgDialog').dialog('open');
								$('#infoCaso').dialog('close');
							}else{
								$('#msgDialog').html(data);
								$('#msgDialog').dialog('open');
								$('#cierracaso_ven').dialog("close");
							}
						},
						failure: function(){
							alert("Error en la peticion");
						}
					});
				}
			},
			"Cancelar": function(){
				$('#cerrarcasoSC').validationEngine('hideAll');
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			asiSelected = [];
			oTable.fnReloadAjax(); 
			casosAiertos.fnReloadAjax();
			casosAiertosT5.fnReloadAjax();
		}
	});
	
	$("#cierracaso_venT5").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				if($("#cerrarcasoSCT5").validationEngine('validate')){
					$.ajax({
						url: "../php/cerrar_caso.php?opcCaso=1",
						data: "idcaso="+asiSelectedT5[0]+"&sistema="+$("#sistemasT5").val()+"&finalComment="+$("#comentarioFinalT5").val(),
						type: "POST",
						success: function(data){
							if(data == "OK"){
								$('#cierracaso_venT5').dialog("close");
								$('#msgDialog').html("Se ha cerrado el caso por su parte, espere la confirmacion del usuario que levanto el caso.");
								$('#msgDialog').dialog('open');
								$('#infoCasoT5').dialog('close');
							}else{
								$('#msgDialog').html(data);
								$('#msgDialog').dialog('open');
								$('#cierracaso_venT5').dialog("close");
							}
						},
						failure: function(){
							alert("Error en la peticion");
						}
					});
				}
			},
			"Cancelar": function(){
				$('#cerrarcasoSCT5').validationEngine('hideAll');
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			asiSelected = [];
			oTable.fnReloadAjax(); 
			casosAiertos.fnReloadAjax();
			casosAiertosT5.fnReloadAjax();
		}
	});


})

function cambiaCombo(){
	$("#asignar").html("a:&nbsp;<input type='text' id='usuarioasig' name='usuarioasig' />");
	$("#otro_usuario").html("")
}
