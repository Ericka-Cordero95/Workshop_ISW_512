import sqlite3
from contextlib import closing
from pathlib import Path

RUTA_BD = Path(__file__).with_name("productos.db")


def ejecutar(sql, parametros=(), *, consultar=False):
    with closing(sqlite3.connect(RUTA_BD)) as conexion:
        conexion.row_factory = sqlite3.Row

        with conexion:
            cursor = conexion.execute(sql, parametros)
            if consultar:
                return cursor.fetchall()

            return cursor.rowcount


def crear_tabla():
    ejecutar("""
        CREATE TABLE IF NOT EXISTS productos (
            id INTEGER PRIMARY KEY,
            nombre TEXT NOT NULL CHECK (length(nombre) BETWEEN 1 AND 100),
            cantidad INTEGER NOT NULL CHECK (cantidad BETWEEN 0 AND 999999)
        )
    """)


def listar():
    return ejecutar(
        "SELECT id, nombre, cantidad FROM productos ORDER BY id",
        consultar=True,
    )


def buscar(producto_id):
    productos = ejecutar(
        "SELECT id, nombre, cantidad FROM productos WHERE id = ?",
        (producto_id,),
        consultar=True,
    )
    return productos[0] if productos else None


def crear(nombre, cantidad):
    ejecutar(
        "INSERT INTO productos (nombre, cantidad) VALUES (?, ?)",
        (nombre, cantidad),
    )


def actualizar(producto_id, nombre, cantidad):
    return ejecutar(
        "UPDATE productos SET nombre = ?, cantidad = ? WHERE id = ?",
        (nombre, cantidad, producto_id),
    )


def eliminar(producto_id):
    return ejecutar(
        "DELETE FROM productos WHERE id = ?",
        (producto_id,),
    )