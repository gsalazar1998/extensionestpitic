<style>
table {
  border-collapse: collapse;
  border: 1px solid #666666;
  font: normal 11px verdana, arial, helvetica, sans-serif;
  color: #363636;
  background: #f6f6f6;
  text-align:left;
  }
caption {
  text-align: center;
  font: bold 16px arial, helvetica, sans-serif;
  background: transparent;
  padding:6px 4px 8px 0px;
  color: #CC00FF;
  text-transform: uppercase;
}
thead, tfoot {
background:url(http://www.netway-media.com/freedesigns/table/bg1.png) repeat-x;
text-align:left;
height:30px;
}
thead th, tfoot th {
padding:5px;
}
table a {
color: #333333;
text-decoration:none;
}
table a:hover {
text-decoration:underline;
}
tr.odd {
background: #f1f1f1;
}
tbody th, tbody td {
padding:5px;
}
</style>
<?php
$mysqli = new mysqli("localhost", "adminusertpitic", "adminusertpitic", "firewall");
$query = "SELECT usuario, descripcion, afectado, fecha, hora, oficina FROM log WHERE tipo = 'cambios' AND (descripcion LIKE '%nivel acceso%' OR descripcion LIKE '%lanip%' ) AND fecha BETWEEN ADDDATE(NOW(), -7) AND NOW()";
$rs = $mysqli->query($query);


$tablalog = "<table>";
$tablalog .= "<thead><th>USUARIO</th><th>DESCRIPCION</th><th>USUARIO AFECTADO</th><th>FECHA</th><th>HORA</th><th>OFICINA</th></thead>";
while($row = $rs->fetch_object()){
	$tablalog .= "<tr><td>".$row->usuario."</td><td>".$row->descripcion."</td><td>".$row->afectado."</td><td>".$row->fecha."</td><td>".$row->hora."</td><td>".$row->oficina."</td></tr>";
}

echo $tablalog .= "</table>";
?>