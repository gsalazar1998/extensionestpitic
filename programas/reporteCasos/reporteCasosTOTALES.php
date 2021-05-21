<html>
<head><title>Reporte de casos por oficinas</title>
<style type="text/css">
table {
	background-color:#F2F2F5;
	border-width:1px 1px 0px 1px;
	border-color:#C9CBD3;
	border-style:solid;
}
td {
	color:#000000;
	font-family:Tahoma,Arial,Helvetica,Geneva,sans-serif;
	font-size:9pt;
	background-color:#EAEFF5;
	padding:8px;
	background-color:#F2F2F5;
	border-color:#ffffff #ffffff #cccccc #ffffff;
	border-style:solid solid solid solid;
	border-width:1px 0px 1px 0px;
	text-align: center;
}
.oscura {
	color:#000000;
	font-family:Tahoma,Arial,Helvetica,Geneva,sans-serif;
	font-size:9pt;
	background-color:#A4A4A4;
	padding:8px;
	background-color:#A4A4A4;
	border-color:#A4A4A4 #A4A4A4 #A4A4A4 #A4A4A4;
	border-style:solid solid solid solid;
	border-width:1px 0px 1px 0px;
	text-align: center;
}
th {
	font-family:Tahoma,Arial,Helvetica,Geneva,sans-serif;
	font-size:9pt;
	padding:8px;
	background-color:#CFE0F1;
	border-color:#ffffff #ffffff #cccccc #ffffff;
	border-style:solid solid solid none;
	border-width:1px 0px 1px 0px;
	white-space:nowrap;
}
h2{
	font-family:Tahoma,Arial,Helvetica,Geneva,sans-serif;
}
#resultados{
	text-align:center;
	margin:0, auto, 0, auto;
}
</style>
<script type="text/javascript">
window.apex_search = {};
apex_search.init = function (){
	this.rows = document.getElementById('data').getElementsByTagName('TR');
	this.rows_length = apex_search.rows.length;
	this.rows_text =  [];
	for (var i=0;i<apex_search.rows_length;i++){
        this.rows_text[i] = (apex_search.rows[i].innerText)?apex_search.rows[i].innerText.toUpperCase():apex_search.rows[i].textContent.toUpperCase();
	}
	this.time = false;
}

apex_search.lsearch = function(){
	this.term = document.getElementById('S').value.toUpperCase();
	for(var i=0,row;row = this.rows[i],row_text = this.rows_text[i];i++){
		row.style.display = ((row_text.indexOf(this.term) != -1) || this.term  === '')?'':'none';
	}
	this.time = false;
}

apex_search.search = function(e){
    var keycode;
    if(window.event){keycode = window.event.keyCode;}
    else if (e){keycode = e.which;}
    else {return false;}
    if(keycode == 13){
		apex_search.lsearch();
	}
    else{return false;}
}
</script>
</head>
<body onload="apex_search.init();">
<div id="resultados">
<h2>Reporte de casos para oficina <? echo $_GET['ofi'] ?></h2>
<table border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td><input type="text" size="30" maxlength="1000" value="" id="S" onkeyup="apex_search.search(event);" /><input type="button" value="Buscar" onclick="apex_search.lsearch();"/> 
</td></tr>
</tbody></table><br />
<?php
$mysqli = new mysqli('dbmsql.transportespitic.com', 'helpdesk', 'h31pd35k51573m45', 'SISP');
if (mysqli_connect_errno()) {
	echo "Connect failed: ".mysqli_connect_error();
	exit();
}

$query = "SELECT 
		a.ID_CASO,
		a.PROBLEMA,
		a.DESCRIPCION,
		CASE a.ESTADO_CASO WHEN 1 THEN 'Abierto' WHEN 0 THEN 'Cerrado' WHEN 2 THEN 'Esperando Conf.' WHEN 3 THEN 'Cerrado' ELSE 'No Definido' END AS ESTADO_CASO, 
		(SELECT sistema FROM cat_sistemas WHERE clave = a.TIPO_PROBLEMA) AS SISTEMA, 
		a.USUARIO, a.ASIGNADO_A, a.OFICINA, a.FECHA_APERTURA, a.FECHA_CIERRE,
		(SELECT sistema FROM cat_sistemas WHERE clave = a.TIPO_PROBLEMA) AS SISTEMA,
		IF(ISNULL(a.FECHA_CIERRE) = 1, '--',
		  IF((SELECT COUNT(b.ID_CASO) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO AND b.FECHA_AUTORIZACION IS NOT null) > 0, 
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, (SELECT MIN(b.FECHA_AUTORIZACION) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO))),
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, a.FECHA_APERTURA)))) AS T_RESOLUCION, 
		a.AUTORIZADO_POR
		FROM casos a 
			WHERE
				a.FECHA_APERTURA 
					BETWEEN DATE_SUB(DATE_ADD(CURDATE(), INTERVAL 1 DAY), INTERVAL 3 MONTH) 
					AND DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
					AND a.OFICINA = '".$_GET['ofi']."'";
	
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

if($result->num_rows > 0) {
	$resultados = "<center><table>
		<thead>
			<tr>
				<th>--</th>
				<th>ID CASO</th>
				<th>ESTADO</th>
				<th>USUARIO</th>
				<th>PROBLEMA</th>
				<th>DESCRIPCION</th>
				<th>ASIGNADO A</th>
				<th>CATEGORIA</th>
				<th>TIEMPO DE ATENCION</th>
			</tr>
		</thead>
		<tbody id='data'>";
	$i = 1;
	while($row = $result->fetch_assoc()) {
		$resultados .= 
			"<tr onmouseover='this.style.background=\"#CFE0F1\"' onmouseout='this.style.background=\"#EAEFF5\"'>
				<td>".$i."</td>
				<td><a href='http://sistemas.tpitic.com.mx/HelpDesk/php/mostrar_caso_detalles.php?idcaso=".utf8_encode($row['ID_CASO'])."'>".utf8_encode($row['ID_CASO'])."</a></td>
				<td>".utf8_encode($row['ESTADO_CASO'])."</td>
				<td>".utf8_encode($row['USUARIO'])."</td>
				<td>".utf8_encode($row['PROBLEMA'])."</td>
				<td>".utf8_encode($row['DESCRIPCION'])."</td>
				<td>".utf8_encode($row['ASIGNADO_A'])."</td>
				<td>".utf8_encode($row['SISTEMA'])."</td>
				<td>".utf8_encode($row['T_RESOLUCION'])."</td>
			</tr>";	
		$i++;
	}
	$resultados .= "</tbody></table></center>";
	echo $resultados;
}
else {
	echo 'NO SE ENCONTRARON RESULTADOS';	
}
// CLOSE CONNECTION
mysqli_close($mysqli);
?>
</div>
</body>
</html>