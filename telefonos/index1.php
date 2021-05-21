<?php
require_once('../php/conexionLDAP.php');
require_once('../php/funciones_generales.php');
$objFN = new FuncionesGrales();
$objConLDAP = new Conexion();

$con = $objConLDAP->conectarLDAP();
if($con){
	?>
	<html>
	<head>
	<title>Reporte de Extensiones</title>
	<style>
		/* ---- ( iTunes CSS ) ---- */
		table { 
			font: 80% Verdana, Arial, Helvetica, sans-serif;
			color: #000;
			text-align: left;
			border-collapse: collapse;
			border: 1px solid #666666;
			border-top: none;	
		}
		
		#formulario{ 
			font: 80% Verdana, Arial, Helvetica, sans-serif;
			color: #000;
		}
		
		table a {
			text-decoration: underline;
		}
		table a:visited {
			text-decoration: none;
		}
		tr.odd {
			background-color: #ebf3ff;
		}
		tr a {
			color: #000000;
		}
		tr:hover a {
			color: #ffffff;
		}
		tr:hover, tr.odd:hover {
			background-color: #3d80df;
			color: #ffffff;
		}
		caption {
			height: 45px;
			line-height: 44px;
			color: #60634E;
			font-weight: bold;
			text-align: center;
			width: 100%;
			margin: 0;
			padding: 0;
			margin-left: -1px;
			background: #ffffff url(captop.jpg) repeat-x;
			background-position: 50% top;
			border-left: 2px solid #616161;
			border-right: 2px solid #616161;
		}
		thead th {
			font-size: 105%;
			color: #000;
			background: #ffffff url('http://www.johnlawrence.net/itable/tbar.gif') repeat-x;
			height: 33px;
		}
		thead th:hover {
			background: #ffffff url(http://www.johnlawrence.net/itable/tbov.gif) repeat-x;
			
		}
		tr {
			vertical-align: top;
		}
		tr,th,td {
			padding: .75em;
		}
		td {
			border-left: 1px solid #dadada;
		}
		tfoot tr {
			background: #fff url(bbar.gif) repeat-x;
		}
		tfoot td, tfoot th{
			color: #000;
			border: 0px;
		}
	</style>
	</head>
	<body>
	<center>
	<div id="content" style="width:500px;">
		<div id="logo" style="float:left;">
			<img src="transportes-pitic.jpg" width="150px" height="100px"/><br />
			<a href="http://sistemas.tpitic.com.mx/manuales/manual_telefono.pdf">Descargar Manual de Telefonos</a>
		</div><br />
		<div id="formulario" style="margin-top:20px;">
			<form style="margin-top:20px;" type="POST">
			Texto a Buscar: <input type="text" id="target" name="target" /><br /><br /> Oficina
			<?php 
				echo $objFN->getOficinas();
			?>
			<br /><br />
			<input type='submit' value='Buscar' />
			</form>
		</div>
	</div>
	</center>
	<?php 
	$target = isset($_GET['target']) ? $_GET['target'] : '';
	$oficinas = isset($_GET['oficinas']) ? $_GET['oficinas'] : '';
	if($oficinas!= '' || $target != ''){
		buscar($con, $oficinas, $target);
	}
	?>
	
	</body>
	</html>
	<?php 
}

function buscar($con, $oficina, $target){
	if($target != ''){
		$srch=ldap_search($con, "dc=transportespitic,dc=com","(|(uid=*".$target."*)(extension=".$target.")(cn=*".$target."*))");
		$numero=ldap_count_entries($con, $srch);
		$info = ldap_get_entries($con, $srch);
		$count = ldap_count_entries($con, $srch);
		?>
		<center>
		<table>
		<thead>
		<tr>
			<th>Extensi&oacute;n</th>
			<th>IP</th>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>Oficina</th>
		</tr>
		</thead>
		
		<tbody>
		<?php
		for($i=0; $i<$count; $i++){
			//Solo muestra los que tienen el campo extension del ldap
			if(isset($info[$i]['extension'][0]) and ($info[$i]['extension'][0])!='NO'){
				echo "<tr><td>".$info[$i]['extension'][0]."</td><td>"./*$info[$i]['voiceip'][0].*/"</td><td>".$info[$i]['uid'][0]."</td><td>".$info[$i]['cn'][0]."</td><td>".$info[$i]['oficina'][0]."</td></tr>";
			}
		}
		?>
		</tbody>
		</table>
		</center>
		<?php
	}
	
	if($oficina != '' && $target == ''){
		$srch=ldap_search($con, "dc=transportespitic,dc=com","(&(oficina=".$oficina.")(!(extension=null)))");
		$numero=ldap_count_entries($con, $srch);
		$info = ldap_get_entries($con, $srch);
		$count = ldap_count_entries($con, $srch);
		?>
		<center>
		<table>
		<thead>
		<tr>
			<th>Extensi&oacute;n</th>
			<th>IP</th>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>Oficina</th>
		</tr>
		</thead>
		<?php
		for($i=0; $i<$count; $i++){
			if(isset($info[$i]['extension'][0]) and ($info[$i]['extension'][0])!='NO'){
				echo "<tr><td>".$info[$i]['extension'][0]."</td><td>"./*$info[$i]['voiceip'][0].*/"</td><td>".$info[$i]['uid'][0]."</td><td>".$info[$i]['cn'][0]."</td><td>".$info[$i]['oficina'][0]."</td></tr>";
			}
		}
		?>
		</table>
		</center>
		<?php
	}
}
?>