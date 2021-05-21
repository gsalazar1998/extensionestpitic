<html>
<head>
<script type="text/javascript">
function escoge(ccoi, rfc, nombre) {
    if (window.opener && !window.opener.closed)
		/*alert('cambiaran datos');
		ccoi = document.getElementById('cuenta_coi').value;
		rfc = document.getElementById('rfc').value;
		nombre = document.getElementById('nombre').value;*/
		window.opener.document.getElementById('factura').cuenta_coi.value = ccoi
		window.opener.document.getElementById('factura').rfc.value = rfc
		window.opener.document.getElementById('factura').proveedor.value = nombre
    window.close();
}
</script>
<style type="text/css">
 @import url("reportesstyle.css");
	
 .boton{
	font-family: Arial;
	font-size:14px;
 }
 
  .resultados{
	font-family: Arial;
	font-size:13px;
 }
 .resultados td{
 	padding-left:1em;
	padding-right:1em;
 }
</style>
</head>
<body>
<form name="proveedores" id="proveedores" action="" method="post">
Proveedor a Buscar: &nbsp;&nbsp;<input type="text" id="busqueda_proveedor" name="busqueda_proveedor" />
<input type="submit" value="Buscar" />
</form>
<form name="proveedor" id="proveedor" action="subirfactura.php" method="post">
<?php
$proveedor = isset($_POST['busqueda_proveedor']) ? $_POST['busqueda_proveedor'] : null;
$link = mysql_connect('dbmsql.transportespitic.com','adminusertpitic','adminusertpitic') or die(mysql_error());
if($link){

	mysql_select_db('facturas', $link);
	$query = "SELECT * FROM proveedores WHERE NOMBRE LIKE '%".$proveedor."%' OR ID_PROVEEDOR LIKE '%".$proveedor."%' OR RFC LIKE '%".$proveedor."%' OR CUENTA_COI LIKE '%".$proveedor."%'";
	$rs = mysql_query($query);
	if(mysql_num_rows($rs) > 0 && $proveedor != null){
		$tableProv = "<table border='0' class='resultados'><thead><tr><th></th><th>CUENTA COI</th><th>RFC</th><th>NOMBRE</th></tr></thead>	";
		while($row = mysql_fetch_object($rs)){
			$tableProv .= "<tr><td><input type='checkbox' id='idprov' value='".$row->ID_PROVEEDOR."' onclick='javascript:escoge(\"".$row->CUENTA_COI."\",\"".$row->RFC."\",\"".$row->NOMBRE."\");'/></td>";
			$tableProv .= "<td><input type='hidden' id='cuenta_coi' name='cuenta_coi' value='".$row->CUENTA_COI."'/>".$row->CUENTA_COI."</td>";
			$tableProv .= "<td><input type='hidden' id='rfc' name='rfc' value='".$row->RFC."'/>".$row->RFC."</td>";
			$tableProv .= "<td><input type='hidden' id='nombre' name='nombre' value='".$row->NOMBRE."'/>".$row->NOMBRE."</td></tr>";
		}
		$tableProv .= "</table>";
		echo $tableProv;
	}
}else{
	echo "error al conectar a la base de datos";
}

?>
</form>
</body>
</html>