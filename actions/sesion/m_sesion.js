/*
 * Desarrollador: Rodrigo García
 * Empresa: -
 * Nombre: m_sesion.js
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |-------------------------------------------------------------------------------------------------------------------------|
 */

// -- Método que cargará información y realizará la captura de la acción de cada objeto dentro del módulo. 
$(document).ready(() => {
	$('#usuario').focus();
	// -- Método que captura la acción de clic sobre el botón para entrar al sistema.
	$("#btn_entrar").click(() => {
		validar_datos();
	});	

	$("#usuario").keypress((e) => {
		if(e.keyCode === 13) {
			$("#contrasena").focus();
		}
	});
		
	$("#contrasena").keypress((e) => {
		if(e.keyCode === 13) {
			$("#btn_entrar").click();		
		}
	});
});

const validar_datos = () => {
	const usuario = $('#usuario').val();
	const contrasena = $('#contrasena').val();
	if(contrasena === '') {
		print_alert("Faltó teclear la contraseña");
		$('#contrasena').focus();
		return false;
	}

	const clave = CryptoJS.SHA512(contrasena);
	const data = JSON.stringify({"accion" : "validar_datos", "usuario": usuario, "contrasena" : String(clave)});
	callServerJQ('POST', apiURL_sesion, data, (response) => {
		if(response.jsonResponse.server_response.error !== '00') {
			print_alert(response.jsonResponse.server_response.message);
			return false;
		}
		window.location='paciente.php';
	});
}
