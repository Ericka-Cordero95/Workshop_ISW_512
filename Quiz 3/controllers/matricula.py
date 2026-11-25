import sqlite3

from flask import Blueprint, abort, redirect, render_template, request, url_for
from models import curso, matricula as registro

matricula = Blueprint("matricula", __name__)


@matricula.route("/", methods=["GET", "POST"])
def inicio():
    error = None
    if request.method == "POST":
        estudiante = request.form.get("estudiante", "").strip()
        identificacion = request.form.get("identificacion", "").strip()
        if not 1 <= len(estudiante) <= 120 or not 1 <= len(identificacion) <= 30:
            error = "Completa el nombre y la identificación dentro de la longitud permitida."
        else:
            try:
                numero = registro.registrar(identificacion, estudiante, request.form.get("curso_id"))
                if numero is not None:
                    return redirect(url_for("matricula.comprobante", numero=numero), code=303)
                error = "Selecciona un curso disponible."
            except sqlite3.IntegrityError:
                error = "El estudiante ya tiene una matrícula en este curso."

    return render_template("matricula/formulario.html", cursos=curso.listar(),
                           datos=request.form, error=error), 400 if error else 200


@matricula.get("/matriculas/<int:numero>")
def comprobante(numero):
    datos = registro.buscar(numero)
    if datos is None:
        abort(404)
    return render_template("matricula/comprobante.html", m=datos)
