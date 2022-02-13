/*
 * Desarrollador: Rodrigo García 
 * Empresa: -
 * Nombre: ajax.js
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |-------------------------------------------------------------------------------------------------------------------------|
 */

const apiURL_sesion = 'actions/sesion/m_sesion.php';
const apiURL_paciente = 'actions/paciente/m_paciente.php';

const showLoad = (isVisible) => {
	if(isVisible) {

	}
}

// -- Función que se encarga de procesar todas las solicitudes asíncronas con el servidor. (Método anterior)
const callServerOld = (method, Url, param, callBack) => {
	try {
		showLoad(true);
		const xmlHttp = new XMLHttpRequest();

		xmlHttp.onreadystatechange = () => {
			// -- Se recibe la respuesta.
			if (this.readyState == 4 && this.status >= 200 && this.status < 300) {
				showLoad(false);
				// -- Se regresa la respuesta a la función que la ejecutó.
				return callBack(JSON.parse(xhttp.responseText));
			}
			else if(this.readyState === 4 && this.status !== 200 && this.status !== "0") {
				alert("Service unavailable "+xhttp.status);
			}		
		}
		// -- Se abre la conexión con el servidor.
		xmlHttp.open(method, Url, true);
		// -- Se envía el encabezado de la petición al servidor.
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// -- Se envían los parámetros al servidor.
		xmlHttp.send("json_request="+param);
	}
	catch(e) {
		console.log(e);
	}
}

// -- Función que se encarga de procesar todas las solicitudes asíncronas con el servidor. (Método JQuery)
const callServerJQ = (method, Url, param, callBack) => {
    $.ajax({
        data: "json_request="+param,
        url: Url,
        type: method,
        dataType: 'json',
        beforeSend: () => {
            showLoad(true);
        },
        success: (response, textStatus, jqXHR ) => {	
			showLoad(false);
			return callBack(response);
		},
		error: (jqXHR, textStatus, errorThrown) => {
			showLoad(false);
			alert(errorThrown);
		},
		fail: (jqXHR, status, error) => {
			showLoad(false);
			alert("Service unavailable "+error);
		}
    });	
}