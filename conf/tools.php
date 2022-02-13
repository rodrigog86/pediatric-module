<?php
/*
 * Desarrollador: Rodrigo García
 * Empresa: *
 * Nombre: tools.php
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |-------------------------------------------------------------------------------------------------------------------------|
 */

// -- Método que escribe en un archivo los mensajes recibidos de cada uno de los scripts.
function debugger($mensaje) {
	try {
		$config = require("properties.php");

		// -- Se abre el archivo del log para escribir en él.
		$log = fopen($FOLDER=str_replace("conf", "logs", str_replace("tools.php", "", __FILE__)).'SystemErr.log', 'a');
		// -- Se valida qué sistema operativo ejecuta el script, para colocar el salto de línea adecuado.
		$eol="\n";
		if(strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif(strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		}
		// -- Se calcula la hora con un timestamp para obtener los milisegundos a imprimir dentro del log.
		$time = microtime(true);
		$micro = sprintf("%06d",($time - floor($time)) * 1000000);
		$nueva = new DateTime(date('Y-m-d H:i:s.'.$micro, $time));
		if($config["write_debugger"] == true) {
			fwrite($log, '['.$nueva->format("d/m/Y H:i:s.u").'] :: '.$mensaje.''.$eol);
		}
		fclose($log);
	}// -- En caso de error se imprime en pantalla el mensaje generado.
	catch(Exception $e) {
		echo $e->getMessage();
	}
}

function utf8ize($dato) {
	// -- Se valida qué sistema operativo ejecuta el script, para codificar los caracteres especiales en pantalla.
	if(strtoupper(substr(PHP_OS,0,3)!='WIN')) {
		try {
			if (is_array($dato)) {
				foreach ($dato as $clave => $valor) {
					$dato[$clave] = utf8ize($valor);
				}
			} else if (is_string ($dato)) {
				return utf8_encode_($dato);
			}
		}
		// -- En caso de error se imprimie dentro del log el mensaje generado.
		catch(Exception $e) {
			debugger($e->getMessage());
		}		
	}
	// -- Al finalizar se regresa el valor.
    return $dato;
}

function utf8_decode_($dato) {
	// -- Se valida qué sistema operativo ejecuta el script, para decodificar los caracteres especiales en pantalla.
	if(strtoupper(substr(PHP_OS,0,3)!='WIN')) {
		return utf8_decode($dato);
	}
	return $dato; // -- En caso contrario se regresa el dato sin cambios.
}

// -- Se codifica los caracteres a mostrar en pantalla.
function utf8_encode_($dato) {
	// -- Se valida qué sistema operativo ejecuta el script, para codificar los caracteres especiales en pantalla.
	if(strtoupper(substr(PHP_OS,0,3)!='WIN')) {
		return utf8_encode($dato);
	}
	return $dato; // -- En caso contrario se regresa el dato sin cambios.
}

function ObtenerIP() {
   $ip = "";
   if(isset($_SERVER)) {
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
   }
   else {
		if (getenv( 'HTTP_CLIENT_IP' )) {
			$ip = getenv( 'HTTP_CLIENT_IP' );
		}
		elseif( getenv( 'HTTP_X_FORWARDED_FOR' )) {
			$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
		}
		else {
			$ip = getenv( 'REMOTE_ADDR' );
		}
   }  
	// En algunos casos muy raros la ip es devuelta repetida dos veces separada por coma 
   if(strstr($ip,',')) {
		$ip = array_shift(explode(',',$ip));
   }
   return $ip;
}

// -- Sólo funciona para versiones PHP 5.
function encrypt($valor){	
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($config["llave"]), $valor, MCRYPT_MODE_CBC, md5(md5($config["llave"]))));
}

// -- Sólo funciona para versiones PHP 5.
function decrypt($valor){
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($config["llave"]), base64_decode($valor), MCRYPT_MODE_CBC, md5(md5($config["llave"]))), "\0");
}

function obtener_ultimo_dia($mes, $anio) {
	switch($mes) {
		case 1 || 3 || 5 || 7 || 8 || 10 || 12:
			return 31;
			break;
		case 2:
			return (($anio%4 == 0 && $anio%100 !=0) || $anio%400==0) ? 29 : 28;
			break;
		default:
			return 30;
	}
}

function obtener_nombre_mes($mes) {
	$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
	return $meses[--$mes];
}

function obtener_fecha($mes, $anio) {
	$anio_actual = (int) date("Y");
	$anio_anterior = (int) date("Y");
	$mes_actual = (int)date("m");
	$mes_anterior = (int)($mes_actual - 1);
	if(($mes_anterior-1) < 1) {
		$mes_anterior = 12;
		$anio_anterior -= 1;
	}	
}

?>

