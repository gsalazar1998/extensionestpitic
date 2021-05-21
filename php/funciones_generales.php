<?php
require_once("phpmailer/class.phpmailer.php");
//para obtener las oficinas de la base de datos
class FuncionesGrales{
	function getOficinas(){
		//$link=mysql_connect('dbmsql.transportespitic.com','helpdesk','h31pd35k51573m45');
		$link=mysql_connect('mysqlhmo','feria','bodycombat');
		//mysql_select_db('SISP',$link)or die(mysql_error());
		mysql_select_db('firewall',$link)or die(mysql_error());
		$query="SELECT oficina,abrev FROM oficinas ORDER BY oficina ASC";
		$rs=mysql_query($query)or die(mysql_error());
		$oficinas = "<select id='oficinas' name='oficinas' class='validate[required]'><option value='' SELECTED >Seleccione</option>";
		$cont=0;
		while($row=mysql_fetch_array($rs)){
			$oficinas .="<option value='".$row['abrev']."'>".$row['oficina']."</option>";
		}
		$oficinas .= "</select>";
		return $oficinas;
	}
	
	function getSistemas(){
		$mysql = new MySQL();
		$mysql->connect();
		$query = "SELECT * FROM cat_sistemas";
		$result = $mysql->query($query);
		$comboSistemas = "<select id='sistemas' name='sistemas'><option value='' SELECTED>Seleccione</option>";
		while($row = mysql_fetch_array($result)){
			$comboSistemas.="<option value='".$row['clave']."'>".$row['sistema']."</option>";
		}
		$comboSistemas.="</select>";
		return $comboSistemas;
	}
	
	function getDeptos(){
		$mysql = new MySQL();
		$mysql->connect();
		$query = "SELECT * FROM departamentos";
		$result = $mysql->query($query);
		$comboDeptos = "<select id='depto' name='depto' class='validate[required]' ><option value=''>Seleccione</option>";
		while($row = mysql_fetch_array($result)){
			$comboDeptos.="<option value='".$row['CLAVE']."'>".$row['DEPARTAMENTO']."</option>";
		}
		$comboDeptos.="</select>";
		return $comboDeptos;
	}
	
	function existeUsuario($usuario){
		$con=ldap_connect("ldap.tpitic.com.mx");
		if(!$con){
			$con=ldap_connect("ldapbackup.tpitic.com.mx");
		}
		if($con) {
		   $bind=ldap_bind( $con,"cn=feria,dc=transportespitic,dc=com", "sistemaspitic" )or die("Can't bind to server.");
		   $srch=ldap_search($con, "dc=transportespitic,dc=com","uid=".$usuario);
		   $numero=ldap_count_entries($con, $srch);
		   $info = ldap_get_entries($con, $srch);
		}
		
		ldap_close($con);
		
		return $numero;
	}

	function enviarMail($titulo, $cuerpo, $destinatario){
		$mail = new phpmailer();
		$mail->PluginDir = "";
		$mail->Mailer = "smtp";
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPSecure = "ssl";
		$mail->SMTPAuth = true;
		$mail->Port = 465;

		/*$mysql = new MySQL();
		$mysql->connect();
		$query = "SELECT * FROM casos WHERE ID_CASO = '".$idCaso."'";
		$result = $mysql->query($query);
		$numero = mysql_num_rows($result);
		$row = mysql_fetch_array($result);*/
		
		//Le decimos cual es nuestro nombre de usuario y password
		$mail->Username = "helpdesk@tpitic.com.mx"; 
		$mail->Password = "tp1t1c123";

		//Indicamos cual es nuestra dirección de correo y el nombre que 
		//queremos que vea el usuario que lee nuestro correo
		$mail->From = "sistemas@tpitic.com.mx";
		$mail->FromName = "HelpDesk del Departamento de Sistemas";

		//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
		//una cuenta gratuita, por tanto lo pongo a 30  
		$mail->Timeout=30;

		//Indicamos cual es la dirección de destino del correo
		$mail->AddAddress($destinatario."@tpitic.com.mx");
		//$mail->AddAddress("cmburboa@tpitic.com.mx"); //CORREO ALEX

		//Asignamos asunto y cuerpo del mensaje
		//El cuerpo del mensaje lo ponemos en formato html, haciendo 
		//que se vea en negrita
		$mail->Subject = $titulo;
		$mail->Body = $cuerpo;
		
		//Definimos AltBody por si el destinatario del correo no admite email con formato html 
		$mail->AltBody = "Por favor utilize un lector de correo que soporte HTML";
		$exito = @$mail->Send();
		if(!$exito){
			return true;
			//echo "<pre>Problemas enviando correo electr&oacutenico a ".$usuario."<br />";
			//$mail->ErrorInfo ."</pre>";	
		}else{
			return false;
		} 
	}
	
	function borrarArchivos($archivos){
		$path = $_SERVER['DOCUMENT_ROOT'] ."/HelpDesk/uploads/"; //path hacia uploads
		$archivosBorrar = array();
		$archivosBorrar = explode(",", $archivos);
		
		foreach($archivosBorrar as $ab){
			if(file_exists ($path.$ab)){
				if(unlink($path.$ab)){

				}else{
					//echo "error al borrar archivo";
				}
			}else{
				//echo "no existe el archivo";
			}
		}
	}
}
//$objFN = new FuncionesGrales();
//$objFN->getOficinas();
?>
