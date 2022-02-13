CREATE DATABASE `pediatriaDB` DEFAULT CHARACTER SET = utf8;

USE `pediatriaDB`;

DROP TABLE if exists `pediatriaDB`.`paciente`;
CREATE TABLE `pediatriaDB`.`paciente` (
	`cve_paciente` INT NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(90) NOT NULL DEFAULT '',
	`edad` DATE,
	`sexo` CHAR(1),
	`domicilio` VARCHAR(255),
	`telefono` CHAR(100),
	`correo_electronico` VARCHAR(100),
	`historia` BLOB,
	`historia2` BLOB,
	`observaciones` BLOB,
	`visible` CHAR(1) NOT NULL DEFAULT 'T',
	`registrado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`actualizado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(`cve_paciente`),
	INDEX `listado_idx` (`cve_paciente` ASC, `nombre` ASC, `sexo` ASC, `edad` ASC),
	INDEX `listado2_idx` (`cve_paciente` ASC, `nombre` ASC, `telefono` ASC, `edad` ASC),
	UNIQUE INDEX `unique_nombre` (`nombre` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

DROP TABLE if exists `pediatriaDB`.`usuario`;
CREATE TABLE `pediatriaDB`.`usuario` (
  `cve_usuario` VARCHAR(12) NOT NULL,
  `contrasena` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `fecha_hora_alta` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_hora_acceso` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre` VARCHAR(90) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  PRIMARY KEY (`cve_usuario`),
  UNIQUE INDEX `cve_usuario` (`cve_usuario` ASC),
  INDEX `listado_idx` (`cve_usuario` ASC, `nombre` ASC, `fecha_hora_acceso` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;
INSERT INTO `pediatriaDB`.`usuario` (`cve_usuario`, `contrasena`, `nombre`) VALUES('sa', '66hCgHk8aqXm6cQ4HzhEHc9bJuB4sgf8PJT46VGH3sU=', 'Doctor');

