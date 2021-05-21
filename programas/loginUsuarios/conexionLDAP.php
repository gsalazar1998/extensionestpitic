<?php
class Conexion{
	var $server_ldap = "ldap.tpitic.com.mx";
	var $dn = "cn=feria,dc=transportespitic,dc=com";
	var $password = "sistemaspitic";
	
	var $server_mysql = "tidb.tpitic.com.mx";
	var $bd = 'globaldb';
	var $user_mysql = 'adminusertpitic';
	var $pass_mysql = 'adminusertpitic';

	
	function __contruct(){
	
	}
	
	function conectarLDAP(){
		$con = ldap_connect($this->server_ldap)or die("can't connect to server");
		if ($con) {
		   $bind=ldap_bind($con, $this->dn, $this->password)or die("Can't bind to server.");
		   return $con;
		}else{
			return null;
		}
	}
	
	/*para conectar a una base de datos MySQL*/
	function conectarMySQL(){
		$con = mysql_connect($this->server_mysql, $this->user_mysql, $this->pass_mysql);
		if($con){
			return $con;
		}else{
			return null;
		}
	}
} 
?>
