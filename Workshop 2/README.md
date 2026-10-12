# Workshop 2: Registro, sesión y perfil de usuarios

Curso ISW-613 · Programación de Ambientes Web

Aplicación en PHP y MySQL que continuacion del Workshop 1. Permite registrar usuarios con su provincia, iniciar sesión y editar el perfil. Sigue la estructura del ejemplo `week3` del profesor.

## Funcionalidades:

- Registro de usuarios con username, contraseña y provincia (dropdown cargado desde la tabla `provincias`).
- Login que valida las credenciales contra la base de datos y crea una sesión.
- Pantalla de Perfil que muestra los datos de la sesión y permite actualizar username, provincia y contraseña.
- El Perfil solo es accesible con una sesión activa; sin ella se redirige al login.
- Cierre de sesión.

## Requisitos:

- XAMPP (Apache + MySQL) 
- PHP 7.4 o superior con la extensión PDO MySQL
- Un navegador web

## Instalación:

1. Clonar el repositorio:

```bash
   git clone https://github.com/Ericka-Cordero95/Workshop_ISW_512.git
```

2. Iniciar Apache y MySQL desde el panel de XAMPP.
3. Crear la base de datos: abrir `http://localhost/phpmyadmin`, ir a la pestaña **SQL**, pegar el contenido de `Workshop 2/database.sql` y presionar **Continuar**. Esto crea la base `workshop1` con las tablas `provincias` y `usuarios`.
4. Revisar las credenciales de conexión en `Workshop 2/utils/database.php` (por defecto: usuario `root`, sin contraseña, base `workshop1`).
5. Ejecutar la aplicación con una de estas opciones.

### Opción A: virtual host de Apache:

Agregar al final de `C:\xampp\apache\conf\extra\httpd-vhosts.conf` (ajustando la ruta):

```apache
<VirtualHost *:80>
    DocumentRoot "C:/ruta/al/repositorio/Workshop 2"
    ServerName workshop2.local
    <Directory "C:/ruta/al/repositorio/Workshop 2">
        Options All
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Agregar esta línea al archivo `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1 workshop2.local
```

Reiniciar Apache y abrir `http://workshop2.local`.

### Opción B: servidor integrado de PHP:

```bash
cd "Workshop 2"
php -S localhost:8000
```

Abrir `http://localhost:8000`. En XAMPP el ejecutable está en `C:\xampp\php\php.exe`.

> La aplicación usa rutas que empiezan con `/` (por ejemplo `/index.php`), por lo que la raíz del sitio debe ser la carpeta `Workshop 2`.

## Uso:

No hay usuarios precargados. Crear uno desde **Regístrese aquí** en la pantalla de login, iniciar sesión y se abre el Perfil.

## Estructura del proyecto:

```
Workshop 2/
├── index.php                  Pantalla de login
├── database.sql               Script de la base de datos
├── README.md
├── actions/                   Scripts que procesan formularios
│   ├── login.php
│   ├── logout.php
│   ├── registro.php
│   └── actualizar_perfil.php
├── pages/                     Pantallas
│   ├── registro.php
│   ├── perfil.php
│   └── common/
│       ├── header.php
│       └── footer.php
└── utils/                     Código compartido
    ├── database.php           Conexión PDO
    └── helpers.php            redirect() y require_login()
```

## Base de datos:

| Tabla | Columnas |
|---|---|
| `provincias` | `id`, `nombre` |
| `usuarios` | `id`, `username`, `password`, `provincia_id` |

`usuarios.provincia_id` es una llave foránea hacia `provincias.id`: una provincia tiene muchos usuarios y cada usuario pertenece a una provincia.

## Seguridad:

- Contraseñas cifradas con `password_hash` y verificadas con `password_verify`.
- Consultas preparadas con PDO (previenen inyección SQL).
- `htmlspecialchars` al mostrar datos en pantalla.
- Validación de los datos también en el servidor.
- `session_regenerate_id` al iniciar sesión.
- Páginas y acciones del perfil protegidas con `require_login()`.

## Autor:

Ericka Cordero Aguero.