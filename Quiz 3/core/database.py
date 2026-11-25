import sqlite3
from contextlib import closing, contextmanager
from pathlib import Path

RUTA = Path(__file__).resolve().parents[1] / "academico.db"


@contextmanager
def conexion():
    with closing(sqlite3.connect(RUTA)) as db:
        db.row_factory = sqlite3.Row
        db.execute("PRAGMA foreign_keys = ON")
        with db:
            yield db


def inicializar():
    # La version permite cargar los ejemplos una sola vez.
    with conexion() as db:
        db.execute("BEGIN IMMEDIATE")
        if db.execute("PRAGMA user_version").fetchone()[0]:
            return
        db.execute("CREATE TABLE carreras (id INTEGER PRIMARY KEY, nombre TEXT UNIQUE NOT NULL)")
        db.execute("""
            CREATE TABLE cursos (
                id INTEGER PRIMARY KEY,
                nombre TEXT NOT NULL,
                codigo TEXT NOT NULL UNIQUE COLLATE NOCASE,
                creditos INTEGER NOT NULL CHECK (creditos > 0),
                carrera_id INTEGER NOT NULL REFERENCES carreras(id)
            )
        """)
        db.execute("""
            CREATE TABLE matriculas (
                id INTEGER PRIMARY KEY,
                identificacion TEXT NOT NULL COLLATE NOCASE,
                estudiante TEXT NOT NULL,
                curso_id INTEGER REFERENCES cursos(id) ON DELETE SET NULL,
                codigo TEXT NOT NULL, curso TEXT NOT NULL,
                creditos INTEGER NOT NULL, carrera TEXT NOT NULL,
                fecha TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE (identificacion, curso_id)
            )
        """)
        db.executemany("INSERT INTO carreras VALUES (?, ?)", [
            (1, "Ingeniería del Software"), (2, "Administración")
        ])
        db.executemany("INSERT INTO cursos (nombre, codigo, creditos, carrera_id) VALUES (?, ?, ?, ?)", [
            ("Programación web", "WEB-101", 4, 1),
            ("Bases de datos", "BD-101", 4, 1),
            ("Administración general", "ADM-101", 3, 2)
        ])
        db.execute("PRAGMA user_version = 1")
