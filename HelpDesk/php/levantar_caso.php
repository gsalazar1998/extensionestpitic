<?php require_once("MySQL/ErrorManager.class.php"); ?>
<?php require_once("MySQL/MySQL.class.php"); ?>
<?php require_once("phpmailer/class.phpmailer.php"); ?>
<?php require_once("funciones_generales.php"); ?>
<?php
	$mysql = new MySQL();
	$mysql->connect(); 

	function eliminar_caracteres_prohibidos($arreglo){
        $prohibidos = array("'","\"");    
        return str_replace($caracteres_prohibidos,"",$arreglo);
    }  
	
	if(isset($_POST['usuario']) && isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['noempleado']) && isset($_POST['lada']) && isset($_POST['ext'])
	&& isset($_POST['depto']) && isset($_POST['oficinas'])){
	
		if($_POST['usuario'] == "" || $_POST['usuario'] == null || $_POST['nombre'] == "" || $_POST['nombre'] == null || $_POST['correo'] == "" || $_POST['correo'] == null
		 || $_POST['noempleado'] == "" || $_POST['noempleado'] == null || $_POST['lada'] == "" || $_POST['lada'] == null || $_POST['ext'] == "" || $_POST['ext'] == null 
		 || $_POST['depto'] == "" || $_POST['depto'] == null || $_POST['oficinas'] == "" || $_POST['oficinas'] == null){
			echo "Hubo un error al tratar de guardar los datos por favor intente de nuevo";
		 }else{
		 
			$query = "INSERT INTO casos VALUES(
				NULL, 
				'".trim(str_replace("'", "", addslashes($_POST['problema'])))."',
				'".trim(str_replace("'", "", addslashes($_POST['descripcion'])))."',
				NULL,
				'".trim($_POST['usuario'])."',
				'".trim($_POST['nombre'])."',
				'".trim($_POST['correo'])."',
				'".trim($_POST['noempleado'])."',
				'(".trim($_POST['lada']).")-".trim($_POST['numero'])."-".trim($_POST['ext'])."',
				'".trim($_POST['depto'])."',
				'".trim($_POST['oficinas'])."',
				'NO',
				'".trim($_POST['adjuntos'])."',
				'1',
				NOW(),
				NULL,
				NULL,
				NULL,
				NULL,
				NULL,
				'0',
				NULL,
				NULL
			)";

			$result = $mysql->query($query);
			if($result){
				$titulo = "Se ha dado de alta un nuevo caso";
				$cuerpo = $_POST['nombre']." (".$_POST['usuario'].") Ha dado de alta un nuevo caso. Aqui su descripcion:<br /><br />".$_POST['descripcion'];
				$objFN = new FuncionesGrales();
				$tipo = "levantarcaso";
				$objFN->enviarMail($titulo, $cuerpo, "support", $tipo);
				//$objFN->enviarMail($titulo, $cuerpo, "acota", $tipo); //CORREO ALEX
				//$objFN->enviarMail($titulo, $cuerpo, "cmburboa", $tipo); //CORREO Misael
				echo "OK";
			}
		}
	}
?>
