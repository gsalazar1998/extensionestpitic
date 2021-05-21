<?php
class Passwords{
//Variables necesarias
	var $host = 'dbmsql.transportespitic.com';
	var $mysqlUser = 'adminusertpitic';
	var $mysqlPass = 'adminusertpitic';
	var $db = 'globaldb';
	
	/**** Conexion a LDAP ***/
	var $hostLDAP = 'ldap.tpitic.com.mx';
	var $hostLDAP_backup = 'ldapbackup.tpitic.com.mx';
	var $userLDAP = 'cn=feria,dc=transportespitic,dc=com';
	var $passLDAP = 'sistemaspitic';
	
	//funcion para traer los datos del usuario del ldap
	/*function getInfoLDAP($user){
		$con = ldap_connect($this->hostLDAP) or die ("Error al conectar a LDAP<br>");
		echo ldap_error($con);
        if ($con) {
            $bind=ldap_bind( $con,$this->userLDAP, $this->passLDAP )or die("No se hizo el bind.");
			$search=ldap_search($con, "dc=transportespitic,dc=com","uid=".$user);
            $info = ldap_get_entries($con, $search);
			
			return $info;
		}
	}*/
	function connectLDAP(){
		$con = ldap_connect($this->hostLDAP) or die ("Error al conectar a LDAP<br>");
		if(!$con){
			$con = ldap_connect($this->hostLDAP_backup) or die ("Error al conectar a LDAP<br>");
		}
		
		if ($con) {
            $bind=ldap_bind( $con,$this->userLDAP, $this->passLDAP )or die("No se hizo el bind.");
			
			return $con;
		}
	}
	
	function getInfoMySQL($user, $link){
		$query = "SELECT * FROM only_users WHERE username = '".$user."'";
		$rs = mysql_query($query);
		$info = mysql_fetch_array($rs);

		return($info);
	}
	
	//funcion para conectarnos a la base de datos
	function connectWorkDb(){
		$link = mysql_connect($this->host,$this->mysqlUser, $this->mysqlPass)or die(mysql_error());
		mysql_select_db($this->db,$link)or die (mysql_error());
		return $link;
	}
	
	//funcion para obtener el password actual del usuario de la tabla globaldb.users_only
	function getCurrentPass($link, $user){
		$query = "SELECT password FROM only_users WHERE username = '".$user."'";
		$rs = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		if($row == null){
			echo "<script>alert('No hay password en la bd del mambo para ese usuario, ¿existe?');window.history.go(-1)</script>";
		}else{
			return $row['password'];
		}
		mysql_close($link);
	}
	
