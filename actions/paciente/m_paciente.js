/*
 * Desarrollador: Rodrigo García
 * Empresa: -
 * Nombre: m_paciente.js
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |-------------------------------------------------------------------------------------------------------------------------|
 */
/*
$(window).unload(function() {
	if($('#nombre_usuario').val()!=='') {
		administrar_registro($('#operacion').val());
	}
});
*/

$(document).ready(() => {
	console.log("App loaded");

	getAllData();
	$("#form").hide();

	$('#btn_agregar').click(() => {
		resetForm();
		$('#operacion').val('insert');
		$('#btn_eliminar').hide();
		$('#label_actualiza').html("Paciente sin actualizar");	
		$("#form").show();
		$('#nombre').focus();
	});

	$('#btn_guardar').click(() => {
		const operacion = $('#operacion').val();
		save(operacion);
	});

	$('#btn_eliminar').click(() => {
		save('delete');
	}).hide();

	$('#btn_cancelar').click(() => {
		resetForm();
	});

	$('#btn_salir').click(() => {
		cerrar_sesion(false);
	});

	$('#edad').datepicker({
		format: "dd/mm/yyyy",
		changeMonth: true,
        changeYear: true
	}).on('change', function(ev){	
		const fecha_nacimiento = $(this).val().substr(6,10)+"-"+$(this).val().substr(3,2)+"-"+($(this).val().substr(0,2));
		getAge(fecha_nacimiento, "#lbl_edad");
		$(this).datepicker('hide');
	});
	
	setInterval(() => {
		autoSave();
	}, 300000);

});


const autoSave = () => {
	const cve_paciente = $("#cve_paciente").val();
	const operacion = $('#operacion').val();
	if(cve_paciente !== '' && operacion !== '') {
		console.log(`[${(new Date())}]Record saved!`);
		save(operacion);
	}
}

const getAge = (nacimiento, dom) => {
	const data = JSON.stringify({"accion":"computeAge", "nacimiento" : nacimiento});
	callServerJQ('POST', apiURL_paciente, data, (response) => {		
		if(response.jsonResponse.server_response.error === '00') {
			response.jsonResponse.data.forEach(elemento => {
				$(dom).html(elemento.anios+" a "+computeAge(elemento.anios, elemento.meses)+" m");
			});
			return false;
		}
		$(dom).html("Error");
	});
}

// -- Funci&oacute;n que limpia el formulario.
const resetForm = () => {
	$('#cve_paciente, #operacion, #nombre, #sexo, #edad, #domicilio, #telefono, #correo_electronico, #observaciones, #historia').val('');
	$('#lbl_edad').html('Edad');
	$("#nombre_paciente").html('Nuevo paciente');
	$("#form").hide();
	//getAllData();
}

const computeAge = (years, months) => {
	return parseInt(months) - parseInt((parseInt(years) * 12));
}

// -- Funci&oacute;n que muestra en la pantalla principal la informaci&oacute;n contenida dentro de la base de datos correpondiente al presente m&oacute;dulo.
const getAllData = () => {
	const data = JSON.stringify({"accion":"getAllData"});
	callServerJQ('POST', apiURL_paciente, data, (response) => {	
		if(response.jsonResponse.server_response.error === '00') {

			if ( $.fn.DataTable.isDataTable( '#listado-pacientes' ) ) {
				$('#listado-pacientes').DataTable().destroy();
			}
			$('#listado-pacientes > tbody').empty();
			
			if(response.jsonResponse.data.length>0) {
				response.jsonResponse.data.forEach((elemento) => {
					$('#listado-pacientes > tbody').append(`<tr align="center" style="cursor:pointer;" onclick="getOne('${elemento.cve_paciente}');">
															<td align="left">${elemento.nombre}</td>
															<td align="left">${elemento.anios} a ${computeAge(elemento.anios, elemento.meses)} m</td>
															</tr>`);
				});

				$('#listado-pacientes').DataTable({
					"pageLength": 10,					
					"select": true,
					"dom": '<lf<t>ip>',
					//"dom": '<"top"i>rt<"bottom"flp><"clear">',
					//"dom" : "<'row'<'col-sm-12 col-md-6 col-lg-12'l><'col-sm-12 col-md-6'f>>" +
					//"<'row'<'col-sm-12'tr>>" +
					//"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
					"ordering":true,
					"info": false,
					"lengthChange": false,
					"searching": true,
					//"pagingType": "simple_numbers",
					"pagingType": "simple",
					"language": {
								"lengthMenu": "Se muestran _MENU_ registros por página",
								"zeroRecords": "La consulta no generó resultados",
								"info": "Página _PAGE_ de _PAGES_",
								"infoEmpty": "La consulta no generó resultados",
								"search": "_INPUT_",
								"searchPlaceholder": "Buscar....",
								"paginate": { "previous": "Anterior", "next" : "Siguiente" },
								"infoFiltered": "(filtrados de _MAX_ registros)"
							}
					
				});
				$("#filter_searching").removeClass("form-control-sm").css({ "max-width" : "100%", "font-size" : "14px"});
			}
			else {
				$('#listado-pacientes > tbody').append('<tr><td>La consulta no generó resultados</td></tr>');
			}
		}
		else {
			$('#listado-pacientes > tbody').append('<tr><td>'+response.jsonResponse.server_response.message+'</td></tr>');
		}
	});
}

