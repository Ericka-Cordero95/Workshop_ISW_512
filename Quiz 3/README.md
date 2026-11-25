# Quiz 3 · Cursos y matrícula

Aplicación MVC en Flask y SQLite que continúa el Workshop 5. Desde la página principal, el estudiante ingresa su identificación y nombre, selecciona un curso y pulsa **Matricular**. El comprobante muestra los datos guardados y un número de matrícula. La sección **Cursos** permite crear, consultar, editar y eliminar cursos.

## Ejecutar en Windows con Git Bash

Abre Git Bash en la carpeta del repositorio. Ejecuta cada línea por separado.

```bash
cd "Quiz 3"
python -m venv .venv
./.venv/Scripts/python.exe -m pip install -r requirements.txt
./.venv/Scripts/python.exe app.py
```

Abre http://127.0.0.1:5000 en el navegador. Para detener Flask, pulsa Ctrl + C en Git Bash. En las siguientes sesiones solo necesitas ejecutar el último comando desde la carpeta del quiz.

## Archivos

| Ubicación | Función |
|---|---|
| app.py | Inicia Flask, registra los controladores y prepara la base. |
| core/database.py | Crea academico.db y carga dos carreras y tres cursos de ejemplo en el primer arranque. |
| models/ | Ejecuta consultas de cursos y matrículas en SQLite. |
| controllers/ | Recibe formularios, valida los datos y selecciona la vista. |
| views/ | Contiene los formularios, el listado y el comprobante. |
| static/ | Contiene los estilos de las páginas. |

## Prueba de funcionamiento

1. En Cursos, agrega un curso con código DEMO-01, nombre Redes, 3 créditos y una carrera. Verifica que aparezca en el listado.
2. Edita el curso y cambia sus créditos a 4. Regresa a Matrícula y selecciónalo.
3. Registra a Ana Mora con identificación PRACTICA-01. Comprueba su nombre, el código, los 4 créditos y la carrera en el comprobante.
4. Recarga el comprobante. Conserva la URL, elimina el curso desde Cursos y vuelve a abrirla. El comprobante conserva los datos que tenía el curso al matricularse.
