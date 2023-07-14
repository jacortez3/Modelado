from flask import Flask, request, jsonify
from pymongo import MongoClient
from bson import json_util
import requests
import json
from bson.objectid import ObjectId
from flask_cors import CORS
try:
    import carga
except:
    pass

app = Flask(__name__)
# Conexión a la base de datos MongoDB
client = MongoClient("mongodb://localhost:27117")
CORS(app)
# Ruta para obtener todos los documentos


@app.route('/api/data', methods=['GET'])
def get_all_data():
    db = client.MyDatabase  # Nombre de la base de datos
    collection = db.MyCollection  # Nombre de la colección
    # Obtener los primeros 5 documentos
    data = list(collection.find().limit(2))
    serialized_data = json_util.dumps(data)

    return serialized_data, 200
    # data = list(collection.find().limit(5))  # Obtener todos los documentos
    # return jsonify(data), 200

# Ruta para obtener un documento por su ID


@app.route('/api/data/<id>', methods=['GET'])
def get_data(id):
    db = client.mydatabase
    collection = db.mycollection
    data = collection.find_one({'_id': id})
    if data:
        return jsonify(data), 200
    else:
        return 'Documento no encontrado', 404

# Ruta para crear un nuevo documento


@app.route('/api/data', methods=['POST'])
def create_data():
    db = client.mydatabase
    collection = db.mycollection
    new_data = request.json
    result = collection.insert_one(new_data)
    return str(result.inserted_id), 201

# Ruta para actualizar un documento existente


@app.route('/api/data/<id>', methods=['PUT'])
def update_data(id):
    db = client.origen
    collection = db.test
    updated_data = request.json

    result = collection.update_one(
        {"_id": ObjectId(id)}, {'$set': updated_data})
    print(result)
    if result.modified_count == 1:
        return 'Documento actualizado', 200
    else:
        return 'Documento no encontrado', 404

# Ruta para eliminar un documento por su ID


@app.route('/api/data/<id>', methods=['DELETE'])
def delete_data(id):
    db = client.mydatabase
    collection = db.mycollection
    result = collection.delete_one({'_id': id})
    if result.deleted_count == 1:
        return 'Documento eliminado', 200
    else:
        return 'Documento no encontrado', 404


@app.route('/api/load', methods=['POST'])
def load_data():
    client2 = MongoClient("mongodb://localhost:27217")
    db2 = client2.Databse-JC
    destination_collection = db2.Colection-JC

    # data_to_load = request.get_json(force=True)
    # Realiza las operaciones de carga en la base de datos de destino
    data_to_load = request.get_json(force=True)

    cleaned_data = []
    for item in data_to_load:
        try:
            item['_id'] = str(ObjectId())  # Convertir ObjectId a cadena
            cleaned_data.append(item)
        except json.JSONDecodeError as e:
            print(f"Error de formato JSON en el documento: {item}")
            continue

    print(data_to_load)

    # Realiza las operaciones de carga en la base de datos de destino
    for item in cleaned_data:
        destination_collection.insert_one(item)

    """ for item in data_to_load:
        transformed_item = carga.transform_data(item)
        transformed_data.append(transformed_item) """

    # Carga los datos en PostgreSQL
    carga.insert_data_mysql(cleaned_data)

    # Guarda los datos en un archivo CSV
    carga.save_data_csv(cleaned_data)

    # return 'Datos cargados en la base de datos de destino', 201
    return item, 201


@app.errorhandler(404)
def not_found(error=None):
    message = {
        'message': 'Resource Not Found ' + request.url,
        'status': 404
    }
    response = jsonify(message)
    response.status_code = 404
    return response


@app.route('/')
def hello():
    return 'El servidor está en funcionamiento'


if __name__ == '__main__':
    app.run(debug=True, host="0.0.0.0", port=5000)
