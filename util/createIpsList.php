#!/usr/bin/php
<?php

$dbConn = mysql_connect('127.0.0.1', 'DBUSER', 'DBPASSWORD');
if (!$dbConn) {
		die('ERROR: No pudo conectar a MySql: ' . mysql_error());
}
//echo 'Conectado satisfactoriamente';


for ($ip=20; $ip<255; $ip++) {
		$qry = "INSERT INTO usuarios.equipos_todas_ips (ip) VALUES ("
			. "'10.55.8.{$ip}'"
			. ")";
		mysql_query($qry, $dbConn);
    //echo $qry."\n";
}



mysql_close($dbConn);
?>
