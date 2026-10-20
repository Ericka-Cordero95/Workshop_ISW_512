# Workshop 3 - Gestión de Cursos (ISW-613)

Aplicación web en PHP y MySQL con inicio de sesión, perfil de usuario y un módulo de **Cursos** con operaciones completas de crear, listar, editar y eliminar (CRUD). Continúa el Workshop 2.

## Funcionalidades

- Registro, inicio y cierre de sesión de usuarios (contraseñas con `password_hash`).
- Perfil de usuario editable, con selección de provincia.
- Menú de navegación: **Perfil**, **Cursos** y **Cerrar sesión**.
- Listado de cursos en tabla: Nombre, Código de Curso, Id de la Carrera, Créditos y Descripción del Curso.
- **Crear Curso**: formulario que, al guardar, redirige al listado.
- **Editar** cada curso desde su fila, con el formulario precargado.
- **Eliminar** cada curso mediante un formulario **POST** con confirmación.
- Mensajes de éxito y error en cada operación.

## Requisitos

- XAMPP (Apache + MySQL) con PHP 8 o superior
- Extensión PDO MySQL (incluida en XAMPP)
- Navegador web

## Instalación

1. Clonar el repositorio y ubicar la carpeta `Workshop 3`.
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. En phpMyAdmin, abrir la pestaña **SQL**, pegar el contenido de `database.sql` y ejecutarlo (crea la base `workshop1` con sus tablas y datos de ejemplo).
4. Revisar las credenciales en `utils/database.php` (por defecto: usuario `root`, sin contraseña).
5. Ejecutar la aplicación con una de estas opciones:
   - **Virtual host:** agregar `127.0.0.1 workshop3.local` al archivo `hosts` y un bloque `VirtualHost` en `httpd-vhosts.conf` con `DocumentRoot` apuntando a la carpeta `Workshop 3`. Abrir `http://workshop3.local`.
   - **Servidor integrado de PHP:** desde la carpeta `Workshop 3`, ejecutar `php -S localhost:8000` y abrir `http://localhost:8000`.

## Estructura del proyecto

```text
Workshop 3/
├── index.php                  Inicio de sesión
├── database.sql               Script de base de datos
├── README.md
├── actions/
│   ├── login.php
│   ├── logout.php
│   ├── registro.php
│   ├── actualizar_perfil.php
│   ├── curso_guardar.php      Crea o actualiza un curso
│   └── curso_eliminar.php     Elimina un curso (solo POST)
├── pages/
│   ├── registro.php
│   ├── perfil.php
│   ├── cursos.php             Listado de cursos
│   ├── curso_form.php         Formulario de crear/editar
│   └── common/
│       ├── header.php
│       ├── navbar.php         Menú de navegación
│       └── footer.php
└── utils/
    ├── database.php           Conexión PDO
    ├── helpers.php            redirect() y require_login()
    ├── messages.php           Mensajes centralizados
    └── cursos.php             Consultas de cursos y carreras
```

## Base de datos

Base de datos `workshop1`:

| Tabla | Descripción |
|-------|-------------|
| `provincias` | Catálogo de provincias |
| `usuarios` | Usuarios registrados (relación con `provincias`) |
| `carreras` | Catálogo de carreras (nombre único) |
| `cursos` | Cursos: nombre, código único, `carrera_id` (clave foránea a `carreras`), créditos y descripción |

## Seguridad

- Consultas preparadas con PDO (previenen inyección SQL).
- Contraseñas almacenadas con `password_hash` y verificadas con `password_verify`.
- Páginas y acciones protegidas con `require_login()`.
- Eliminar y cerrar sesión solo aceptan el método POST.
- Validación en el servidor de todos los campos del curso.
- Salida escapada con `htmlspecialchars` (previene XSS).
- Restricción `UNIQUE` en el código del curso y clave foránea hacia `carreras`.

## Autora

Ericka Cordero - ISW-613