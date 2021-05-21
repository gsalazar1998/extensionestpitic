<?php
$link = mysql_connect("dbmsql.transportespitic.com","adminusertpitic","adminusertpitic");
mysql_select_db("facturas",$link);

$ccoi = isset($_POST['cuenta_coi']) ? $_POST['cuenta_coi'] : "NULL";
$rfc = isset($_POST['rfc']) ? $_POST['rfc'] : "NULL";
$proveedor = isset($_POST['proveedor']) ? $_POST['proveedor'] : "NULL";
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : "NULL";
if($tipo == "CBB"){
//CBB
	$foliosaut = isset($_POST['folios_aut']) ? $_POST['folios_aut'] : "NULL";
	$num_sicofi = isset($_POST['num_sicofi']) ? $_POST['num_sicofi'] : "NULL";
	$folios_aut = isset($_POST['folios_aut']) ? $_POST['folios_aut'] : "NULL";
	$serie = isset($_POST['serie']) ? $_POST['serie'] : "NULL";
	$folio_fac = isset($_POST['folio_fac']) ? $_POST['folio_fac'] : "NULL";
}
if($tipo == "CFD"){
//CFD
	$no_aprobacion = isset($_POST['no_aprobacion']) ? $_POST['no_aprobacion'] : "NULL";
	$ano_apro = isset($_POST['ano_apro']) ? $_POST['ano_apro'] : "NULL";
	$serie_cert = isset($_POST['serie_cert']) ? $_POST['serie_cert'] : "NULL";
	$serie = isset($_POST['serie']) ? $_POST['serie'] : "NULL";
	$folio_fact = isset($_POST['folio_fact']) ? $_POST['folio_fact'] : "NULL";
}

if($tipo == "CFDI"){
//CFDI
	$serie_cert_digital = isset($_POST['serie_cert_digital']) ? $_POST['serie_cert_digital'] : "NULL";
	$serie_sat = isset($_POST['serie_sat']) ? $_POST['serie_sat'] : "NULL";
	$fec_certificacion = isset($_POST['fec_certificacion']) ? $_POST['fec_certificacion'] : "NULL";
	$folio_fisca = isset($_POST['folio_fisca']) ? $_POST['folio_fisca'] : "NULL";
	$serie = isset($_POST['serie']) ? $_POST['serie'] : "NULL";
	$folio_fac = isset($_POST['folio_fac']) ? $_POST['folio_fac'] : "NULL";
}

$xmls = isset($_POST['xmls']) ? $_POST['xmls'] : "NULL";
$pdfs = isset($_POST['pdfs']) ? $_POST['pdfs'] : "NULL";

/*$archivo = $_FILES["subir_factura"]["tmp_name"]; 
$tamanio = $_FILES["subir_factura"]["size"];
$tipo    = $_FILES["subir_factura"]["type"];
$nombre  = $_FILES["subir_factura"]["name"];
$titulo  = $_POST["titulo"];*/
if($pdfs != "NULL"){
	$query = "SELECT MAX(ID_REGISTRO) as MAXIMO FROM facturas;";
	$rset = mysql_query($query)or die(mysql_error());
	$result = mysql_fetch_array($rset);
	$max = $result['MAXIMO']+1;
	$archivo = "facturas/".$pdfs;

	$pdfs = substr($pdfs, 0, -4);
	$new_name_archivo = "facturas/".$pdfs."_".$max.".pdf";
	rename($archivo, $new_name_archivo);
	
	$pdfs_renamed = $pdfs."_".$max.".pdf";
}

if($xmls != "NULL" and $xmls != ""){
	$archivo = "facturas/".$xmls;
	if ( $archivo != "none" ){
		$fp = fopen($archivo, "rb");
		$contenido = fread($fp, filesize($archivo));
		$contenido = addslashes($contenido);
		fclose($fp); 
		if($contenido != ""){
			//$query = "INSERT INTO facturas VALUES('NULL',CURDATE(),'".$ccoi."','".$rfc."','".$proveedor."','".$foliosaut."','".$feccaduca."','".$fecauto."','".$verifUser."', '".$pdfs_renamed."','".$contenido."')";
			$query = "INSERT INTO facturas VALUES('NULL',CURDATE(),'".$ccoi."','".$rfc."','".$proveedor."', null, null, '".$fecauto."','".$verifUser."', '".$pdfs_renamed."','".$contenido."')";
		}else{
			echo "<script>alert('esta vacio');</script>";
		}
		$rs = mysql_query($query)or die(mysql_error());

		if(mysql_affected_rows($link) > 0){
			if(file_exists ($archivo)){
				if(unlink($archivo)){
					echo "OK";
				}else{
					echo "error al borrar archivo";
				}
			}else{
				echo "no existe el archivo";
			}
		}else{
			echo "ERROR";
		}
	}else{
		echo "No se ha podido subir el archivo al servidor";
	}
}else{
	echo "xmls is null";
}
?>