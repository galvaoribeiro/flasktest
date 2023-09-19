from flask import Flask, request, render_template, redirect, url_for
from flask_sqlalchemy import SQLAlchemy
import os

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///uploads.db'  # Usando SQLite para simplicidade
app.config['UPLOAD_FOLDER'] = 'uploads'  # Pasta para armazenar os arquivos enviados
db = SQLAlchemy(app)

class Arquivo(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(255))
    informacoes = db.Column(db.String(255))

@app.route('/')
def index():
    arquivos = Arquivo.query.all()
    return render_template('index.html', arquivos=arquivos)

@app.route('/upload', methods=['POST'])
def upload():
    informacoes = request.form.get('informacoes')
    arquivos = request.files.getlist('arquivos')  # Obter lista de arquivos

    for arquivo in arquivos:
        if arquivo:
            nome_arquivo = arquivo.filename
            arquivo.save(os.path.join(app.config['UPLOAD_FOLDER'], nome_arquivo))

            novo_arquivo = Arquivo(nome=nome_arquivo, informacoes=informacoes)
            db.session.add(novo_arquivo)
            db.session.commit()

    return redirect(url_for('index'))

@app.route('/excluir/<int:id>', methods=['POST'])
def excluir(id):
    arquivo = Arquivo.query.get(id)
    if arquivo:
        os.remove(os.path.join(app.config['UPLOAD_FOLDER'], arquivo.nome))
        db.session.delete(arquivo)
        db.session.commit()

    return redirect(url_for('index'))

if __name__ == '__main__':
    with app.app_context():
        db.create_all()
    app.run(debug=True)
