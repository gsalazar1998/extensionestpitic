 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">

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
</head>
<body>

<div id="resultados">
<h2>Reporte de casos por oficinas</h2>
<div id="formulario">
<form id="reporteCasosForm" method="post" action="" onChange="javascript:this.submit();">
		<select id="region" name="region">
		<option value="">Seleccione</option>
		<option value="1">Norte</option>
		<option value="2">Noroeste</option>
		<option value="3">Centro</option>
		<option value="4">Occidente</option>
		<option value="5">Sureste</option>
		<option value="6">Corporativo</option>
		<option value="7">Otros</option>
		<option value="8">Todas</option>
	</select>
</form>
</div>
&Uacute;ltimos 3 meses
<?php
/* pChart library inclusions */
include("lib_graficos/class/pData.class.php");
include("lib_graficos/class/pDraw.class.php");
include("lib_graficos/class/pImage.class.php");

$mysqli = new mysqli('dbmsql.transportespitic.com', 'helpdesk', 'h31pd35k51573m45', 'SISP');
if (mysqli_connect_errno()) {
	echo "Connect failed: ".mysqli_connect_error();
	exit();
}
if(isset($_POST['region'])){
	$query = "
	SELECT
	b.abrev AS OFICINA,
	(SELECT nombre FROM regiones WHERE id_region = b.region) AS REGION,
	SUM(CASE WHEN a.ESTADO_CASO = 0 || a.ESTADO_CASO = 3 THEN 1 ELSE 0 END) AS ATENDIDOS,
	SUM(CASE WHEN a.ESTADO_CASO = 1 THEN 1 ELSE 0 END) AS ABIERTOS,
	SUM(CASE WHEN a.ESTADO_CASO = 2 THEN 1 ELSE 0 END) AS ESPERANDO,
	COUNT(ESTADO_CASO) AS TOTAL,
	FORMAT((AVG(IF((SELECT COUNT(b.ID_CASO) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO AND b.FECHA_AUTORIZACION IS NOT null) > 0, 
		CONCAT(TIMESTAMPDIFF(MINUTE,(SELECT MIN(b.FECHA_AUTORIZACION) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO), a.FECHA_CIERRE)),
		CONCAT(TIMESTAMPDIFF(MINUTE, a.FECHA_APERTURA, a.FECHA_CIERRE)))))/60,2) AS AVG_ATENCION

	FROM  oficinas b LEFT JOIN SISP.casos a ON b.abrev = a.oficina
	WHERE "; 
	if($_POST['region'] < 8){
		$query .= "b.region = ".$_POST['region']." AND";
	}elseif($_POST['region'] == 8){
	
	}
	$query .= " a.FECHA_APERTURA BETWEEN DATE_SUB(DATE_ADD(CURDATE(), INTERVAL 1 DAY), INTERVAL 3 MONTH) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
	/*OR a.OFICINA IS NULL*/

	GROUP BY a.OFICINA ORDER BY TOTAL DESC";
	
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	// Recorremos la informacion
	$cant_casos = array();
	$oficinas = array();
	if($result->num_rows > 0) {
		$resultados = "<center><table>
			<thead>
				<tr>
					<th>REGION</th>
					<th>OFICINA</th>
					<th>ATENDIDOS</th>
					<th>ABIERTOS</th>
					<th>ESPERANDO</th>
					<th>TOTAL</th>
					<th>AVG ATENCION</th>
				</tr>
			</thead>";
		while($row = $result->fetch_assoc()) {
			$cant_casos[] = $row['TOTAL'];
			$oficinas[] = $row['OFICINA'];
			$resultados .= 
				"<tr onmouseover='this.style.background=\"#CFE0F1\"' onmouseout='this.style.background=\"#EAEFF5\"'>
					<td>".stripslashes($row['REGION'])."</td>
					<td>".stripslashes($row['OFICINA'])."</td>
					<td>".stripslashes($row['ATENDIDOS'])."</td>
					<td>".stripslashes($row['ABIERTOS'])."</td>
					<td>".stripslashes($row['ESPERANDO'])."</td>
					<td class='oscura'><a href='reporteCasosTOTALES.php?ofi=".stripslashes($row['OFICINA'])."'>".stripslashes($row['TOTAL'])."</a></td>
					<td class='oscura'>";
					if(isset($row['AVG_ATENCION'])){
						$resultados .= stripslashes($row['AVG_ATENCION'])." Hrs.";
					}else{
						$resultados .= "--";
					}
					$resultados .= "</td>
				</tr>";	
		}
		$resultados .= "</table></center>";
		echo $resultados;
		
		// Create and populate the pData object 
		$MyData = new pData();  
		$MyData->addPoints($cant_casos, "Num. Casos");
		//$MyData->addPoints(array(140,0,340,300,320,300,200,100,50),"Server B");
		//$MyData->setAxisName(0,"Cantidad de casos");
		$MyData->addPoints($oficinas, "Oficinas");
		$MyData->setSerieDescription("Oficina","Oficinas");
		$MyData->setAbscissa("Oficinas");

		// Create the pChart object 
		$myPicture = new pImage(700,230,$MyData);

		// Turn of Antialiasing 
		$myPicture->Antialias = FALSE;

		// Add a border to the picture 
		$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
		$myPicture->drawGradientArea(0,0,700,230,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
		$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

		// Set the default font 
		if($_POST['region'] == 8){
			$myPicture->setFontProperties(array("FontName"=>"lib_graficos/fonts/verdana.ttf", "FontSize"=>6));
		}else{
			$myPicture->setFontProperties(array("FontName"=>"lib_graficos/fonts/verdana.ttf", "FontSize"=>10));
		}

		// Define the chart area 
		$myPicture->setGraphArea(60,40,650,200);

		// Draw the scale 
		$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
		$myPicture->drawScale($scaleSettings);

		// Write the chart legend 
		$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

		// Turn on shadow computing
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

		// Draw the chart
		$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		$settings = array("Surrounding"=>-30,"InnerSurrounding"=>30,"Interleave"=>0);
		$myPicture->drawBarChart($settings);

		// Render the picture (choose the best way)
		$myPicture->Render("graficas/casos_por_oficinas_barras.png");
		?>
		<br />
		<div style="margin:0 auto 0 auto; width:700px;"><img src="graficas/casos_por_oficinas_barras.png" /></div>
		<?php
	}
	else {
		echo 'NO SE ENCONTRARON RESULTADOS';	
	}
	// CLOSE CONNECTION
	mysqli_close($mysqli);
}else{
	echo "seleccione una region";
}
?>
</div>
</body>
</html>