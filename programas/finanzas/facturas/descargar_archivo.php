<?phpheader("Pragma: public");header("Expires: 0");header( "Content-type: text/xml" ); header( "Content-Disposition: attachment; filename=\"".$_GET['nombre']."\"\r\n");header("Content-Description: File Transfer");$link = mysql_connect("dbmsql.transportespitic.com","adminusertpitic","adminusertpitic");mysql_select_db("facturas",$link);$qry = "SELECT xml FROM facturas WHERE ID_REGISTRO=".$_GET['id'];$res = mysql_query($qry);$contenido = mysql_result($res, 0);header("Content-type: text/xml");echo $contenido;?>