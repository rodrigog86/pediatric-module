<?php
/*
 * Desarrollador: Rodrigo García
 * Empresa: -
 * Nombre: m_paciente.php
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
include_once "../../conf/connection.php";

// -- Se obtiene la acción que el usuario desea ejecutar.
$request = json_decode($_POST["json_request"], false);
switch($request->accion) {
	case "getAllData":
		getAllData($request);
		break;
	case "getOne":
		getOne($request);
		break;
	case "save":
		debugger("Entro");
		save($request);			
		break;
	case "computeAge":
		computeAge($request);
		break;
	default:
		echo json_encode(array("jsonResponse" => array("server_response" => array("error" => "99", "message" => utf8_decode_("Opción incorrecta")))));
		break;
}

function computeAge($request) {
	$SQL = new SQL();
	$parametros = array(":nacimiento" => str_replace(' ', '', trim(strtolower($request->nacimiento))),
						":fecha_actual" => date("Y-m-d"),
						":nacimiento" => str_replace(' ', '', trim(strtolower($request->nacimiento))),
						":fecha_actual" => date("Y-m-d"));
	$resultado = $SQL->consultaBD("SELECT TIMESTAMPDIFF(YEAR, :nacimiento, :fecha_actual) anios, TIMESTAMPDIFF(MONTH, :nacimiento, :fecha_actual) meses", $parametros);
	$resultado = json_decode($resultado);
	echo json_encode(utf8ize($resultado));
}

// -- Función que se encarga de obtener la información del registro seleccionado.
function getOne($request) {
	$SQL = new SQL();
	$parametros = array(":cve_paciente" => str_replace(' ', '', trim(strtolower($request->cve_paciente))));
	$resultado = $SQL->consultaBD("SELECT `cve_paciente`, `nombre`, `edad`, `sexo`, `domicilio`, `telefono`, `correo_electronico`, `historia`, `observaciones`, TIMESTAMPDIFF(YEAR, edad, now()) anios, TIMESTAMPDIFF(MONTH, edad, now()) meses FROM `".$SQL->obtenerDB()."`.`paciente` WHERE cve_paciente=:cve_paciente", $parametros);
	$resultado = json_decode($resultado);
	echo json_encode(utf8ize($resultado));
}

// -- Función que se encarga de obtener la información almacenada dentro de la base de datos.
function getAllData($request) {
	$SQL = new SQL();
	$resultado = $SQL->consultaBD("SELECT cve_paciente, nombre, TIMESTAMPDIFF(YEAR, edad, now()) anios, TIMESTAMPDIFF(MONTH, edad, now()) meses FROM `".$SQL->obtenerDB()."`.`paciente` WHERE `visible`= 'T' ORDER BY nombre", array());
	$resultado = json_decode($resultado);
	echo json_encode($resultado);	
}

// -- Función que se encarga de ejecutar la acción (agregar, modificar y eliminar) seleccionada dentro de la base de datos.
function save($request) {
	$SQL = new SQL();
	$parametros = array(":operacion" => str_replace(' ', '', trim(strtolower($request->operacion))) === "delete" ? "D" : "",
						":cve_paciente" => str_replace(' ', '', trim($request->cve_paciente == "" ? 0 : $request->cve_paciente)) ,
						":nombre" => utf8_decode_(trim($request->nombre)) ,
						":edad" => str_replace(' ', '', trim($request->edad)) == "--" ? date("Y-m-d") : str_replace(' ', '', trim($request->edad)) ,
						":sexo" => str_replace(' ', '', trim($request->sexo)),
						":domicilio" => utf8_decode_(trim($request->domicilio)) ,
						":telefono" => utf8_decode_(trim($request->telefono)) ,
						":correo_electronico" => str_replace(' ', '', trim($request->correo_electronico)) ,
						":historia" => utf8_decode_(trim($request->historia)),
						":historia2" => utf8_decode_(trim($request->historia)),
						":observaciones" => utf8_decode_(trim($request->observaciones)));
	$resultado = $SQL->modificaBD_SP("CALL `".$SQL->obtenerDB()."`.`sp_administrar_paciente`(:operacion, :cve_paciente, :nombre, :edad, :sexo, :domicilio, :telefono, :correo_electronico, :historia, :historia2, :observaciones) ", $parametros);	
	$resultado = json_decode($resultado);
	$resultado->jsonResponse->server_response->actualizado = date("d/m/Y H:i:s");
	echo json_encode($resultado);
}


?>