from core.database import conexion


def registrar(identificacion, estudiante, curso_id):
    # Copia los datos vigentes del curso para conservar el comprobante.
    with conexion() as db:
        cursor = db.execute("""
            INSERT INTO matriculas (identificacion, estudiante, curso_id, codigo, curso, creditos, carrera)
            SELECT ?, ?, c.id, c.codigo, c.nombre, c.creditos, ca.nombre
            FROM cursos c JOIN carreras ca ON ca.id = c.carrera_id WHERE c.id = ?
        """, (identificacion, estudiante, curso_id))
        if cursor.rowcount:
            return cursor.lastrowid


def buscar(numero):
    with conexion() as db:
        return db.execute("SELECT * FROM matriculas WHERE id=?", (numero,)).fetchone()
