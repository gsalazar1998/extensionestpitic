<?php
ini_set('display_errors',1);
ini_set("error_reporting", E_ALL);

class Email_reader {
 
	// imap server connection
	public $conn;
 
	// inbox storage and inbox message count
	private $inbox;
	private $msg_cnt;
 
	// email login credentials
	private $server = '200.34.32.158';
	private $user   = 'helpdesk@tpitic.com.mx';
	private $pass   = 'wj6n0twsp';
	private $port   = 143; // adjust according to server settings
 
	// connect to the server and get the inbox emails
	function __construct() {
		$this->connect();
		$this->inbox();
	}
 
	// close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;
 
		imap_close($this->conn);
	}
 
	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	function connect() {
		//$this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
		$this->conn = imap_open('{'.$this->server.'/novalidate-cert}', $this->user, $this->pass);
	}
 
	// move the message to a new folder
	function move($msg_index, $folder='INBOX.Processed') {
		// move on server
		imap_mail_move($this->conn, $msg_index, $folder);
		imap_expunge($this->conn);
 
		// re-read the inbox
		$this->inbox();
	}
 
	// get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index=NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}
 
		return $this->inbox[0];
	}
 
	// read the inbox
	function inbox() {
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => imap_body($this->conn, $i),
				'structure' => imap_fetchstructure($this->conn, $i),
				'body_struct' => imap_bodystruct($this->conn, $i, "1"),
				'fetch_body'  => imap_fetchbody($this->conn, $i, "1"),
				'fetch_body1'  => imap_fetchbody($this->conn, $i, "1.1"),
			);
		}
		
		$this->inbox = $in;
	}
	
	function getNumMsg(){
		return $this->msg_cnt = imap_num_msg($this->conn);
	}
	
	function getSender($msgnum){
		$header = imap_header($this->conn, ($msgnum+1));
		
		return $header->from[0]->mailbox;
	}
}

date_default_timezone_set('America/Hermosillo');
$mailreader = new Email_reader();
$num_msgs = $mailreader->getNumMsg();
for($i=0; $i<$num_msgs; $i++){
	$mensaje = $mailreader->get($i);
	//$from = $mailreader->getSender($i);
	$structure = $mensaje['body_struct']->encoding;
	$subject = $mensaje['header']->subject;
	//sacaremos el numero del caso
	preg_match('/[0-9]{4,9}/',$subject, $coincidencias);
	$num_caso = $coincidencias[0];
	//obtendremos el usuario que envio el correo
	$user = $mailreader->getSender($i);
	
	
	/*print("<pre>");
	print_r($mensaje);
	print("</pre>");*/

	$mensaje['body_struct']->ifsubtype;
	if(isset($mensaje['body_struct']->ifsubtype)){
		if($mensaje['body_struct']->subtype=='ALTERNATIVE'){
			$text = $mensaje['fetch_body1'];
		}else{
			$text = $mensaje['fetch_body'];
		}
	}else{
		$text = $mensaje['fetch_body'];
	}
	
	if($structure == 3) {
		print("<pre>");
		$text = utf8_encode(imap_base64($text));
		if(strpos($text, '-----Mensaje original-----')){
			$quitar = substr($text,(strpos($text, '-----Mensaje original-----')));
			$text = str_replace($quitar, '', $text);
		}
		echo $query = "INSERT INTO casos_respuestas VALUES ('".$num_caso."', '".$user."', '".addslashes($text)."', '".date('Y-m-d')."')";
		echo "<br /><br /><br />";
		print("</pre>");
	}elseif($structure == 4) {
		print("<pre>");
		$text = utf8_encode(imap_qprint($text));
		if(strpos($text, '-----Mensaje original-----')){
			$quitar = substr($text,(strpos($text, '-----Mensaje original-----')));
			$text = str_replace($quitar, '', $text);
		}
		echo $query = "INSERT INTO casos_respuestas VALUES ('".$num_caso."', '".$user."', '".addslashes($text)."', '".date('Y-m-d')."')";
		echo "<br /><br /><br />";
		print("</pre>");
	}else{
		print("<pre>");
		$text = utf8_encode($text);
		if(strpos($text, '-----Mensaje original-----')){
			$quitar = substr($text,(strpos($text, '-----Mensaje original-----')));
			$text = str_replace($quitar, '', $text);
		}
		echo $query = "INSERT INTO casos_respuestas VALUES ('".$num_caso."', '".$user."', '".addslashes($text)."', '".date('Y-m-d')."')";
		echo "<br />	<br /><br />";
		print("</pre>");
	}
}

/*print("<pre>");
print_r($mensaje);
print("</pre>");*/
?>