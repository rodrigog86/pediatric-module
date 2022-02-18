<?php
/*
 * Desarrollador: Rodrigo García
 * Empresa: *
 * Nombre: m_sesion.php
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |-------------------------------------------------------------------------------------------------------------------------|
 */

// -- Se continúa con la sesión activa.
session_start();

// -- Se establece el timezone. 
date_default_timezone_set('America/Mexico_City');
// -- Se indica que el resultado generado por el presente será en formato JSON.
header("Content-Type: application/json; charset=UTF-8");
// -- Se importan funcionalidades de los siguientes archivos.
include_once "../../conf/tools.php";
include_once "../../conf/connection.php";
//include_once "../../conf/lib/PHPMailer/PHPMailer/PHPMailer.php";
//include_once "../../conf/lib/PHPMailer/PHPMailer/Exception.php";

// -- Se obtiene la acción que el usuario desea ejecutar.
$request = json_decode($_POST["json_request"], false);

switch($request->accion) {
	case "isValid":
		isValid($request);
		break;
	case "addUser":
		addUser($request);
		break;
	case "getUsers":
		getUsers();
		break;
	case "closeSession":
		@session_destroy();
		echo json_encode(array("jsonResponse" => array("server_response" => array("error" => "00", "message" => "OK"))));
		break;
	default:
		echo json_encode(array("jsonResponse" => array("server_response" => array("error" => "99", "message" => utf8_encode_("Opción incorrecta")))));
		break;
}

function addUser($request) {
	$SQL = new SQL();
	$parametros = array(":cve_usuario" => str_replace(' ', '', trim($request->cve_usuario)),
						":contrasena" => password_hash($request->contrasena, PASSWORD_BCRYPT, array("cost" => 5)),
						":nombre" => trim($request->nombre));
	$resultado = $SQL->modificaBD_SP("CALL `".$SQL->obtenerDB()."`.`sp_administrar_usuario`(:cve_usuario, :contrasena, :nombre) ", $parametros);
	$resultado=json_decode($resultado);
	echo json_encode($resultado);
}

function getUsers() {
	$SQL = new SQL();
	$resultado=$SQL->consultaBD("SELECT cve_usuario, nombre FROM `".$SQL->obtenerDB()."`.`usuario`", array());
	$resultado=json_decode($resultado);
	debugger(json_encode($resultado));
	echo json_encode($resultado);
}

// -- Función que se encarga de validar el usuario y contraseña dentro de la base de datos.
function isValid($request) {
	$SQL = new SQL();
	$parametros = array(":cve_usuario" => str_replace(' ', '', trim($request->usuario)));
	$resultado = $SQL->consultaBD("SELECT contrasena, nombre FROM `".$SQL->obtenerDB()."`.`usuario` WHERE cve_usuario=:cve_usuario", $parametros);
	$resultado = json_decode($resultado);
	$error = "00";
	$message = "OK";
	try {
		if(count($resultado->jsonResponse->data)>0) {
			//$hash_ingresado = password_hash(str_replace(' ', '', trim($request->contrasena)), PASSWORD_BCRYPT, array("cost" => 5));
			$hash_ingresado = $request->contrasena;
			if(password_verify($hash_ingresado, $resultado->jsonResponse->data[0]->contrasena)) {
				$session_vars = array('usuario_conectado' => $parametros[":cve_usuario"], 'nombre_empleado' => $resultado->jsonResponse->data[0]->nombre, 'tiempo_inactividad' => 90000000);
				foreach($session_vars as $key => $value) {
					if (isset($_SESSION[$key])) {
						unset($_SESSION[$key]);
					}
					$_SESSION[$key] = $value;
				}
				$SQL->modificaBD("UPDATE `".$SQL->obtenerDB()."`.`usuario` SET fecha_hora_acceso=now() WHERE cve_usuario=:cve_usuario", $parametros);				
			}
			else {
				$error="01";
				$message=utf8_encode_("Los datos introducidos no corresponden con un usuario registrado.");									
			}
		}
		else { 
			$error="01";
			$message=utf8_encode_("Los datos introducidos no corresponden con un usuario registrado.");		
		}
	}
	catch(Exception $e) {
		$error="01";
		$message="Se presentó un error al consultar la información de la base de datos.";
	}

	$resultado->jsonResponse->server_response->error=$error;
	$resultado->jsonResponse->server_response->message=$message;
	echo json_encode($resultado);
}

?>