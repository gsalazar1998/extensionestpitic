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

	$atendio = isset($_POST['asignadoa_rpt_ec']) ? $_POST['asignadoa_rpt_ec'] : "";
	$mostrar_tipo = isset($_POST['mostrar_tipo_ec']) ? $_POST['mostrar_tipo_ec'] : "";
	$ffin = isset($_POST['f_fin_ec']) ? $_POST['f_fin_ec'] : "";
	$finicio = isset($_POST['f_ini_ec']) ? $_POST['f_ini_ec'] : "";
	$no_actividad = isset($_POST['no_caso_rpt_ec']) ? $_POST['no_caso_rpt_ec'] : "";
	$oficina = isset($_POST['oficinas_ec']) ? $_POST['oficinas_ec'] : "";
	$ano_ini = isset($_POST['ano_inicio_ec']) ? $_POST['ano_inicio_ec'] : "";
	$ano_fin = isset($_POST['ano_fin_ec']) ? $_POST['ano_fin_ec'] : "";
	if($ffin == "" && $finicio ==""){
		$finicio = $ano_ini."/01/01";
		$ffin = $ano_fin."/12/31";
	}elseif($finicio != "" && $ffin != ""){
		
	}else{
		echo "<script>alert('debe seleccionar ambas fechas o dejar ambas en blanco para tomar los años como referencia');</script>";
	}

	$query = "SELECT 
		ID_ACTIVIDAD,
		CASE ESTADO_AUTORIZACION WHEN 1 THEN 'AUTORIZADO' WHEN 2 THEN 'RECHAZADO' ELSE 'ESPERANDO' END AS ESTADO_AUTORIZACION,
		PROBLEMA,
		USUARIO,
		NOMBRE,
		OFICINA,
		ATENDIDO_POR,
		FECHA,
		AUTORIZADO_POR,
		HORAS
		FROM act_extra_casos a 
			WHERE a.FECHA BETWEEN '".$finicio."' AND DATE_ADD('".$ffin."', INTERVAL 1 DAY)";
		if($atendio != ""){$query.=" AND ATENDIDO_POR = '".$atendio."'";}
		if($no_actividad != ""){$query.=" AND ID_ACTIVIDAD = '".$no_actividad."'";}
		if($oficina != ""){$query.=" AND OFICINA = '".$oficina."'";}
		switch($mostrar_tipo){
			case "all": $query .= " "; break;
			case "aut": $query .= " AND ESTADO_AUTORIZACION = 1"; break;
			case "notaut": $query .= " AND ESTADO_AUTORIZACION = 0"; break;
		}
	//echo $query;
	$result = $mysql->query($query);
	$numero = mysql_num_rows($result);

	$tabla = "<table cellpadding='0' cellspacing='0' border='0' class='display fuente' id='TReporte_casos_ec' style='width:100%;'>
		<thead width='100%'>
			<tr>
				<th>ACTIVIDAD</th>
				<th>USUARIO</th>
				<th>OFICINA</th>
				<th>FECHA</th>
				<th>HORAS</th>
				<th>AUTORIZO</th>
				<th>EDO. AUTORIZACION</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
			</tr>
		</tfoot>
		<tbody>";
		while($row = mysql_fetch_array($result)){
			$tabla .='<tr><td>'.$row['ID_ACTIVIDAD'].'</td><td>'.$row['USUARIO'].'</td><td>'.$row['OFICINA'].'</td><td>'.$row['FECHA'].'</td><td>'.$row['HORAS'].'</td><td>'.$row['AUTORIZADO_POR'].'</td><td>'.$row['ESTADO_AUTORIZACION'].'</td></tr>';
		}
		$tabla .= "</tbody></table>";
		
		$script = "<script>
		
		selectedRPT = [];
		var TReporteCasos = $('#TReporte_casos_ec').dataTable({
		'bJQueryUI': true,
		'sPaginationType': 'full_numbers',
		'bProcessing': true,
	});

	$('#TReporte_casos_ec tbody tr ').live('click', function () {
		var aData = TReporteCasos.fnGetData(this );
		var iId = aData[0];
		
		$('#TReporte_casos_ec tbody tr ').removeClass('row_selected');
		$(this).addClass('row_selected');
		if ( jQuery.inArray(iId, selectedRPT) == -1 )
		{
			selectedRPT[0] = iId;
			if(selectedRPT.length>0){
				if(selectedRPT.length>1){
					/*$('#link_asignarReporte_ec').html('<a href=\'#\' id=\'asignacaso_link_ec\' class=\'ui-state-default ui-corner-all\'><span class=\'ui-icon ui-icon-arrowreturnthick-1-e\'></span>Asignar</a>');*/
				}else{
					$('#link_asignarReporte_ec').html('<a href=\'#\' id=\'detallesReportes_ec\' class=\'ui-state-default ui-corner-all\'><span class=\'ui-icon ui-icon-arrowreturnthick-1-e\'></span>Detalles</a>');
				}
			}
		}
		else{}
	});
		</script>";
		echo $tabla.$script;
}

?>
