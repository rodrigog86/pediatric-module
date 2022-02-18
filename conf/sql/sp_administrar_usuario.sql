USE pediatriaDB;
DROP PROCEDURE IF EXISTS `pediatriaDB`.`sp_administrar_usuario`;
delimiter $$
CREATE PROCEDURE `pediatriaDB`.`sp_administrar_usuario`(
IN i_operacion CHAR(1), 
IN i_cve_usuario CHAR(12), 
IN i_contrasena VARCHAR(60),
IN i_nombre VARCHAR(90))
BEGIN
	DECLARE resultado CHAR(100) DEFAULT '';	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT; 
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            select @full_error as message;
		END;		
	START TRANSACTION;
		IF EXISTS(SELECT cve_usuario FROM `pediatriaDB`.`usuario` WHERE cve_usuario = i_cve_usuario) THEN
			UPDATE `pediatriaDB`.`usuario` SET		  
			  contransena = i_contrasena, 
			  nombre = i_nombre
			WHERE cve_usuario = i_cve_usuario;			
		ELSE
			INSERT INTO `pediatriaDB`.`usuario` (cve_usuario, contrasena, nombre) VALUES (i_cve_usuario, i_contrasena, i_nombre);
		END IF;			
		SET resultado = concat("OK|",i_cve_usuario);
	COMMIT;
	select resultado as message;
END;
$$
