<?php
include_once("conf/connection.php");
// -- Se establece el timezone. 
date_default_timezone_set('America/Mexico_City');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Pediatria</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" href="conf/css/datepicker3.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<link rel="stylesheet" href="conf/css/main.css?v=0.0.13" />
		<link rel="stylesheet" href="conf/css/css_sesion.css?v=0.0.15" />
		<link rel="stylesheet" href="conf/css/jquery-ui.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="conf/js/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	</head>
<body onpaste="return false;" oncopy="return false;">
	
<!-- Modal para generar el primer usuario. -->
	<div class="modal fade" id="modal_add_user" tabindex="-1" role="dialog"
        aria-labelledby="modal_add_user_title" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
				<div class="modal-header bg-dark text-white">
					Asistente para generar usuario
				</div>
                <div class="modal-body">
					<div class="container">
						<div class="form-group">
							<input type="text" class="form-control" id="add_name" value="Dra. Beatriz Cadena Montero" maxlength="90" placeholder="Nombre de usuario" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="add_user" value="drabett2" maxlength="12" placeholder="Usuario" />
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="pwd_user" value="" maxlength="12" placeholder="Contraseña" />
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="confirm_pwd" value="" maxlength="12" placeholder="Confirmar contraseña" />
						</div>

						<button id="btn_create_user" class="mt-3 btn btn-sm btn-primary btn-block">Crear usuario</button>
					</div>
				</div>
            </div>
        </div>
    </div>



	<div class="d-flex justify-content-center">
		<div class="pt-2 m-5">
			<div class="row">
				<div class="col-sm-12 col-12 col-md-12 col-lg-12">
					<div class="card">
						<div class="card-header text-center">
							Pediatría
						</div>
						<div class="card-body">
							<div class="text-center">
								<img src="assets/logo.png" style="max-height:50%; max-width:50%;">
								<p>Iniciar sesión</p>
							</div>

							<div class="form-group">
								<label for="usuario" class="control-label">Usuario</label>
								<select class="form-control" id="usuario">

								</select>
							</div>

							<div class="form-group">
								<label for="contrasena" class="control-label">Contraseña</label>
								<input type="password" id="contrasena" class="form-control" placeholder="Contraseña" maxlength="16">
							</div>
						</div>
						<div class="card-footer">
							<button type="button" id="btn_entrar" class="btn btn-primary btn-block">Ingresar</button>
						</div>
					</div>
				</div>
			</div>
		</div>			
	</div>

	<script src="conf/js/ajax.js?v=0.0.8"></script>
	<script src="conf/js/validaciones.js?v=0.0.1"></script>
	<script src="conf/js/tools.js?v=0.0.2"></script>
	<script src="actions/sesion/m_sesion.js?v=0.0.15"></script>
	<script language="javascript" src="conf/js/CryptoJS v3.1.2/rollups/sha512.js"></script>
</body>
</html>
