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

CREATE TABLE IF NOT EXISTS carreras (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) CHARACTER SET utf8mb4;

INSERT IGNORE INTO carreras (nombre) VALUES
  ('Ingeniería del Software'), ('Administración de Empresas'),
  ('Contabilidad'), ('Turismo');

CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  carrera_id INT NOT NULL,
  creditos INT NOT NULL,
  descripcion TEXT NOT NULL,
  CONSTRAINT fk_cursos_carrera
    FOREIGN KEY (carrera_id) REFERENCES carreras(id)
) CHARACTER SET utf8mb4;

INSERT IGNORE INTO cursos (nombre, codigo, carrera_id, creditos, descripcion) VALUES
  ('Programación en Ambiente Web', 'ISW-613', 1, 4, 'Desarrollo de aplicaciones web con PHP y MySQL.'),
  ('Bases de Datos', 'ISW-512', 1, 4, 'Diseño de bases de datos relacionales y SQL.'),
  ('Matemática Discreta', 'MAT-201', 1, 3, 'Lógica, conjuntos y relaciones.');