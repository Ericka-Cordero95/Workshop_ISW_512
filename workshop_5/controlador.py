from flask import Flask, abort, redirect, render_template, request, url_for

import modelo

app = Flask(__name__)


def mostrar_productos(producto=None, datos=None, error=None, estado=200):
    if datos is None:
        datos = producto if producto is not None else {
            "nombre": "",
            "cantidad": "",
        }

    return render_template(
        "productos.html",
        productos=modelo.listar(),
        producto=producto,
        datos=datos,
        error=error,
    ), estado


def leer_formulario():
    datos = {
        "nombre": request.form.get("nombre", "").strip(),
        "cantidad": request.form.get("cantidad", "").strip(),
    }

    if not 1 <= len(datos["nombre"]) <= 100:
        return datos, None, "El nombre debe tener entre 1 y 100 caracteres."

    try:
        cantidad = int(datos["cantidad"])
    except ValueError:
        return datos, None, "La cantidad debe ser un número entero."

    if not 0 <= cantidad <= 999999:
        return datos, None, "La cantidad debe estar entre 0 y 999999."

    return datos, cantidad, None


@app.get("/")
def inicio():
    return mostrar_productos()


@app.post("/productos")
def crear_producto():
    datos, cantidad, error = leer_formulario()
    if error:
        return mostrar_productos(datos=datos, error=error, estado=400)

    modelo.crear(datos["nombre"], cantidad)
    return redirect(url_for("inicio"), code=303)


@app.route("/productos/<int:producto_id>/editar", methods=["GET", "POST"])
def editar_producto(producto_id):
    producto = modelo.buscar(producto_id)
    if producto is None:
        abort(404)

    if request.method == "GET":
        return mostrar_productos(producto=producto)

    datos, cantidad, error = leer_formulario()
    if error:
        return mostrar_productos(producto, datos, error, estado=400)

    if modelo.actualizar(producto_id, datos["nombre"], cantidad) == 0:
        abort(404)

    return redirect(url_for("inicio"), code=303)


@app.post("/productos/<int:producto_id>/eliminar")
def eliminar_producto(producto_id):
    if modelo.eliminar(producto_id) == 0:
        abort(404)

    return redirect(url_for("inicio"), code=303)


if __name__ == "__main__":
    modelo.crear_tabla()
    app.run()