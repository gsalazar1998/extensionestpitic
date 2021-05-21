<?php
/*
interface:

public function __construct()

public function connect($host, $base, $user, $password, $enconding = "utf8")
public function query($string)
public function fetchAll($result)
public function queryFetchAll($query)

public function getHost()
public function getBaseName()
public function getUser()

public function getLastId()
public function getQueries()

public function __destruct()

*/

class MySQLException extends Exception { }



class MySQL {

 	var $host = "dbmsql.transportespitic.com"; 
	var $baseName = "SISP";
	var $user = "helpdesk"; 
	var $password = "h31pd35k51573m45";
	var $enconding = "utf8";
	var $id = null;

	private $errorManager = null;	
	
	private $queries = array();



	public function __construct() {

		$this->errorManager = ErrorManager::getInstance();

	} // public function __construct()



	public function getHost() { return $this->host; }
	public function getBaseName() { return $this->baseName; }
	public function getUser() { return $this->user; }
	

	public function connect() {
			$this->id = mysql_connect($this->host, $this->user, $this->password)or die(mysql_error());
			mysql_select_db($this->baseName, $this->id)or die(mysql_error());
			mysql_set_charset($this->enconding, $this->id);
			return $this->id;
	} // public function connect($host, $base, $user, $password)
	




	public function getLastId() {
		$query = "SELECT max(ID_CASO) AS ULTIMO FROM casos";
		$rs = mysql_query($query, $this->id);
		$row = mysql_fetch_array($rs);
		return $row['ULTIMO']+1;
	} // public function getLastId()
	
	public function getLastIdEC() {
		$query = "SELECT max(ID_ACTIVIDAD) AS ULTIMO FROM act_extra_casos";
		$rs = mysql_query($query, $this->id);
		$row = mysql_fetch_array($rs);
		return $row['ULTIMO']+1;
	} // public function getLastId()

	public function getLastIdCasosDesarrollo() {
			$query = "SELECT max(ID_CASODES) AS ULTIMO FROM casosDesarrollo";
			$rs = mysql_query($query, $this->id);
			$row = mysql_fetch_array($rs);
			return $row['ULTIMO']+1;
		} // public function getLastId()





	public function fetchAll($result) {
	
		if (mysql_num_rows($result)) {

			$rows = array();
			while ($row = mysql_fetch_assoc($result)) {
				$rows[] = $row;
			}
			return $rows;

		}
		else {

			$this->errorManager->reportFatalError($this, new MySQLException("invalid mysql query result supplied to fetchAll()"));

		}

	} // public function fetchAll($result)





	
	public function query($string) {

	 	if (is_resource($this->id)) {
			
			if ($result = mysql_query($string, $this->id)) {
				
				$this->queries[] = $string;
				return $result;

			}
			else {
				$this->errorManager->reportFatalError($this, new MySQLException("error in query: '$string':".mysql_error($this->id)));
			}

		}
		else {

			$this->errorManager->reportFatalError($this, new MySQLException("database is not connected, can't make query"));

		}

	} // public function query($string)



	public function queryFetchAll($query) {

		$result = $this->query($query);
		$entries = $this->fetchAll($result);
		return $entries;

	} // public function queryFetchAll($query)





	public function getQueries() {
	
		return $this->queries;

	} // public function getQueries()



	
	public function __destruct() {

		if (is_resource($this->id)) {

			mysql_close($this->id);
		
		}

	} // public function __destruct()



} // class Mysql

?>
