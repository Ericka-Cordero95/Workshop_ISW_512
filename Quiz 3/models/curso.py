from core.database import conexion

CONSULTA = """
    SELECT c.*, ca.nombre AS carrera FROM cursos c
    JOIN carreras ca ON ca.id = c.carrera_id
"""


def listar():
    with conexion() as db:
        return db.execute(CONSULTA + " ORDER BY c.nombre").fetchall()


def buscar(curso_id):
    with conexion() as db:
        return db.execute(CONSULTA + " WHERE c.id = ?", (curso_id,)).fetchone()


def carreras():
    with conexion() as db:
        return db.execute("SELECT * FROM carreras ORDER BY nombre").fetchall()


def guardar(curso_id, datos):
    with conexion() as db:
        if curso_id is None:
            db.execute("""
                INSERT INTO cursos (nombre, codigo, creditos, carrera_id)
                VALUES (:nombre, :codigo, :creditos, :carrera_id)
            """, datos)
            return True
        return db.execute("""
            UPDATE cursos SET nombre=:nombre, codigo=:codigo,
                creditos=:creditos, carrera_id=:carrera_id WHERE id=:id
        """, dict(datos, id=curso_id)).rowcount == 1


def eliminar(curso_id):
    with conexion() as db:
        return db.execute("DELETE FROM cursos WHERE id=?", (curso_id,)).rowcount == 1
