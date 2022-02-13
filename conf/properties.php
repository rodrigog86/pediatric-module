<?php

/*
 * Desarrollador: Félix Rodrigo García Magaña (ES1521201162).
 * Empresa: *
 * Nombre: properties.php
 * Modificaciones
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	Fecha		|	Quién modificó	|	Descripción del cambio															 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 * |	20/01/2019	|	Rodrigo García	|	Creación de archivo de propiedades para configurar el sitio.					 |
 * |-------------------------------------------------------------------------------------------------------------------------|
 */

// -- Se establece el timezone. 
date_default_timezone_set('America/Mexico_City');
$fecha = new DateTime();
$tz = $fecha->getTimezone();

return Array(
    "tz" => $tz,
    "timezone" => $tz->getName(),
    "write_debugger" => true,
    "tiempo_inactividad" => 11900000,
    "str_titulo" => "Pediatría",
    "logo" => "assets/logo_empresa.png",
    "str_footer" => "Pediatría",
    "str_nombre_empresa" => "Pediatría",
    "str_formato_archivo" => "Pediatría_",
    "tipo_menu" => "TOP");

?>