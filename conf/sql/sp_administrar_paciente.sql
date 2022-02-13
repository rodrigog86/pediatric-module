USE pediatriaDB;
DROP PROCEDURE IF EXISTS `pediatriaDB`.`sp_administrar_paciente`;
delimiter $$
CREATE PROCEDURE `pediatriaDB`.`sp_administrar_paciente`(IN i_operacion CHAR(1), IN i_cve_paciente INT, IN i_nombre VARCHAR(90), IN i_edad DATE, IN i_sexo CHAR(1), IN i_domicilio CHAR(255), IN i_telefono CHAR(100), IN i_correo_electronico VARCHAR(100), IN i_historia LONGTEXT, IN i_historia2 LONGTEXT, IN i_observaciones LONGTEXT)
BEGIN
	DECLARE resultado CHAR(100) DEFAULT '';	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT; 
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            select @full_error as message;
		END;		
	START TRANSACTION;
		IF i_operacion <> '' THEN
			UPDATE `pediatriaDB`.`paciente` SET visible = 'F' WHERE cve_paciente = i_cve_paciente;
			SET resultado = concat("OK|",i_cve_paciente);
		ELSE
			IF EXISTS(SELECT cve_paciente FROM `pediatriaDB`.`paciente` WHERE cve_paciente = i_cve_paciente) THEN
				UPDATE `pediatriaDB`.`paciente` SET		  
				  nombre = i_nombre, 
				  edad = i_edad, 
				  sexo = i_sexo, 
				  domicilio = i_domicilio, 
				  telefono = i_telefono, 
				  correo_electronico = i_correo_electronico, 
				  historia = i_historia, 
				  historia2 = i_historia2, 
				  observaciones = i_observaciones
				WHERE cve_paciente = i_cve_paciente;			
			ELSE
				INSERT INTO `pediatriaDB`.`paciente` (nombre, edad, sexo, domicilio, telefono, correo_electronico, historia, historia2, observaciones) VALUES (i_nombre, i_edad, i_sexo, i_domicilio, i_telefono, i_correo_electronico, i_historia, i_historia2, i_observaciones);
				SET i_cve_paciente = LAST_INSERT_ID();
			END IF;			
			SET resultado = concat("OK|",i_cve_paciente);
		END IF;
	COMMIT;
	select resultado as message;
END;
$$