// -- Funci&oacute;n que se encarga de obtener la informaci&oacute;n de un registro seleccionado.
const getOne = (cve_paciente) => {
	console.log(cve_paciente);
	$("#form").show();
	$('#historia').focus();
	$("#operacion").val("update");
	$('#btn_eliminar').show();
		
	const data = JSON.stringify({"accion":"getOne", "cve_paciente" : cve_paciente});
	callServerJQ('POST', apiURL_paciente, data, (response) => {	
		if(response.jsonResponse.server_response.error === '00') {
			
			response.jsonResponse.data.forEach(elemento => {
				const nacimiento = elemento.edad.substr(8,10)+"/"+elemento.edad.substr(5,2)+"/"+elemento.edad.substr(0,4);
				//const edad = new Date(elemento.edad);
				//const nacimiento = ((edad.getDate()+1) < 10 ? "0"+(edad.getDate()+1) : (edad.getDate()+1))+"/"+((edad.getMonth()+1) < 10 ? "0"+(edad.getMonth()+1) : (edad.getMonth()+1))+"/"+(edad.getYear()+1900);

				$("#cve_paciente").val(elemento.cve_paciente);
				$("#nombre_paciente").html(elemento.nombre);
				$("#nombre").val(elemento.nombre);
				$("#sexo").val(elemento.sexo);
				$("#edad").val(nacimiento);	
				$("#lbl_edad").html(elemento.anios+" a "+computeAge(elemento.anios, elemento.meses)+" m");
				$("#domicilio").val(elemento.domicilio);
				$("#telefono").val(elemento.telefono);
				$('#correo_electronico').val(elemento.correo_electronico);
				$("#observaciones").val(elemento.observaciones);
				$("#historia").val(elemento.historia);
				$('#label_actualiza').html("Paciente sin actualizar");
			});

			return false;
		}
		print_alert(response.jsonResponse.server_response.message);
	});
}
/*
const insertNewRow = (cve_paciente, nombre, edad) => {
	console.log(cve_paciente, nombre, edad)
	const tpacientes = document.getElementById("listado-pacientes").getElementsByTagName("tbody")[0];
	const newRow = `<tr align="center" style="cursor:pointer;">
						<td align="left" onclick="getOne('${cve_paciente}');">${nombre}</td>
						<td align="left">${edad}</td>
						</tr>`;
	tpacientes.insertRow(-1).innerHTML = newRow;
}
*/

// -- Se valida la informaci&Atilde;&sup3;n dentro del formulario y se empaqueta en un objeto json para su env&Atilde;&shy;o al servidor.
const save = (operacion) => {
	let msgerr='';
	let numerr=0;
	operacion = operacion == "" ? $('#operacion').val() : operacion;
	const cve_paciente = $('#cve_paciente').val().replace(' ','').trim();
	const nombre = $('#nombre').val().trim();
	const nacimiento = $('#edad').val().trim();
	const edad = nacimiento.substr(6,10)+'-'+nacimiento.substr(3,2)+'-'+nacimiento.substr(0,2);
	const sexo = $('#sexo').val();
	const domicilio = $('#domicilio').val().trim();
	const telefono = $('#telefono').val().trim();
	const correo_electronico = $('#correo_electronico').val().trim();
	const observaciones = $('#observaciones').val().trim();
	const historia = $('#historia').val().trim();
	
	if(operacion !== 'delete') {
		if(nombre === "" && nombre.length < 8) {
			numerr++;
			msgerr+="El nombre tecleado no es válido\n";
		}
		
		if(edad === "") {
			numerr++;
			msgerr+="La edad no es válida\n";
		}

		if(sexo === "") {
			numerr++;
			msgerr+="El sexo del paciente no es válido\n";
		}
	}

	if(numerr<=0) {
		const data = JSON.stringify({
			"accion":"save", 
			"operacion" : operacion, 
			"cve_paciente":cve_paciente, 	
			"nombre" : nombre,
			"edad" : edad,
			"sexo" : sexo,
			"domicilio" : domicilio,
			"telefono" : telefono,
			"correo_electronico" : correo_electronico,
			"observaciones" : observaciones,
			"historia" : historia
		});
		
		if(operacion === 'delete') {
			if(!confirm('Se eliminará el registro, ¿desea continuar?')) {
				return false;
			}
		}
		
		callServerJQ('POST', apiURL_paciente, data, (response) => {		
			if(response.jsonResponse.server_response.error === '00') {
				$('#label_actualiza').html("Paciente actualizado: "+response.jsonResponse.server_response.actualizado);
				if(operacion === 'delete') {
					resetForm();
					getAllData();
					return false;
				}
				if(response.jsonResponse.server_response.id !== 0) {
					$('#cve_paciente').val(response.jsonResponse.server_response.id);
					$('#operacion').val('update');
					$('#btn_eliminar').show();

					getAllData();
					//insertNewRow($('#cve_paciente').val(), nombre, $("#lbl_edad").text());
				}
			}
			print_alert(response.jsonResponse.server_response.message);
		});
		return false;
	}
	print_alert(msgerr);
}
