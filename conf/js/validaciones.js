// -- Valida si el dia se encuentra entre 1 y 31.
const isValidDay = (day) => {
	console.log((day > 31 || day < 1))
	return !(day > 31 || day < 1);
}

// -- Valida si el mes se encuentra entre 1 y 12.
const isValidMonth = (month) => {
	return !(month > 12 || month < 1);
}

// -- Devuelve el último día de una fecha compuesta por mes y año.
const lastDayOfMonth = (month, year) => {
	if(month === 2) {
		return ((year%4 == 0 && year%100 !=0) || year%400==0) ? 29 : 28
	}
	return [1, 3, 5, 7, 8, 10, 12].indexOf(month) >= 0 ? 31 : 30;
}

// -- Se valida que el valor ingresado sea numérico.
const isNumber = (val) => {
	return (/^([0-9])*$/.test(val));
}

// -- Se valida que el teléfono ingresado sea valido.
const isPhone = (phone) => {
	return (phone !== '' && phone.length >= 10 && phone.substr(0,1) !== '0' && phone.substr(1,1) !=='0');
}

// -- Valida que el campo se encuentre entre los límites definidos.
const verifyValue = (val, lowerLimit, upperLimit) => {
	return (val === '' || (val.length >= lowerLimit && val.length <= upperLimit)); 
}

// -- Valida que la fecha ingresada dentro del RFC sea correcta.
const isValidDate = (rfc, number) => {
	try {
		const year = parseInt(rfc.substr(number === 3 ? 3 : 4 , 2));
		const month = parseInt(rfc.substr(number === 3 ? 5 : 6 , 2));
		const day = parseInt(rfc.substr(number === 3 ? 7 : 8, 2));

		if(isValidDay(day) && isValidMonth(month) && lastDayOfMonth(month, year)) {
			return true;
		}
	}
	catch(e) {
		return false;
	}
	return false;
}

const rfcIsValid = (rfc, person) => {
	let name_person = rfc.substr(0,4); // Persona Moral
	let number = name_person.length;
	if(person !== "") {
		if(rfc.length === 12) { //Persona física
			name_person = rfc.substr(0,3);
			number = name_person.length;
		}

		if(/^[A-Za-z]*$/.test(name_person) && isValidDate(rfc, number) && /^[A-Za-z0-9]*$/.test(rfc.substr(rfc.length - 3, rfc.length))) {
			return true;
		}
	}
	return false;
}

const countChar = (string, char) => {
	return string.match(new RegExp(char, "g")).length;
}

// -- Se valida el correo electrónico.
const isEmailValid = (email) => {
	console.log(email === "" ? "vacio" : email);
	if(email !== "") {
		if(/^[A-Za-z0-9]*$/.test(email[0]) && (email.indexOf('@') > 0  && countChar(email, '@') === 1)  && email.indexOf(' ') < 1 && email.indexOf('..') < 1 && email.indexOf('.') > 0) {	
			const username = email.substr(0, email.indexOf('@')); 
			const domain = email.substr(username.length + 1, email.length);
			if(/^\w+([\.-]?\w+)*$/.test(username) && /^\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(domain)) {
				return true;
			}
		}
	}
	return false;
}