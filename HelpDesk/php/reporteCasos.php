<?php
require_once("MySQL/ErrorManager.class.php");
require_once("MySQL/MySQL.class.php");

switch($_GET['opc']){
	case 1: tablaVacía(); break;
	case 2: obtenerReporteFechas(); break;
}

function tablaVacía(){
	echo $nassign = '{ "aaData": []}';
}
function obtenerReporteFechas(){
	$mysql = new MySQL();
	$mysql->connect(); 

	$asignadoa = isset($_POST['asignadoa_rpt']) ? $_POST['asignadoa_rpt'] : "";
	$depto = isset($_POST['depto']) ? $_POST['depto'] : "";
	$ffin = isset($_POST['f_fin']) ? $_POST['f_fin'] : "";
	$finicio = isset($_POST['f_ini']) ? $_POST['f_ini'] : "";
	$mostrar_tipo = isset($_POST['mostrar_tipo']) ? $_POST['mostrar_tipo'] : "";
	$no_caso = isset($_POST['no_caso_rpt']) ? $_POST['no_caso_rpt'] : "";
	$oficina = isset($_POST['oficinas']) ? $_POST['oficinas'] : "";
	$sistema = isset($_POST['sistemas']) ? $_POST['sistemas'] : "";
	$ano_ini = isset($_POST['ano_inicio']) ? $_POST['ano_inicio'] : "";
	$ano_fin = isset($_POST['ano_fin']) ? $_POST['ano_fin'] : "";
	$vencido = isset($_POST['vencidos']) ? $_POST['vencidos'] : "";
	if($ffin == "" && $finicio ==""){
		$finicio = $ano_ini."/01/01";
		$ffin = $ano_fin."/12/31";
	}elseif($finicio != "" && $ffin != ""){
		
	}else{
		echo "<script>alert('debe seleccionar ambas fechas o dejar ambas en blanco para tomar los años como referencia');</script>";
	}

	/*$query = "SELECT 
	  ID_CASO,
	  CASE ESTADO_CASO WHEN 1 THEN 'Abierto' WHEN 0 THEN 'Cerrado' WHEN 2 THEN 'Esperando Conf.' WHEN 3 THEN 'Cerrado SIN Conf.' ELSE 'No Definido' END AS ESTADO_CASO,
	  (SELECT sistema FROM cat_sistemas WHERE clave = TIPO_PROBLEMA) AS SISTEMA,
	  USUARIO,
	  ASIGNADO_A,
	  OFICINA,
	  FECHA_APERTURA,
	  FECHA_CIERRE,
	  IF(ISNULL(FECHA_CIERRE) = 1,
		'--',
		CONCAT(TIMEDIFF(FECHA_CIERRE,FECHA_APERTURA))) AS T_RESOLUCION
	FROM casos
	  WHERE
		FECHA_APERTURA BETWEEN '".$finicio."' AND DATE_ADD('".$ffin."', INTERVAL 1 DAY)";
	if($asignadoa != ""){$query.=" AND ASIGNADO_A = '".$asignadoa."'";}
	if($depto != ""){$query.=" AND DEPTO = '".$depto."'";}
	if($no_caso != ""){$query.=" AND ID_CASO = '".$no_caso."'";}
	if($oficina != ""){$query.=" AND OFICINA = '".$oficina."'";}
	if($sistema != ""){$query.=" AND TIPO_PROBLEMA = '".$sistema."'";}
	switch($mostrar_tipo){
		case "closed": $query .= " AND ESTADO_CASO = '0'"; break;
		case "opened": $query .= " AND ESTADO_CASO = '1'"; break;
		case "waiting": $query .= " AND ESTADO_CASO = '2'"; break;
		case "closedsc": $query .= " AND ESTADO_CASO = '3'"; break;
	}*/
	$query = "SELECT 
		a.ID_CASO, 
		CASE a.ESTADO_CASO WHEN 1 THEN 'Abierto' WHEN 0 THEN 'Cerrado' WHEN 2 THEN 'Esperando Conf.' WHEN 3 THEN 'Cerrado' ELSE 'No Definido' END AS ESTADO_CASO, 
		(SELECT sistema FROM cat_sistemas WHERE clave = a.TIPO_PROBLEMA) AS SISTEMA, 
		a.USUARIO, a.ASIGNADO_A, a.OFICINA, a.FECHA_APERTURA, a.FECHA_CIERRE,
		IF(ISNULL(a.FECHA_CIERRE) = 1, '--', 
		  IF((SELECT COUNT(b.ID_CASO) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO AND b.FECHA_AUTORIZACION IS NOT null) > 0, 
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, (SELECT MIN(b.FECHA_AUTORIZACION) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO))),
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, a.FECHA_APERTURA)))) AS T_RESOLUCION, 
		a.AUTORIZADO_POR
		FROM casos a 
			WHERE a.FECHA_APERTURA BETWEEN '".$finicio."' AND DATE_ADD('".$ffin."', INTERVAL 1 DAY)";
		if($asignadoa != ""){$query.=" AND ASIGNADO_A = '".$asignadoa."'";}
		if($depto != ""){$query.=" AND DEPTO = '".$depto."'";}
		if($no_caso != ""){$query.=" AND ID_CASO = '".$no_caso."'";}
		if($oficina != ""){$query.=" AND OFICINA = '".$oficina."'";}
		if($sistema != ""){$query.=" AND TIPO_PROBLEMA = '".$sistema."'";}
		if($vencido){$query .= " AND TIEMPO_VENCIDO = 1";}
		switch($mostrar_tipo){
			case "closed": $query .= " AND ESTADO_CASO = '0'"; break;
			case "opened": $query .= " AND ESTADO_CASO = '1'"; break;
			case "waiting": $query .= " AND ESTADO_CASO = '2'"; break;
			case "closedsc": $query .= " AND ESTADO_CASO = '3'"; break;
		}
	//echo $query;
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	/*if($numero >0){
		$nassign = '{ "aaData": [';
		while($row = mysql_fetch_array($result)){
			$nassign .='["'.$row['ID_CASO'].'","'.$row['ESTADO_CASO'].'","'.$row['SISTEMA'].'","'.$row['USUARIO'].'","'.$row['OFICINA'].'","'.$row['FECHA_APERTURA'].'","'.$row['FECHA_CIERRE'].'","'.$row['T_RESOLUCION'].'"],';
		}

		echo $nassign = substr($nassign,0, -1)."]}";
	}else{
		echo $nassign = '{ "aaData": []}';
	}*/
	$tabla = "<table cellpadding='0' cellspacing='0' border='0' class='display fuente' id='TReporte_casos' style='width:100%;'>
		<thead width='100%'>
			<tr>
				<th>caso</th>
				<th>Estado</th>
				<th>Sistema</th>
				<th>Abrio</th>
				<th>Asignado a</th>
				<th>Oficina</th>
				<th>Fecha</th>
				<th>Fecha Cierre</th>
				<th>Tiempo Respuesta</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
			</tr>
		</tfoot>
		<tbody>";
		while($row = mysql_fetch_array($result)){
			$tabla .='<tr><td>'.$row['ID_CASO'].'</td><td>'.$row['ESTADO_CASO'].'</td><td>'.$row['SISTEMA'].'</td><td>'.$row['USUARIO'].'</td><td>'.$row['ASIGNADO_A'].'</td><td>'.$row['OFICINA'].'</td><td>'.$row['FECHA_APERTURA'].'</td><td>'.$row['FECHA_CIERRE'].'</td><td>'.$row['T_RESOLUCION'].'</td></tr>';
		}
		$tabla .= "</tbody></table>";
		
		$script = "<script>
		
		selectedRPT = [];
		var TReporteCasos = $('#TReporte_casos').dataTable({
		'bJQueryUI': true,
		'sPaginationType': 'full_numbers',
		'bProcessing': true,
	});

	$('#TReporte_casos tbody tr ').live('click', function () {
		var aData = TReporteCasos.fnGetData(this );
		var iId = aData[0];
		
		$('#TReporte_casos tbody tr ').removeClass('row_selected');
		$(this).addClass('row_selected');
		if ( jQuery.inArray(iId, selectedRPT) == -1 )
		{
			selectedRPT[0] = iId;
			if(selectedRPT.length>0){
				if(selectedRPT.length>1){
					$('#link_asignarReporte').html('<a href=\'#\' id=\'asignacaso_link\' class=\'ui-state-default ui-corner-all\'><span class=\'ui-icon ui-icon-arrowreturnthick-1-e\'></span>Asignar</a>');
				}else{
					$('#link_asignarReporte').html('<a href=\'#\' id=\'detallesReportes\' class=\'ui-state-default ui-corner-all\'><span class=\'ui-icon ui-icon-arrowreturnthick-1-e\'></span>Detalles</a>');
				}
			}
		}
		else{}
	});
		</script>";
		echo $tabla.$script;
}

?>
