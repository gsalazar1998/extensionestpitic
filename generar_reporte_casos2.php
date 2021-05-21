<?php
require_once 'php/PHPExcel/PHPExcel.php';
    $conexion = new mysqli('dbmysql.transportespitic.com','helpdesk','h31pd35k51573m45','SISP',3306);
	if (mysqli_connect_errno()) {
    	printf("La conexion con el servidor de base de datos fallo: %s\n", mysqli_connect_error());
    	exit();
	}
	
	// Se crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel();
	
	$consulta1 = "SELECT abrev FROM oficinas ORDER BY abrev ASC";
	$resultado1 = $conexion->query($consulta1);
	$i = 4;
	while ($fila = $resultado1->fetch_array()) {
		$objPHPExcel->setActiveSheetIndex(0)
        		    ->setCellValue('A'.$i,  $fila['abrev']);
					
		$consulta2 = "SELECT COUNT(*) AS ABIERTOS FROM casos WHERE FECHA_APERTURA < '2014-05-23' AND ESTADO_CASO = 1 
		AND OFICINA = '".$fila['abrev']."' GROUP BY OFICINA ORDER BY OFICINA";
		
		/*$consulta2 = "SELECT COUNT(*) AS ABIERTOS FROM casos WHERE FECHA_APERTURA BETWEEN
		DATE_SUB(CURDATE(),INTERVAL '14' DAY) AND DATE_SUB(CURDATE(),INTERVAL '7' DAY) AND ESTADO_CASO = 1 
		AND OFICINA = '".$fila['abrev']."' GROUP BY OFICINA ORDER BY OFICINA";*/
		$resultado2 = $conexion->query($consulta2);

		while ($fila2 = $resultado2->fetch_array()) {
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.$i,  $fila2['ABIERTOS']);
		}
		
		/*$consulta3 = "SELECT COUNT(*) AS ABIERTOS_ACT FROM casos WHERE FECHA_APERTURA BETWEEN
		DATE_SUB(CURDATE(),INTERVAL '7' DAY) AND DATE_ADD(CURDATE(), INTERVAL '1' DAY) AND OFICINA = '".$fila['abrev']."' 
		GROUP BY OFICINA ORDER BY OFICINA";*/
		
		//ABIERTOS ESTA SEMANA
		$consulta3 = "SELECT COUNT(*) AS ABIERTOS_ACT FROM casos WHERE FECHA_APERTURA BETWEEN
		'2014-05-31' AND '2014-06-06' AND OFICINA = '".$fila['abrev']."' 
		GROUP BY OFICINA ORDER BY OFICINA";
		$resultado3 = $conexion->query($consulta3);
		while ($fila3 = $resultado3->fetch_array()) {
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.$i,  $fila3['ABIERTOS_ACT']);
		}
		
		/*$consulta4 = "SELECT COUNT(*) AS CERRADOS FROM casos WHERE FECHA_CIERRE BETWEEN
		DATE_SUB(CURDATE(),INTERVAL '7' DAY) AND DATE_ADD(CURDATE(), INTERVAL '1' DAY) AND (ESTADO_CASO = 0 OR ESTADO_CASO = 2) AND OFICINA = '".$fila['abrev']."'
		GROUP BY OFICINA ORDER BY OFICINA";*/
		
		//CERRADOS
		$consulta4 = "SELECT COUNT(*) AS CERRADOS FROM casos WHERE FECHA_CIERRE BETWEEN
		'2014-05-31' AND '2014-06-06' AND (ESTADO_CASO = 0 OR ESTADO_CASO = 2) AND OFICINA = '".$fila['abrev']."'
		GROUP BY OFICINA ORDER BY OFICINA";
		$resultado4 = $conexion->query($consulta4);
		while ($fila4 = $resultado4->fetch_array()) {
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('D'.$i,  $fila4['CERRADOS']);
		}
		//Se realizara la suma de los casos anteriores
		$locale = 'es';
		$validLocale = PHPExcel_Settings::setLocale($locale);
		if (!$validLocale) {
			echo 'Unable to set locale to '.$locale." - reverting to en_us<br />\n";
		}
		$formula = "=SUM(B".$i." + C".$i." - D".$i.")";
		$objPHPExcel->getActiveSheet()->setCellValue("E".$i, $formula);
		
		/*REPORTE DE LOS 5 PROBLEMAS MAS RECURRENTES POR OFICINA */
		$q = "SELECT b.sistema, COUNT(*) AS NUM_CASOS FROM casos a 
		INNER JOIN cat_sistemas b ON a.TIPO_PROBLEMA = b.CLAVE
		WHERE a.OFICINA = '".$fila['abrev']."' AND FECHA_APERTURA BETWEEN '2014-05-31' AND '2014-06-06'
		GROUP BY b.sistema ORDER BY NUM_CASOS DESC";
		
		/*$q = "SELECT b.sistema, COUNT(*) AS NUM_CASOS FROM casos a 
		INNER JOIN cat_sistemas b ON a.TIPO_PROBLEMA = b.CLAVE
		WHERE a.OFICINA = '".$fila['abrev']."' AND FECHA_APERTURA BETWEEN
		DATE_SUB(CURDATE(),INTERVAL '7' DAY) AND DATE_ADD(CURDATE(), INTERVAL '1' DAY)
		GROUP BY b.sistema ORDER BY NUM_CASOS DESC";*/
		$resultadoq = $conexion->query($q);
		$problemasRec = "";
		$prc = 0; //Problemas Recurrentes Contador
		while ($filaq = $resultadoq->fetch_array()) {
			if($prc < 5){
				$problemasRec .= $filaq['sistema']."(".$filaq['NUM_CASOS']."), ";
				$prc++;
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('F'.$i,  $problemasRec);
		//Agregar comentario
		$consultaComment = "SELECT AVG(TIMESTAMPDIFF(MINUTE, FECHA_APERTURA, FECHA_ASIGNACION)/60) AS TIEMPO_ASIGNACION FROM casos WHERE 
		OFICINA = '".$fila['abrev']."' AND FECHA_APERTURA < '2014-05-23'";
		
		/*$consultaComment = "SELECT AVG(TIMESTAMPDIFF(MINUTE, FECHA_APERTURA, FECHA_ASIGNACION)/60) AS TIEMPO_ASIGNACION FROM casos WHERE 
		OFICINA = '".$fila['abrev']."' AND FECHA_APERTURA BETWEEN DATE_SUB(CURDATE(),INTERVAL '14' DAY) AND DATE_ADD(CURDATE(), INTERVAL '1' DAY) ";*/
		$resultado5 = $conexion->query($consultaComment);
		while ($fila5 = $resultado5->fetch_array()) {
			$objPHPExcel->getActiveSheet()
				->getComment('A'.$i)
				->setAuthor('Misael Burboa');
			$objCommentRichText = $objPHPExcel->getActiveSheet()
				->getComment('A'.$i)
				->getText()->createTextRun('AVG asignacion '.$fila['abrev'].':');
			$objCommentRichText->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()
				->getComment('A'.$i)
				->getText()->createTextRun("\r\n");
			$objPHPExcel->getActiveSheet()
				->getComment('A'.$i)
				->getText()->createTextRun(round($fila5['TIEMPO_ASIGNACION'])." Hrs.");
		}
		
		/*$consultaComment2 = "SELECT 
		AVG(IF(ISNULL(a.FECHA_CIERRE) = 1, '--',
		IF((SELECT COUNT(b.ID_CASO) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO AND b.FECHA_AUTORIZACION IS NOT null) > 0, 
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, (SELECT MIN(b.FECHA_AUTORIZACION) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO))),
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, a.FECHA_APERTURA))))) AS T_RESOLUCION
			FROM casos a WHERE 
			OFICINA = '".$fila['abrev']."' AND a.FECHA_APERTURA BETWEEN DATE_SUB(CURDATE(),INTERVAL '14' DAY) AND DATE_ADD(CURDATE(), INTERVAL '1' DAY)";*/
			
		$consultaComment2 = "SELECT 
		AVG(IF(ISNULL(a.FECHA_CIERRE) = 1, '--',
		IF((SELECT COUNT(b.ID_CASO) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO AND b.FECHA_AUTORIZACION IS NOT null) > 0, 
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, (SELECT MIN(b.FECHA_AUTORIZACION) FROM casos_autorizaciones b WHERE b.ID_CASO = a.ID_CASO))),
			CONCAT(TIMEDIFF(a.FECHA_CIERRE, a.FECHA_APERTURA))))) AS T_RESOLUCION
			FROM casos a WHERE 
			OFICINA = '".$fila['abrev']."' AND a.FECHA_APERTURA BETWEEN '2014-05-31' AND '2014-06-06'";
		$resultado6 = $conexion->query($consultaComment2);
		while ($fila6 = $resultado6->fetch_array()) {
			$objPHPExcel->getActiveSheet()
				->getComment('D'.$i)
				->setAuthor('Misael Burboa');
			$objCommentRichText = $objPHPExcel->getActiveSheet()
				->getComment('D'.$i)
				->getText()->createTextRun('AVG Resolucion '.$fila['abrev'].':');
			$objCommentRichText->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()
				->getComment('D'.$i)
				->getText()->createTextRun("\r\n");
			$objPHPExcel->getActiveSheet()
				->getComment('D'.$i)
				->getText()->createTextRun(round($fila6['T_RESOLUCION'])." Hrs.");
		}
			
		
		
		
		$i++;
	}
	
	$objPHPExcel->getActiveSheet()->setCellValue("A".$i, 'TOTAL');
	//Se realizara la suma de los casos anteriores
	$locale = 'es';
	$validLocale = PHPExcel_Settings::setLocale($locale);
	if (!$validLocale) {
		echo 'Unable to set locale to '.$locale." - reverting to en_us<br />\n";
	}
	$formula = "=SUM(B4:B41)";
	$objPHPExcel->getActiveSheet()->setCellValue("B".$i, $formula);
	$formula = "=SUM(C4:C41)";
	$objPHPExcel->getActiveSheet()->setCellValue("C".$i, $formula);
	$formula = "=SUM(D4:D41)";
	$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $formula);
	$formula = "=SUM(E4:E41)";
	$objPHPExcel->getActiveSheet()->setCellValue("E".$i, $formula);
	
	//if($resultado->num_rows > 0 ){

		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator("Codedrinks") //Autor
							 ->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
							 ->setTitle("Reporte Excel con PHP y MySQL")
							 ->setSubject("Reporte Excel con PHP y MySQL")
							 ->setDescription("Reporte de Casos")
							 ->setKeywords("reporte casos")
							 ->setCategory("Reporte excel");

		$tituloReporte = "Relacion de Casos";
		$titulosColumnas = array('OFICINA', 'ANTERIORES', 'ESTA SEMANA', 'CERRADOS ESTA SEMANA', 'TOTAL PENDIENTES', 'CATEGORIA MAS RECURRENTE DURANTE LA SEMANA', 'NUM. CASOS');
		
		$objPHPExcel->setActiveSheetIndex(0)
        		    ->mergeCells('A1:G1');
						
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A3',  $titulosColumnas[0])
		            ->setCellValue('B3',  $titulosColumnas[1])
        		    ->setCellValue('C3',  $titulosColumnas[2])
            		->setCellValue('D3',  $titulosColumnas[3])
					->setCellValue('E3',  $titulosColumnas[4])
					->setCellValue('F3',  $titulosColumnas[5]);
		
		$estiloTituloReporte = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size' =>16,
	            	'color'     => array(
    	            	'rgb' => 'FFFFFF'
        	       	)
            ),
	        'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'	=> array('argb' => 'FF220835')
			),
            'borders' => array(
               	'allborders' => array(
                	'style' => PHPExcel_Style_Border::BORDER_NONE                    
               	)
            ), 
            'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'   => 0,
        			'wrap'          => TRUE
    		)
        );

		$estiloTituloColumnas = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
        		'startcolor' => array(
            		'rgb' => 'c47cf2'
        		),
        		'endcolor'   => array(
            		'argb' => 'FF431a5d'
        		)
			),
            'borders' => array(
            	'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => TRUE
    		));
			
		$estiloInformacion = new PHPExcel_Style();
		$estiloInformacion->applyFromArray(
			array(
           		'font' => array(
               	'name'      => 'Arial',               
               	'color'     => array(
                   	'rgb' => '000000'
               	)
           	),
           	'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'		=> array('argb' => 'F5F5DC')
			),
           	'borders' => array(
               	'left'     => array(
                   	'style' => PHPExcel_Style_Border::BORDER_THIN ,
	                'color' => array(
    	            	'rgb' => '3a2a47'
                   	)
               	)             
           	),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => TRUE
    		)
        ));
		
		$estiloTotales = new PHPExcel_Style();
		$estiloTotales->applyFromArray(
			array(
           		'font' => array(
               	'name'      => 'Arial',               
               	'color'     => array(
                   	'rgb' => '000000'
               	)
           	),
           	'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'		=> array('argb' => 'FF6600')
			),
           	'borders' => array(
               	'left'     => array(
                   	'style' => PHPExcel_Style_Border::BORDER_THIN ,
	                'color' => array(
    	            	'rgb' => '000000'
                   	)
               	)             
           	),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => TRUE
    		)
        ));
		 
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:F".($i-1));
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloTotales, "A42:F42");
				
		for($i = 'A'; $i <= 'G'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Casos');

		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);
		// Inmovilizar paneles 
		//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

		// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Reportedecasos.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
		
	/*}
	else{
		print_r('No hay resultados para mostrar');
	}*/
?>