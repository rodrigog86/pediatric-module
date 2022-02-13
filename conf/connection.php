<?php
/*
 * Desarrollador: Rodrigo García 
 * Empresa: *
 * Nombre: connection.php
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
  * |-------------------------------------------------------------------------------------------------------------------------|
 *
 */

include_once "tools.php"; 

class SQL {
	// -- Declaración de variables que ocupará la clase SQL.
	private $opciones;
	protected $conexion;
	private $cve_conexion;
	
	private $host;
	private $data;
	private $user;
	private $pass;
	
	// -- Constructor de la clase SQL.
	public function __construct() {
		$this->opciones = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
		$this->cve_conexion=0;
		$this->conexion=null;
		
		$config = require("db-properties.php"); 
		$this->host=$config["name_server"];
		$this->data=$config["database_name"];
		$this->user=$config["username_db"];
		$this->pass=$config["password_db"];
	}
	
	// -- Método que se conecta el sitio con la base de datos.
	public function conectaBD($archivo) {
		try {
			// -- Se crea el objeto de conexión con la base de datos.		
			$this->conexion = new PDO('mysql:host='.$this->host.';dbname='.$this->data, $this->user, $this->pass, $this->opciones);
			// -- Se obtiene el id de la conexión.
			$this->cve_conexion = $this->conexion->query("SELECT connection_id() id")->fetch(PDO::FETCH_ASSOC)['id'];
			//debugger("Conexión [id => ".$this->cve_conexion."] abierta [file => ".$archivo."]");
			return $this->conexion;
		}
		// -- En caso de error se agrega al log el mensaje generado.
		catch(PDOException $e) {
			debugger("\n******************************************\nException____".$e->getMessage()."\n******************************************");
		}
	}
	
	// -- Método que desconecta el sitio de la base de datos.
	public function desconectaBD($archivo) {
		try {
			// -- Se valida que la conexión se encuentre activa.
			if($this->conexion!=null) {
				// -- Se cierra la conexión con la base de datos.
				$this->conexion = null;
				//debugger("Conexión [id => ".$this->cve_conexion."] liberada [file => ".$archivo."]");
			}			
		}
		// -- En caso de error se agrega al log el mensaje generado.
		catch(PDOException $e) {
			debugger("\n******************************************\nException____".$e->getMessage()."\n******************************************");
		}
	}
	
	public function consultaBD($Query,$parametros) {
		$error="00";
		$message="OK";
		$root = array();
		$element = array();
		$conexion = $this->conectaBD(basename(__FILE__));
		if($conexion!=null) {
			try {
				$DATASET01=$conexion->prepare($Query);
				$DATASET01->execute($parametros);
				if($DATASET01->rowCount()>0) {
					foreach ($DATASET01 as $row) {
						for ($columna = 0; $columna < $DATASET01->columnCount(); $columna ++) {
							$etiqueta=$DATASET01->getColumnMeta($columna)['name'];
							$element[$etiqueta] = $row[$etiqueta];
						}
						array_push($root, $element);
					}
				}
				else {
					$error="00";
					$message="No se encontraron registros";
				}
			}
			catch(Exception $e) {
				$error="01";
				$message="Se presentó un error al consultar la información de la base de datos.";
				debugger($e);
			}
			$DATASET01->closeCursor();
			$this->desconectaBD(basename(__FILE__));
		}
		else {
			$error="02";
			$message="No existe conexión con el servidor de base de datos";
		}
		return json_encode(array("jsonResponse" => array("server_response" => array("error" => $error, "message" => $message), "data" => utf8ize($root))));	
	}
	
	public function modificaBD($Query, $parametros) {
		$id = 0;
		$root = array();
		$element = array();
		$conexion = $this->conectaBD(basename(__FILE__));
		if($conexion!=null) {
			try {
				$conexion->beginTransaction();
				$DATASET01=$conexion->prepare($Query);
				$DATASET01->execute($parametros);
				if((int)$conexion->errorCode()<=0) {
					$id = $conexion->lastInsertId();
					$error="00";
					$message="Se actualizó la base de datos.";
				}
				else {
					$conexion->rollBack();
					$error="01";
					$message="Error en la consulta.";
				}
				$conexion->commit();
			}
			catch(Exception $e) {
				$error="01";
				$message="Se presentó un error al consultar la información de la base de datos.".$e->getMessage();
			}
			$this->desconectaBD(basename(__FILE__));
		}
		else {
			$error="02";
			$message="No existe conexión con el servidor de base de datos";
		}
		return json_encode(array("jsonResponse" => array("server_response" => array("error" => $error, "message" => $message, "id" => $id))));
	}
	
	public function modificaBD_SP($Query, $parametros) {
		$id = 0;
		$root = array();
		$element = array();
		$conexion = $this->conectaBD(basename(__FILE__));
		if($conexion!=null) {
			try {
				//$conexion->beginTransaction();
				$DATASET01=$conexion->prepare($Query);
				$DATASET01->execute($parametros);
				$resultado = $DATASET01->fetch(PDO::FETCH_ASSOC);		
				if(substr(trim($resultado["message"]),0 , 2)!="OK") {
					//$conexion->rollBack();
					$error="01";
					$message=substr(trim($resultado["message"]), 3, strlen(trim($resultado["message"])));
				}
				else {
					$id = substr(trim($resultado["message"]), 3, strlen(trim($resultado["message"])));
					//$id = $conexion->lastInsertId(); //---
					$error="00";
					$message="Se actualizó la base de datos.";						
				}
				//$conexion->commit();
			}
			catch(Exception $e) {
				$error="01";
				$message="Se presentó un error al consultar la información de la base de datos.".$e->getMessage();
			}
			$this->desconectaBD(basename(__FILE__));
		}
		else {
			$error="02";
			$message="No existe conexión con el servidor de base de datos";
		}
		return json_encode(array("jsonResponse" => array("server_response" => array("error" => $error, "message" => $message, "id" => $id))));
	}
	
	public function __rollback() {
		if($conexion!=null) {
			$conexion->rollBack();
		}
	}
	
	public function obtenerDB() {
		return $this->data;
	}
	
	public function __destruct() {
		try {
			// -- Se valida que la conexión se encuentre activa.
			if($this->conexion!=null) {
				// -- Se cierra la conexión con la base de datos.
				$this->conexion = null;
				//debugger("Conexión [id => ".$this->cve_conexion."] cerrada [file => ".$archivo."]");
			}			
		}
		// -- En caso de error se agrega al log el mensaje generado.
		catch(PDOException $e) {
			debugger("\n******************************************\nException____".$e->getMessage()."\n******************************************");
		}		
	}
}
?>
