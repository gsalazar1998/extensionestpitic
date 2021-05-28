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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<title>Reporte de Extensiones</title>
	
	<style>
		.boton_personalizado{
		text-docoration: none;
		margin-top:center;
		padding:8px 13px;
		font-weight:1000;
		font-size: 13px;
		color: #ffffff;
		background-color: #228B22;
		border-radius:6px;
		border: 2px solid #2f4f4f;
		height: 33px;
		}
		
		.desplazar{
		position: center;
		top: 1px;
		left: 11px;
		}
		
		.boton_personalizado:hover{
		color: #228B22;
		background-color: #ffffff;
		}
		
		.redondeado{
		border-radius:5px;
		}
		/* ---- ( iTunes CSS ) ---- */
		table { 
			font: 80% Verdana, Arial, Helvetica, sans-serif;
			color: #000;
			text-align: center;
			border-collapse: collapse;
			border: 2px solid #666666;
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
			color: #030303;
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
	<div id="content" style="width:600px;">
		<div id="logo" style="float:left;">
			<img src="logo_adobe.jpg" width="200px" height="155px"/><br />
			<br />				
		</div><br />
		<div id="formulario" style="margin-top:20px;">
			<form style="margin-top:20px;">
				<font size="3">
				<br>
				Oficina: 				
			<?php 
				echo $objFN->getOficinas();
			?>
					<input type='submit' style="float:right" class="boton_personalizado" value='Buscar' />
				<br />
				</div>
				<br>
				<center>Extension o usuario:</center>
				<div class="desplazar">
				<input type="text" id="target" class="redondeado" name="target" size="32" placeholder="Presione [enter] despues de ingresar"/>
				</div>						
			
				</font>
			
			</form>
			<br>
		</div>
	</div>
	</center>
	<?php
	$target = isset($_GET['target']) ? $_GET['target'] : '';
	$oficinas = isset($_GET['oficinas']) ? $_GET['oficinas'] : '';
	if(($target != '' || $oficinas != '') and $target !='jcons'){
		buscar($con, $oficinas, $target);
	}
	?>
	
	</body>
	</html>
	<?php 
}

function buscar($con, $oficina, $target){
	if($target != ''){
		$filter = "(|(usuariotelefono=".$target.")(extensiontelefono=".$target.")(oficinatelefono=".$target."))";
		$srch=ldap_search($con, "ou=Telefonos,ou=groups,dc=transportespitic,dc=com",$filter);
		$numero=ldap_count_entries($con, $srch);
		$info = ldap_get_entries($con, $srch);
		$count = ldap_count_entries($con, $srch);
		?>
		<center>
		<table class="table table-hover">
		<thead>
		<tr text-align="center">
			<th>Extensi&oacute;n</th>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>Oficina</th>
		</tr>
		</thead>
		
		<tbody>
		<?php
		for($i=0; $i<$count; $i++){
			$srch2=ldap_search($con,"ou=People,dc=transportespitic,dc=com", 'uid='.$info[$i]['usuariotelefono'][0]);
			$info2 = ldap_get_entries($con, $srch2);
			$count2 = ldap_count_entries($con, $srch);
				
			$oficinatelefono = isset($info[$i]['oficinatelefono'][0])?$info[$i]['oficinatelefono'][0]:"NO";
			echo "<tr><td>".$info[$i]['extensiontelefono'][0]."</td><td>".$info[$i]['usuariotelefono'][0]."</td><td>"; $nombre = isset($info2[0]['cn'][0]) ?  $info2[0]['cn'][0] :  "LIBRE"; echo $nombre; echo "</td><td>".$oficinatelefono."</td></tr>";
		}
		?>
		</tbody>
		</table>
		</center>
		<?php
	}
	
	if($oficina != '' && $target == ''){
		$filter = "(oficinatelefono=".$oficina.")";
		$srch=ldap_search($con,"ou=Telefonos,ou=groups,dc=transportespitic,dc=com", $filter);
		$numero=ldap_count_entries($con, $srch);
		$info = ldap_get_entries($con, $srch);
		$count = ldap_count_entries($con, $srch);
		?>
		<center>
		<table class="table table-hover">
		<thead>
		<tr>
			<th>Extensi&oacute;n</th>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>Oficina</th>
		</tr>
		</thead>
		<?php
		for($i=0; $i<$count; $i++){
			if(isset($info[$i]['extensiontelefono'][0])){
				$srch2=ldap_search($con,"ou=People,dc=transportespitic,dc=com", 'uid='.$info[$i]['usuariotelefono'][0]);
				$info2 = ldap_get_entries($con, $srch2);
				$count2 = ldap_count_entries($con, $srch);
				//echo "<tr><td>".$info[$i]['extensiontelefono'][0]."</td><td>".$info[$i]['usuariotelefono'][0]."</td><td>";echo isset($info[$i]['iptelefono'][0]) ? $info[$i]['iptelefono'][0]:'NO';
				//for($j=0; $j<$count; $j++){
				//echo "<tr><td>".$info[$i]['extensiontelefono'][0]."</td><td>".$info[$i]['usuariotelefono'][0]."</td><td>";echo isset($info[$i]['iptelefono'][0]) ? $info[$i]['iptelefono'][0]:'NO';
					echo "<tr><td>".$info[$i]['extensiontelefono'][0]."</td><td>".$info[$i]['usuariotelefono'][0]."</td><td>"; $nombre = isset($info2[0]['cn'][0]) ?  $info2[0]['cn'][0] :  "LIBRE"; echo $nombre;
					echo "</td><td>".$info[$i]['oficinatelefono'][0]."</td></tr>";
				//}
			}
		}
		?>
		</table>
		</center>
		<?php
	}
}
?>
