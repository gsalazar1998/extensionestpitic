<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['usuario'])){
	echo "<script>window.location = 'http://sistemas.tpitic.com.mx/HelpDesk/php/mostrar_caso_autorizacion.php?cod=".$_GET['cod']."&idcaso=".$_GET['idcaso']."&uid=".$_GET['uid']."'</script>";
}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!--<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />-->
		
		<title>Login - HelpDesk</title>
		<style type="text/css" title="currentStyle">
			body {
				text-align:center;
				font-size: 13px;
				font-family: 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif;
				color:blue;  
				padding:0 20px;
				margin:0;
			}

			/** css para la caja de login*/
			#loginForm{
				background-color: #F5F2F2;
				width: 300px;
				padding: 30 30 30 30;
				text-align: center;
			}

			#naccesodiv{
				list-style-type: none;
				width:250px;
			}

			/************************************************/
			td {padding:5px;}
			.fuente{ font: 85% 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif; margin: 5px;}
			.fuenteTable{ font: 12px 'Lucida Grande',Tahoma,Arial,Verdana,Sans-Serif;  margin: 5px;}
			.sombras {
				width: 100%
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

		<!--JQuery y JQueryUI -->
		<link type="text/css" href="../js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="../js/jqueryui/js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="../js/jqueryui/js/jquery-ui-1.8.11.custom.min.js"></script>
		<!-- para validacion de formulario-->
		<link rel="stylesheet" href="../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
		<link rel="stylesheet" href="../js/validationEngine/css/template.css" type="text/css"/>
		<script src="../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
		<script src="../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script><!--end validacion-->
		<!--configuraciones-->
		<!--<script type="text/javascript" src="../js/login_mostrar_info_auto.js"></script>-->
		<?php 
			echo "<script type='text/javascript'>
			$(document).ready(function(){
			$('#accordion').accordion({ header: 'h3' });

			$('#loginForm').submit(function() {
				var user = $('#user').val();
				var pass = $('#pass').val();
				$.ajax({
					url: 'login.php',
					type: 'POST',
					data:'user='+user+'&pass='+pass,
					success: function(data) {
						if(data == 'pincorrecto'){
							$('#errorLogin').html('<div style=\"width: 400px\" class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\"> <p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span> <strong style=\"float:left\">Error:</strong> Password o usuario incorrectos por favor verifique sus datos.</p></div>');
						}else{
							window.location = '../php/mostrar_caso_autorizacion.php?cod=".$_GET['cod']."&idcaso=".$_GET['idcaso']."&uid=".$_GET['uid']."'
						}
					},
					error: function(xhr,ajaxOptions,thrownError){
						alert(thrownError);
					},
				});
				return false;
			});
		});
		</script>"; ?>
	</head>
	<body>
		<div id="content">
			<h2>Bienvenido al HelpDesk de Sistemas</h2>
			<!--Div que mostrara un error en caso de haberlo-->
			<center><p>
			<div id="errorLogin"></div>
			</p>
			<div id="loginForm">
				<form id="login">
				<center><table class="fuenteTable">
					<tr><td><label for="user" style="color:black;">Usuario</label></td><td><input type="text" class="sombras" id="user" name="user"/></td></tr>
					<tr><td><label for="pass" style="color:black;">Password</label></td><td><input type="password" class="sombras" id="pass" name="pass" /></td></tr>
					<tr><td colspan=2 align="right"><input type="submit"  id="sub" class="ui-state-default ui-corner-all" value="Entrar"></td></tr>
				</table></center>
				</form>
			</div></center>
			<p>&nbsp;&nbsp;</p>
			<p>&nbsp;&nbsp;</p>
			<p>&nbsp;&nbsp;</p>
		<div id="info" name="info">
		</div>
		</div>
	</body>
</html>
