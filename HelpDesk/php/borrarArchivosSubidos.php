<?php
//Este php se utilizara en caso de que el usuario suba archivos al servidor.. pero no habra el caso.. 
//si no se abre el caso este script se encarga de eliminar las imagenes
$path = $_SERVER['DOCUMENT_ROOT'] ."/HelpDesk/uploads/"; //path hacia uploads
$archivosBorrar = array();
$archivosBorrar = explode(",", $_POST['borrar']);
/*print("<pre>");
print_r($archivosBorrar);
print("</pre>");*/
foreach($archivosBorrar as $ab){
	if(file_exists ($path.$ab)){
		if(unlink($path.$ab)){
			echo "archivo borrado";
		}else{
			echo "error al borrar archivo";
		}
	}else{
		echo "no existe el archivo";
	}
}
?>
