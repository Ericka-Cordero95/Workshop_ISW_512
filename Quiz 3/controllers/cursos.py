import sqlite3

from flask import Blueprint, abort, redirect, render_template, request, url_for
from models import curso

cursos = Blueprint("cursos", __name__, url_prefix="/cursos")


@cursos.get("/")
def lista():
    return render_template("cursos/lista.html", cursos=curso.listar())


@cursos.route("/nuevo", methods=["GET", "POST"])
@cursos.route("/<int:curso_id>/editar", methods=["GET", "POST"])
def formulario(curso_id=None):
    datos = {}
    if curso_id is not None:
        datos = curso.buscar(curso_id)
        if datos is None:
            abort(404)

    carreras = curso.carreras()
    error = None
    if request.method == "POST":
        datos = {campo: request.form.get(campo, "").strip()
                 for campo in ("nombre", "codigo", "creditos", "carrera_id")}
        datos["codigo"] = datos["codigo"].upper()
        if not 1 <= len(datos["nombre"]) <= 100 or not 1 <= len(datos["codigo"]) <= 25:
            error = "Ingresa un nombre de hasta 100 caracteres y un código de hasta 25."
        elif not datos["creditos"].isascii() or not datos["creditos"].isdigit() or len(datos["creditos"]) > 9 or int(datos["creditos"]) < 1:
            error = "Ingresa una cantidad de créditos entera y positiva de hasta 9 dígitos."
        elif datos["carrera_id"] not in {str(fila["id"]) for fila in carreras}:
            error = "Selecciona una carrera de la lista."
        else:
            try:
                if not curso.guardar(curso_id, datos):
                    abort(404)
                return redirect(url_for("cursos.lista"), code=303)
            except sqlite3.IntegrityError:
                error = "El código ya pertenece a otro curso."

    return render_template("cursos/formulario.html", datos=datos, carreras=carreras,
                           editar=curso_id is not None, error=error), 400 if error else 200


@cursos.post("/<int:curso_id>/eliminar")
def eliminar(curso_id):
    if not curso.eliminar(curso_id):
        abort(404)
    return redirect(url_for("cursos.lista"), code=303)