	//funcion para salvar el nuevo password
	function saveNewPass($link, $pass, $user, $pAnterior){
	//buscamos al usuario
		$rs = mysql_query("SELECT usuario FROM passwords WHERE usuario='".$user."'");
		$encontrado = mysql_num_rows($rs);
		//si es 0 es qe no se encontro entonces lo insertamos en la tabla de passwords
		//para llevar un control de sus passwords anteriores
		if($encontrado == 0){
			$rs2 = mysql_query("INSERT INTO passwords(usuario, antpass1, antpass2, fecha_expiracion)
			VALUES('".$user."','".$pAnterior."',NULL,CURDATE())");
		}
		
		//Aqui es donde guardamos el password en la tabla only_users, sera el password nuevo
		$query = "UPDATE only_users SET password = '".$pass."' WHERE username = '".$user."'";
		$rs = mysql_query($query) or die (mysql_error());
		if($rs){
			echo "<script>alert('Se ha cambiado el password correctamente')</script>";
			echo "window.location.href='../../index.php'";
			//Aqui se redireccionara al usuario a donde sea necesario
		}
		
		//aqui el 1 password mas viejo  pasa a ser el 2 password mas viejo, y el password que se acaba de cambiar
		// pasa a ser el 1 mas viejo, cuando el usuario cambie de nuevo el password el password actual sera el primero mas viejo
		// y el primero mas viejo sera el segundo mas viejo y asi sucesivamente
		$query2 = "UPDATE passwords SET 
		antpass2 = antpass1,
		antpass1 = '".$pAnterior."',
		fecha_expiracion = ADDDATE(CURDATE(),62)  WHERE usuario = '".$user."'";
		$rs2 = mysql_query($query2);
		if($rs2){
			//echo "se almacenaron los ultimos dos passwords";
		}
		mysql_close($link);
	}
	
	//funcion que comprueba los dias que faltan para que expire el password
	function getExpireDaysOf($user, $link){
		if($link){
			$query = "SELECT DATEDIFF(fecha_expiracion,CURDATE())AS expira_en_dias FROM passwords WHERE usuario = '".$user."'";
			$rs = mysql_query($query);
			$row = mysql_fetch_array($rs);
			$expiraen = $row['expira_en_dias'];
			
			return $expiraen;
		}
		mysql_close($link);
	}
	
	//funcion de validacion
	function checkValidPassword($link, $info, $pass, $pass2, $user, $passAnterior, $se, $pTFActual){
		$lenPass = strlen($se);
		if($pass == $pass2){
			if($passAnterior == $pTFActual){
				if($link and ($info != null)){
					if($lenPass >= 8){
						if($pass != md5($user)){
						//convierte el password en un arreglo para hacer la comparacion de secuencias
							$arraypass = preg_split('//', $se, -1, PREG_SPLIT_NO_EMPTY);
							$secuencia = 0;
							$i = 0;
							//en el siguiente ciclo se compara que no haya secuencias de numeros dentro del arreglo
							while($secuencia <= 2 and $i<count($arraypass)-1){
								if(is_numeric($arraypass[$i])){
									if(is_numeric($arraypass[$i+1])){
										if($arraypass[$i]+1 == $arraypass[$i+1]){
											$secuencia++;
										}
									}
								}else{
									$secuencia = 0;
								}
								$i++;
							}
							if($secuencia <= 2){
								$arrayNombre = explode(" ",$info['name']);
								if(($se == strtolower($arrayNombre[0])) or ($se == $arrayNombre[0])){
									echo "<script>alert('No puedes poner tu nombre como password');window.history.go(-1)</script>";
								}else{
									if(($se == strtolower($arrayNombre[1])) or ($se == $arrayNombre[1])){
										echo "<script>alert('No puedes poner tu apellido como password'); window.history.go(-1)</script>";
									}else{
										mysql_select_db($this->db, $link)or die(mysql_error());
										$query = "SELECT antpass1, antpass2 FROM passwords WHERE usuario = '".$user."'";
										$res = mysql_query($query,$link) or die (mysql_error());
										$row = mysql_fetch_array($res);
										if(($pass == $row['antpass1']) or ($pass == $row['antpass2']) or ($pass == $passAnterior)){
											echo "<script>alert('Ya utilizaste ese password, introduce otro');window.history.go(-1)</script>";
										}else{
											return true;
										}
									}
								}
							}else{
								echo "<script>alert('no puedes poner secuencias de numeros mayores de 3 digitos como por ejemplo 1234');window.history.go(-1)</script>";
							}
						}else{
							echo "<script>alert('El password no puede ser su nombre de usuario');window.history.go(-1)</script>";
						}
					}else{
						echo "<script>alert('El password debe ser de minimo 8 digitos');window.history.go(-1)</script>";
					}
				}else{echo "<script>alert('Alguna de las conexiones a ldap o mysql esta fallando');window.history.go(-1)</script>";}
			}else{echo "<script>alert('El password actual es incorrecto');window.history.go(-1)</script>";}
		}else{
			echo "<script>alert('Los passwords no concuerdan');window.history.go(-1)</script>";
		}
		
		mysql_close($link);
	}
	
	function throwPrompts($dias_expira){
		//en caso de que ya se haya vencido para que se vensa
		if($dias_expira < 1){
			//generamos el javascript para preguntarle si lo quiere cambiar
			echo "
				<center><div id='messagecontainer' name='messagecontainer' style = 'border: 2px solid black;width: 700; background:#FFA07A; height: 20;'>
					¡SU PASSWORD HA EXPIRADO, TIENE QUE CAMBIARLO!
				</div></center><br>
				<script type='text/javascript' src='jquery.js'></script>
				<script>
					$(document).ready(function(){
						$('#test').load('formpassnew.html');
					})
				</script>";
		}
	}
	
	function HashPassword($password){
	  $userpassword = "{SHA}".base64_encode(sha1($password, TRUE));
	  return $userpassword;
	}
	
	function changePassLDAP($user,$pass,$con,$obj){
	   if ($con) {
		    $r2=ldap_bind( $con,"cn=feria,dc=transportespitic,dc=com", "sistemaspitic" )or die("Can't bind to server.");
		    $sr2=ldap_search($con, "dc=transportespitic,dc=com","uid=".$user);
		    $info = ldap_get_entries($con, $sr2);
									
			$tpass = trim($pass);
			
			$cryptedpassword = $obj->HashPassword($pass);
				
			$srchSamba=ldap_search($con, "dc=transportespitic,dc=com","(&(objectClass=sambaSamAccount)(uid=".$user."))");
			$tieneSamba = ldap_count_entries($con, $srchSamba);
			if($tieneSamba == 1){
				$updatepass['userPassword']=$cryptedpassword;
				$updatepass['sambaNTPassword']=strtoupper(hash('md4', iconv('UTF-8','UTF-16LE',$pass)));
			}else{
				$updatepass['userPassword']=$cryptedpassword;
			}
			
			$userldap="uid=".$user.",ou=People,dc=transportespitic,dc=com"; 
			$mod=ldap_modify($con, $userldap, $updatepass);
			
			if($mod){
				echo "<script>alert('Password cambiado con exito'); window.location.href='../../index.php'</script>";
				ldap_close($con);
			}
			else{
				echo "<script>alert('Hubo errores, no se cambio el password en ldap');window.history.go(-1)</script>";
				ldap_close($con);
			}	
		}else{
			echo "<script>alert('No hay conexion al LDAP');window.history.go(-1)</script>";
		}
	}
}
?>
