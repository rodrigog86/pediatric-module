<?php
// -- Se contin&uacute;a con la sesi&oacute;n activa.
session_start();
// -- Se importan funcionalidades de los siguientes archivos.
include_once "conf/tools.php";
include_once "conf/connection.php";
date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Pediatría</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" href="conf/css/datepicker3.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<link rel="stylesheet" href="conf/css/main.css?v=0.0.13" />
		<link rel="stylesheet" href="conf/css/css_sesion.css?v=0.0.15" />
		<link rel="stylesheet" href="conf/css/jquery-ui.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<!--<script type="text/javascript" charset="utf8" src="conf/js/jquery-3.5.1.js"></script>-->
		<script src="conf/js/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	</head>
<body>
 
	<!-- Se valida que exista una sesi&oacute;n activa para mostrar el contenido de la p&aacute;gina. -->
	<?php
		if($_SESSION["usuario_conectado"]=='') {
	?>
		<div class="container mt-5">
			<div class="text-center">
				<div class="alert alert-warning" role="alert">
					<i class="fas fa-exclamation-circle"></i>  No es posible mostrar el contenido sin una sesión activa.
				</div>
			</div>
		</div>
	</body>
</html>
	<?php
			exit();
		}
	?>

	<!-- objetos ocultos para guardar valores temporales. -->
	<input type="hidden" id="session_id" value="<?php echo session_id(); ?>" />
	<input type="hidden" id="nombre_usuario" value="<?php echo strtolower($_SESSION["nombre_empleado"]); ?>" />
	<input type="hidden" id="tiempo_sin_actividad" value="<?php echo $_SESSION["tiempo_inactividad"]; ?>" />
	<input type="hidden" id="cve_paciente" value="" />
	<input type="hidden" id="operacion" value="insert" />

	  	<div class="pt-2 pl-3 pr-3">
			<div class="row"> 
				<div class="col-md-4">
					<div class="card">
						<div class="card-header font-weight-bold">
							<i class="fas fa-user-md"></i> 
							<label class="h5">Dr. García Pérez</label>
							<button id="btn_salir" class="btn btn-sm btn-danger float-right">Cerrar sesión</button>
							
						</div>
								
						<div class="card-body">
							<div class="row text-center mb-5">
								<div class="col-12 text-center">
									<button id="btn_agregar" class="btn btn-sm btn-primary">Registrar paciente</button>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<table id="listado-pacientes" class="table table-sm table-hover compact">
										<thead class="bg-white text-dark">
											<tr class="text-left ">
												<th class="small font-weight-bold">Nombre</th>
												<th class="small font-weight-bold">Edad</th>
											</tr>
										</thead>
										<tbody class="text-left text-muted small">

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="form" class="col-md-8">
					<div class="card">
	<!--
						<div class="card-header font-weight-bold">
							<label class="h5" id="nombre_paciente">Nuevo paciente</label>
						</div>
	-->
						<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nombre">Nombre</label>
									<input type="text" id="nombre" class="form-control" value="" placeholder="Nombre completo" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sexo">Sexo</label>
									<select id="sexo" class="form-control">
										<option value=''>Elige</option>
										<option value='F'>Mujer</option>
										<option value='M'>Hombre</option>
									</select>
								</div>					
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label id="lbl_edad" for="edad">Edad</label>
									<input type="text" class="datepicker form-control" id="edad" placeholder="Nacimiento" readonly/>
								</div>	
							</div>
						

							<div class="col-md-4">
								<div class="form-group">
									<label for="domicilio">Domicilio</label>
									<input type="text" id="domicilio" class="form-control" value="" placeholder="Domicilio"/>
								</div>	
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="telefono"><?php echo utf8_encode("Tel&eacute;fonos"); ?></label>
									<input type="text" id="telefono" class="form-control" value="" placeholder="<?php echo utf8_encode("Tel&eacute;fonos"); ?>"/>
								</div>	
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="correo_electronico">Correo electr&oacute;nico</label>
									<input type="text" id="correo_electronico" class="form-control" value="" placeholder="Correo electr&oacute;nico"/>
								</div>	
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label for="observaciones">Observaciones</label>
									<textarea id="observaciones" class="form-control" style="height:57px;"></textarea>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label for="historia">Historia</label>
									<textarea id="historia" class="form-control" rows="6"></textarea>
								</div>
							</div>
						</div>
						</div>
			
						<div class="card-footer">
							<div class="form-group clearfix">
								<button id="btn_guardar" class="btn btn-md btn-primary"><i class="glyphicon glyphicon-save"></i>&nbsp;Guardar</button>
								<button id="btn_eliminar" class="btn btn-md btn-danger"><i class="glyphicon glyphicon-trash"></i>&nbsp;Eliminar</button>
								<button id="btn_cancelar" class="btn btn-md btn-default"><i class="glyphicon glyphicon-remove"></i>&nbsp;Cancelar</button>
								&nbsp;<label class="font-weight-bold float-right id="label_actualiza"></label>
							</div>
						</div>
					</div>
				</div>
			</div>
	  	</div>

	<!-- Datatables-->
	<script type="text/javascript" charset="utf8" src="conf/js/1.10.21/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8" src="conf/js/1.10.21/dataTables.bootstrap4.min.js"></script>
	<!-- Datatables-->
	<script src="conf/js/bootstrap-datepicker.min.js"></script>
	<script src="conf/js/ajax.js?v=0.0.8"></script>
	<script src="conf/js/validaciones.js?v=0.0.1"></script>
	<script src="conf/js/tools.js?v=0.0.2"></script>
	<script src="actions/paciente/m_paciente.js?v=0.0.2"></script>
  </body>
</html>