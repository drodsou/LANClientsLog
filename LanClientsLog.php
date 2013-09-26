#!/usr/bin/php
<?php

/*
----------------------------------------------------------
Proceso recurrente ejecutado por cron diariamente 
que hace una ronda de pings a los pcs de la lan Edu Pontevedra
y que inserta los datos en la tabla "usuarios.equipos_ping_log"
----------------------------------------------------------
*/

$dbConn = mysql_connect('127.0.0.1', 'DBUSER', 'DBPASSWORD');
if (!$dbConn) {
		die('ERROR: No pudo conectar a MySql: ' . mysql_error());
}
//echo 'Conectado satisfactoriamente';


$resultado = shell_exec('nmap -sP 10.55.8.11-254');
//$resultado = shell_exec('nmap -sP 10.55.8.102');
$lineas = explode(PHP_EOL, $resultado);


/* la salida del comando es algo asi:
	Starting Nmap 5.00 ( http://nmap.org ) at 2013-08-13 09:34 CEST
	Host ED1PO8_55.xunta.es (10.55.8.55) is up (0.00049s latency).
	MAC Address: 00:18:8B:29:F7:5B (Dell)
	Host 10.55.8.56 is up (0.00057s latency).
	MAC Address: 00:18:8B:26:4C:3B (Dell)
	Nmap done: 2 IP addresses (2 hosts up) scanned in 0.44 seconds
*/


// procesar salida de nmap
$datos = array();
foreach($lineas as $linea) {
	$palabras = explode(' ',$linea);


	if ($palabras[0] == 'Host') {
		// primera linea de un ping
		if (preg_match("/^10/", $palabras[1])) {      // empieza por 10
			// sin nombre de host, solo ip
			$datos['host'] = '';
			$datos['ip'] = $palabras[1];
		}
		else {
			// nombre de host identificado
			$datos['host'] = $palabras[1];
			$datos['ip'] = substr($palabras[2], 1, -1);
		}
	}
	elseif ($palabras[0] == 'MAC') {
		// segunda linea de un ping (ultima)
		$datos['mac'] = $palabras[2];
    
    $datos['marca'] = ''; //puede ir en mas de una columna
    for ($n=3; $n<count($palabras); $n++) { 
  		$datos['marca'] .= $palabras[$n]. ' ';
    }
		$datos['marca'] = substr($datos['marca'], 1, -2);
		
		// insertar en bd
		
		//print_r($datos."\n\n");
		$qry = "INSERT INTO usuarios.equipos_ping_log (ip, host, mac, marca, fecha) VALUES ("
			. "'" . $datos['ip'] . "'"
			. ",'" . $datos['host'] . "'"
			. ",'" . $datos['mac'] . "'"
			. ",'" . $datos['marca'] . "'"
			. ",now() "
			. ")";
		//print_r($qry."\n\n");
		mysql_query($qry, $dbConn);
	}

}


// borrar logs antiguos para q la tabla no crezca demasiado
$qry = "DELETE FROM usuarios.equipos_ping_log WHERE fecha < DATE_SUB( NOW(), INTERVAL 3 MONTH)";
mysql_query($qry, $dbConn);


mysql_close($dbConn);
?>
