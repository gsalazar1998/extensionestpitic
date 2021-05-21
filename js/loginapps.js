$(document).ready(function(){
	$("#accordion").accordion({ header: "h3" });

	$('#loginForm').submit(function() {
		var user = $("#user").val();
		var pass = $("#pass").val();
		$.ajax({
			url: 'HelpDesk/php/login.php',
			type: 'POST',
			data:"user="+user+"&pass="+pass,
			success: function(data) {
				if(data == "pincorrecto"){
					$("#errorLogin").html("<div style='width: 400px'class='ui-state-error ui-corner-all' style='padding: 0 .7em;'> <p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span> <strong style='float:left'>Error:</strong> Password o usuario incorrectos por favor verifique sus datos.</p></div>");
				}
				if(data == "8A"){
					window.location = "appsadmin.php";
				}
				if(data == "8" || data == "7"){
					window.location = "appsadmin.php";
				}else{
					if(data < 7 || data == 9){
						window.location = "appsusuario.php";
					}
				}
			},
			error: function(xhr,ajaxOptions,thrownError){
				alert(thrownError);
			},
		});
		return false;
	});

	//Aqui va una caja de dialogo de cuando se cambia el status de alguna llave
	$('#dialog').dialog({
		autoOpen: false,
		width: 300,
		buttons: {
			"Perfecto!": function() { 
				$(this).dialog("close"); 
			}, 
			/*"Cancelar": function() { 
				$(this).dialog("close"); 
			} */
		}
	}); //termina cada de dialogo
});
