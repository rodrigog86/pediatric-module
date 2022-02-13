var t=null;
const contadorInactividad = () => {
	t=setTimeout(() => {
		closeSession(true);
	}, parseInt($("#tiempo_sin_actividad").val()) == '' ? 600000 : parseInt($("#tiempo_sin_actividad").val()));
}

const days_week = Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
const months = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
const current = new Date();

const current_date = () => {
	return `${formatNumber(current.getDate())}/${formatNumber(current.getMonth()+1)}/${current.getFullYear()}`;
}

const formatMySQLDate = (date) => {
	return `${date.substr(6,10)}-${date.substr(3,2)}-${date.substr(0,2)}`;
}

// - Función que imprime la fecha y hora que el usuario tiene en su equipo.
const print_date_time = () => {
    let hours = current.getHours();
    const minutes = current.getMinutes();
    const format = (hours >= 12 ? "p.m." : "a.m.");
    hours = (hours > 12 ? hours-12 : hours);
    return `${days_week[current.getDay()]}, ${formatNumber(current.getDate())} de ${months[current.getMonth()]}, ${current.getFullYear()}  ${formatNumber(hours)}:${formatNumber(minutes)} ${format}`;
}

// -- Función que se encarga de dar formato completo a un número.
const formatNumber = (number) => {
    return (number < 10 ? "0"+number : number);
}

// -- Función que se encarga de solo escribir números en ciertos campos de texto dentro del sitio.
const writeOnlyNumber = (e) => {
	const key = (e.which || e.keyCode);
	const pressKey =String.fromCharCode( key );
	const numbers ="0123456789";
    const allowKeys = [8,35,32,36,37,38,39,46];
	if(numbers.indexOf(pressKey) === -1 || allowKeys.find(element => element === key) !== undefined) {
		return true;
	}
	return false;
}

// -- Función que se encarga de solo escribir letras en ciertos campos de texto dentro del sitio.
const writeOnlyLetter = (e) => {
	const key = (e.which || e.keyCode);
	const pressKey =String.fromCharCode( key ).toLowerCase();
	const numbers ="abcdefghijklmnopqrstuvwxyzáéíóúñ";
    const allowKeys = [8,35,32,36,37,38,39,46];
	if(numbers.indexOf(pressKey) === -1 || allowKeys.find(element => element === key) !== undefined) {
		return true;
	}
	return false;
}

const print_alert = (message) => {
	toastr.warning(message, "Alerta", {
		"timeOut": 3000,
		"progressBar": true,
		"closeButton": true,
		"preventDuplicates": true
	});
}

// -- Función que se encarga de imprimir alerta tipo bootstrap.
const print_message = (message) => {
	toastr.success(message, "Mensaje", {
		"timeOut": 3000,
		"progressBar": true,
		"closeButton": true,
		"preventDuplicates": true
	});
}

const cerrar_sesion = (exito = true) => {
	const data = JSON.stringify({"accion" : "cerrar_sesion"});
	callServerJQ('POST', apiURL_sesion, data, (response) => {
		window.location='sesion.php';
	});
}