-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema dbpet
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dbpet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dbpet` DEFAULT CHARACTER SET utf8mb3 ;
USE `dbpet` ;

-- -----------------------------------------------------
-- Table `dbpet`.`tipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbpet`.`tipo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `dbpet`.`mascota`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbpet`.`mascota` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NULL DEFAULT NULL,
  `tipo_id` INT NULL DEFAULT NULL,
  `fecha_nacimiento` DATE NULL DEFAULT NULL,
  `peso` DECIMAL(10,0) NULL DEFAULT NULL,
  `foto` LONGBLOB NULL DEFAULT NULL,
  `foto_url` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_idx` (`tipo_id` ASC) VISIBLE,
  CONSTRAINT `FK`
    FOREIGN KEY (`tipo_id`)
    REFERENCES `dbpet`.`tipo` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb3;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
