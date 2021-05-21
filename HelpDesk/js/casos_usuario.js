$(function(){
	$( "#tabs" ).tabs(); //convertimos los divs en pestañas (div tabs-1, 2 ,3)
	$( "#fotos" ).dialog({
		width: 'auto',
		autoOpen: false,
		modal: true
	});
	
	/*var oTable;
	var casosAiertos;
	var gaiSelected =  [];
	var selecteds = "";*/
	
	var asiSelected = [];
	var numCasoReasignado = "";
	/* Add a click handler to the rows - this could be used as a callback */
	
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

/****************Aqui comienza la segunda tabla de casos abiertos ***************/
var casosAiertos = $('#casignados').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_usuario.php?tidf=2',
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

	// Evento de click 
	$('#casignados tbody tr ').live('click', function () {
		$("#info_caso").html("<a href='#' id='casos_info_link' class='ui-state-default ui-corner-all fuenteTable'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
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
		$("#asignacaso_ventana").html("Asignar los casos "+asiSelected+"<br /><br /><form id='asignarcasoa' onsubmit='return: false'><input type='hidden' id='casos' name='casos' value='"+numCasoReasignado+"' /><table align='center'><tr><td>a: &nbsp;</td><td><select id='usuarioasig' name='usuarioasig'><option value='cmburboa'>cmburboa</option><option value='acota'>acota</option><option value='iaguiar'>iaguiar</option><option value='gesquivel'>gesquivel</option><option value='cquintero'>cquintero</option><option value='jjara'>jjara</option><option value='eresendiz'>eresendiz</option><option value='jfavila'>jfavila</option><option value='dwong'>dwong</option></select></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
	});


	$("#infoCaso").dialog({
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

	$('#info_caso').live('click',function(){
		$.ajax({
			url: "../php/casos_usuario.php?tidf=3",
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
	
/******************AQUI COMIENZA LA TABLA DE CASOS SIN CONFIRMAR ************************/
	var sinConfirmacion = $('#csinconfirmacion').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bProcessing": true,
		"sAjaxSource": '../php/casos_usuario.php?tidf=4',
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
	$('#csinconfirmacion tbody tr ').live('click', function () {
		$("#info_caso").html("<a href='#' id='casos_info_link' class='ui-state-default ui-corner-all fuenteTable'><span class='ui-icon ui-icon-arrowreturnthick-1-e'></span>Ver Detalles</a></p>");
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
		$("#asignacaso_ventana").html("Asignar los casos "+asiSelected+"<br /><br /><form id='asignarcasoa' onsubmit='return: false'><input type='hidden' id='casos' name='casos' value='"+numCasoReasignado+"' /><table align='center'><tr><td>a: &nbsp;</td><td><select id='usuarioasig' name='usuarioasig'><option value='cmburboa'>cmburboa</option><option value='acota'>acota</option><option value='iaguiar'>iaguiar</option><option value='gesquivel'>gesquivel</option><option value='cquintero'>cquintero</option><option value='jjara'>jjara</option><option value='eresendiz'>eresendiz</option><option value='jfavila'>jfavila</option><option value='dwong'>dwong</option></select></td></tr></table></form>");
		//$(this).toggleClass('row_selected');
	});


	$("#infoCaso").dialog({
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

	$('#info_caso').live('click',function(){
		$.ajax({
			url: "../php/casos_usuario.php?tidf=3",
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
/****************************************************************************************/
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
			//oTable.fnReloadAjax(); 
			casosAiertos.fnReloadAjax();
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
								$("#msgDialog").html("Se ha agregado la respuesta al caso");
								$('#msgDialog').dialog('open');
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
	
	
/*********************VENTANA PARA CERRAR EL CASO*****************************/

	$("#cierracaso_ven").dialog({
		autoOpen: false,
		width: 400,
		draggable: true,
		resizeable: false,
		buttons:{
			"Aceptar": function(){
				$.ajax({
					url: "../php/cerrar_caso.php?opcCaso=1",
					data: "idcaso="+asiSelected[0]+"&sistema="+$("#sistemas").val(),
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
			},
			"Cancelar": function(){
				$(this).dialog("close");
			}
		},
		close: function(event, ui) {
			asiSelected = [];
			//oTable.fnReloadAjax(); 
			casosAiertos.fnReloadAjax();
		}
	});

/********************************************************************************/
});

function cambiaCombo(){
	$("#asignar").html("a:&nbsp;<input type='text' id='usuarioasig' name='usuarioasig' />");
	$("#otro_usuario").html("")
}
