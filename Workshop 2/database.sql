CREATE DATABASE IF NOT EXISTS workshop1 CHARACTER SET utf8mb4;
USE workshop1;

CREATE TABLE IF NOT EXISTS provincias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) CHARACTER SET utf8mb4;

INSERT IGNORE INTO provincias (nombre) VALUES
  ('San José'), ('Alajuela'), ('Cartago'), ('Heredia'),
  ('Guanacaste'), ('Puntarenas'), ('Limón');

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  provincia_id INT NULL,
  CONSTRAINT fk_usuarios_provincia
    FOREIGN KEY (provincia_id) REFERENCES provincias(id)
) CHARACTER SET utf8mb4;