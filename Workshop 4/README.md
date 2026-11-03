# Workshop 4 - Gestión de Cursos con patrón MVC (ISW-613)

Reorganización del Workshop 3 con el patrón **MVC** (Modelo - Vista - Controlador). La aplicación mantiene las mismas funcionalidades: registro, inicio y cierre de sesión, perfil de usuario y CRUD de cursos.

## Patrón MVC

- **Modelos** (`models/`): único lugar con consultas SQL (PDO y consultas preparadas).
- **Vistas** (`views/`): solo HTML y PHP de presentación; no consultan la base de datos.
- **Controladores** (`controllers/`): reciben la petición, validan, usan los modelos y deciden qué vista mostrar o a dónde redirigir.
- **Controlador frontal** (`index.php`): punto de entrada único. Recibe `?c=` (controlador) y `?a=` (acción) y ejecuta la acción solo si está en una lista blanca de rutas.

Ejemplo: `/index.php?c=cursos&a=listar` ejecuta `CursosController::listar()`.

## Funcionalidades

- Registro, inicio y cierre de sesión (contraseñas con `password_hash`).
- Perfil de usuario editable con provincia.
- Menú de navegación: Perfil, Cursos y Cerrar sesión.
- Listado de cursos, crear, editar y eliminar (eliminar solo por POST).

## Requisitos

- XAMPP (Apache + MySQL) con PHP 8 o superior
- Extensión PDO MySQL

## Instalación

1. Iniciar Apache y MySQL desde el panel de XAMPP.
2. En phpMyAdmin, ejecutar `database.sql` (crea la base `workshop1` con sus tablas y datos de ejemplo).
3. Revisar las credenciales en `models/Database.php` (por defecto: `root`, sin contraseña).
4. Ejecutar con una de estas opciones:
   - **Virtual host:** agregar `127.0.0.1 workshop4.local` al archivo `hosts` y un bloque `VirtualHost` con `DocumentRoot` en la carpeta `Workshop 4`. Abrir `http://workshop4.local`.
   - **Servidor integrado de PHP:** desde la carpeta `Workshop 4`, ejecutar `php -S localhost:8000` y abrir `http://localhost:8000`.

## Estructura

```text
Workshop 4/
├── index.php                    Controlador frontal
├── database.sql
├── README.md
├── core/
│   ├── Controller.php           Clase base de los controladores
│   ├── Mensajes.php             Mensajes de éxito y error
│   └── helpers.php              Función e() para escapar la salida
├── controllers/
│   ├── AuthController.php       Login, registro y logout
│   ├── PerfilController.php     Ver y actualizar perfil
│   └── CursosController.php     Listar, crear, editar y eliminar cursos
├── models/
│   ├── Database.php             Conexión PDO
│   ├── Usuario.php
│   ├── Provincia.php
│   ├── Carrera.php
│   └── Curso.php
└── views/
    ├── layout/                  header, navbar y footer
    ├── auth/                    login y registro
    ├── perfil/                  ver
    └── cursos/                  listado y formulario
```

## Base de datos

Base `workshop1`: `provincias`, `usuarios` (con provincia), `carreras` y `cursos` (clave foránea a carreras, código único).

## Seguridad

- Consultas preparadas con PDO.
- Contraseñas con `password_hash` / `password_verify`.
- Páginas y acciones protegidas con sesión.
- Lista blanca de controladores y acciones en el controlador frontal.
- Eliminar y cerrar sesión solo por POST.
- Validación en el servidor y salida escapada con `htmlspecialchars`.

## Autora

Ericka Cordero - ISW-613