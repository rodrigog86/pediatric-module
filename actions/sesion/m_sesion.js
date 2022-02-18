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
		isValid();
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

	$("#btn_create_user").click(() => {
		addUser();
	});
	getUsers();
});

const isValid = () => {
	const usuario = $('#usuario').val();
	const contrasena = $('#contrasena').val();
	if(contrasena === '') {
		print_alert("Faltó teclear la contraseña");
		$('#contrasena').focus();
		return false;
	}

	const data = JSON.stringify({"accion" : "isValid", "usuario": usuario, "contrasena" : String(CryptoJS.SHA512(contrasena))});
	callServerJQ('POST', apiURL_sesion, data, (response) => {
		if(response.jsonResponse.server_response.error !== '00') {
			print_alert(response.jsonResponse.server_response.message);
			return false;
		}
		window.location='paciente.php';
	});
}

const getUsers = () => {
	const data = JSON.stringify({"accion" : "getUsers"});
	callServerJQ('POST', apiURL_sesion, data, (response) => {
		$("#usuario").empty();
		if(response.jsonResponse.server_response.error !== '00') {
			print_alert(response.jsonResponse.server_response.message);
			return false;
		}
		else if(response.jsonResponse.data.length < 1) {
			$("#modal_add_user").modal({backdrop: 'static', keyboard: false, show: true}).on('shown.bs.modal', () => {
				$('#add_name').focus();
			});
			$("#usuario").append(`<option>No existen usuarios registrados</option>`);
			return false;
		}
		response.jsonResponse.data.map(elemento => {
			$("#usuario").append(`<option value='${elemento.cve_usuario}'>${elemento.nombre}</option>`);
		});
	});	
}

const addUser = () => {
	const cve_usuario = $("#add_user").val();
	const contrasena = $("#pwd_user").val();
	const confirma_contrasena = $("#confirm_pwd").val();
	const nombre = $("#add_name").val();

	let msgerr='';
	let numerr=0;
	
	if(!verifyValue(cve_usuario, 5, 12)) {
		numerr++;
		msgerr+='El usuario tecleado no es válido.<br>';
	}
	
	if(!verifyValue(contrasena, 8, 16) || contrasena !== confirma_contrasena) {
		numerr++;
		msgerr+='La contraseña tecleada no es válida o éstas no son identicas.<br>';
	}

	if(!verifyValue(nombre, 5, 90)) {
		numerr++;
		msgerr+='Se debe teclear un nombre.<br>';
	}

	if(numerr>0) {
		print_alert(msgerr);
		return false;
	}
	
	const data = JSON.stringify({"accion": "addUser", "cve_usuario":cve_usuario, "contrasena":String(CryptoJS.SHA512(contrasena)), "nombre":nombre});
	callServerJQ('POST', apiURL_sesion, data, (response) => {
		if(response.jsonResponse.server_response.error !== '00') {
			print_alert(response.jsonResponse.server_response.message);
			return false;
		}
		window.location.reload();
	});		
}
