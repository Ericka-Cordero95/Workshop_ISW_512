# Inventario de productos

Aplicación web para crear, listar, editar y eliminar productos. Cada producto tiene un nombre y una cantidad. El proyecto usa Flask, SQLite y el patrón Modelo-Vista-Controlador (MVC).

## Instalación en Windows

Abre la carpeta `crud_mvc` en Visual Studio Code y selecciona **Terminal → Nueva terminal**. Usa PowerShell y verifica que la ruta de la terminal termine en `crud_mvc`.

Comprueba que Python esté instalado.

```powershell
python --version
```

Crea el entorno virtual. Si ya tienes la carpeta `.venv`, puedes pasar al comando de instalación.

```powershell
python -m venv .venv
```

Instala Flask dentro del entorno virtual.

```powershell
.\.venv\Scripts\python.exe -m pip install Flask
```

## Ejecución

En la misma terminal, ejecuta el controlador.

```powershell
.\.venv\Scripts\python.exe controlador.py
```

Cuando aparezca `Running on`, abre [http://127.0.0.1:5000](http://127.0.0.1:5000) en el navegador. Mantén la terminal abierta mientras usas la aplicación. Para detener el servidor, presiona **Ctrl + C** en la terminal.

## Archivos del proyecto

| Archivo | Función |
| --- | --- |
| `modelo.py` | **Modelo.** Crea la tabla de productos y ejecuta las consultas de SQLite para guardar, consultar, actualizar y eliminar registros. |
| `controlador.py` | **Controlador.** Recibe las solicitudes del navegador, valida los datos del formulario, llama al modelo y carga la vista. También inicia el servidor Flask. |
| `templates/productos.html` | **Vista.** Muestra el formulario, la lista de productos y las opciones para editar o eliminar. |
| `productos.db` | Base de datos SQLite. Se crea automáticamente junto a `modelo.py` al ejecutar el proyecto por primera vez. |

Al pulsar **Guardar**, la vista envía el formulario al controlador. El controlador valida los datos y llama al modelo para guardarlos. Luego el navegador vuelve al listado y muestra el cambio.

## Prueba rápida

1. Agrega `Teclado` con cantidad `5` y comprueba que aparezca en la lista.
2. Recarga la página y verifica que el producto siga registrado.
3. Pulsa **Editar**, cambia la cantidad a `8` y guarda.
4. Pulsa **Eliminar**, acepta la confirmación y verifica que el producto desaparezca.
