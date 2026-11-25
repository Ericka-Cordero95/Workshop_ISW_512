from flask import Flask

from controllers.cursos import cursos
from controllers.matricula import matricula
from core.database import inicializar

app = Flask(__name__, template_folder="views")
app.register_blueprint(cursos)
app.register_blueprint(matricula)
inicializar()

if __name__ == "__main__":
    app.run()
