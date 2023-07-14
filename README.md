1.	Arquitectura
 
2.	Instalación del Docker Compose
mkdir -p ~/.docker/cli-plugins/
curl -SL https://github.com/docker/compose/releases/download/v2.3.3/docker-compose-linux-x86_64 -o ~/.docker/cli-plugins/docker-compose
chmod +x ~/.docker/cli-plugins/docker-compose
docker compose version
mkdir ~/compose-demo
cd ~/compose-demo
3.	Configuración del mongoDB
Creamos dos Carpetas, la primera para la base de datos con data Cruda y la segunda con la data Limpia.
Archivo Docker-compose.yml
version: '3'
services:

## Router
  router01:
    image: mongo
    container_name: router-01
    command: mongos --port 27017 --configdb rs-config-server/configsvr01:27017,configsvr02:27017,configsvr03:27017 --bind_ip_all
    ports:
      - 27117:27017
    restart: always
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_router01_db:/data/db
      - mongodb_cluster_router01_config:/data/configdb
  router02:
    image: mongo
    container_name: router-02
    command: mongos --port 27017 --configdb rs-config-server/configsvr01:27017,configsvr02:27017,configsvr03:27017 --bind_ip_all
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_router02_db:/data/db
      - mongodb_cluster_router02_config:/data/configdb
    ports:
      - 27118:27017
    restart: always
    links:
      - router01

## Config Servers
  configsvr01:
    image: mongo
    container_name: mongo-config-01 
    command: mongod --port 27017 --configsvr --replSet rs-config-server
    volumes:
      - ./scripts:/scripts 
      - mongodb_cluster_configsvr01_db:/data/db
      - mongodb_cluster_configsvr01_config:/data/configdb
    ports:
      - 27119:27017
    restart: always
    links:
      - shard01-a
      - shard02-a
      - shard03-a
  configsvr02:
    image: mongo
    container_name: mongo-config-02 
    command: mongod --port 27017 --configsvr --replSet rs-config-server
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_configsvr02_db:/data/db
      - mongodb_cluster_configsvr02_config:/data/configdb
    ports:
      - 27120:27017
    restart: always
    links:
      - configsvr01
  configsvr03:
    image: mongo
    container_name: mongo-config-03 
    command: mongod --port 27017 --configsvr --replSet rs-config-server
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_configsvr03_db:/data/db
      - mongodb_cluster_configsvr03_config:/data/configdb
    ports:
      - 27121:27017
    restart: always
    links:
      - configsvr02

## Shards
  ## Shards 01
   
  shard01-a:
    image: mongo
    container_name: shard-01-node-a
    command: mongod --port 27017 --shardsvr --replSet rs-shard-01
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard01_a_db:/data/db
      - mongodb_cluster_shard01_a_config:/data/configdb
    ports:
      - 27122:27017
    restart: always
    links:
      - shard01-b
      - shard01-c
  shard01-b:
    image: mongo
    container_name: shard-01-node-b
    command: mongod --port 27017 --shardsvr --replSet rs-shard-01
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard01_b_db:/data/db
      - mongodb_cluster_shard01_b_config:/data/configdb
    ports:
      - 27123:27017
    restart: always
  shard01-c:
    image: mongo
    container_name: shard-01-node-c
    command: mongod --port 27017 --shardsvr --replSet rs-shard-01
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard01_c_db:/data/db
      - mongodb_cluster_shard01_c_config:/data/configdb
    ports:
      - 27124:27017
    restart: always

  ## Shards 02
  shard02-a:
    image: mongo
    container_name: shard-02-node-a
    command: mongod --port 27017 --shardsvr --replSet rs-shard-02
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard02_a_db:/data/db
      - mongodb_cluster_shard02_a_config:/data/configdb
    ports:
      - 27125:27017
    restart: always
    links:
      - shard02-b
      - shard02-c
  shard02-b:
    image: mongo
    container_name: shard-02-node-b
    command: mongod --port 27017 --shardsvr --replSet rs-shard-02
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard02_b_db:/data/db
      - mongodb_cluster_shard02_b_config:/data/configdb
    ports:
      - 27126:27017
    restart: always
  shard02-c:
    image: mongo
    container_name: shard-02-node-c
    command: mongod --port 27017 --shardsvr --replSet rs-shard-02
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard02_c_db:/data/db
      - mongodb_cluster_shard02_c_config:/data/configdb
    ports:
      - 27127:27017
    restart: always

  ## Shards 03
  shard03-a:
    image: mongo
    container_name: shard-03-node-a
    command: mongod --port 27017 --shardsvr --replSet rs-shard-03
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard03_a_db:/data/db
      - mongodb_cluster_shard03_a_config:/data/configdb
    ports:
      - 27128:27017
    restart: always
    links:
      - shard03-b
      - shard03-c
  shard03-b:
    image: mongo
    container_name: shard-03-node-b
    command: mongod --port 27017 --shardsvr --replSet rs-shard-03
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard03_b_db:/data/db
      - mongodb_cluster_shard03_b_config:/data/configdb
    ports:
      - 27129:27017
    restart: always
  shard03-c:
    image: mongo
    container_name: shard-03-node-c
    command: mongod --port 27017 --shardsvr --replSet rs-shard-03
    volumes:
      - ./scripts:/scripts
      - mongodb_cluster_shard03_c_db:/data/db
      - mongodb_cluster_shard03_c_config:/data/configdb
    ports:
      - 27130:27017
    restart: always

volumes:
  mongodb_cluster_router01_db:
  mongodb_cluster_router01_config:
  
  mongodb_cluster_router02_db:
  mongodb_cluster_router02_config:
  
  mongodb_cluster_configsvr01_db:
  mongodb_cluster_configsvr01_config:
  
  mongodb_cluster_configsvr02_db:
  mongodb_cluster_configsvr02_config:
  
  mongodb_cluster_configsvr03_db:
  mongodb_cluster_configsvr03_config:
  
  mongodb_cluster_shard01_a_db:
  mongodb_cluster_shard01_a_config:
  
  mongodb_cluster_shard01_b_db:
  mongodb_cluster_shard01_b_config:
  
  mongodb_cluster_shard01_c_db:
  mongodb_cluster_shard01_c_config:
  
  mongodb_cluster_shard02_a_db:
  mongodb_cluster_shard02_a_config:
  
  mongodb_cluster_shard02_b_db:
  mongodb_cluster_shard02_b_config:
  
  mongodb_cluster_shard02_c_db:
  mongodb_cluster_shard02_c_config:
  
  mongodb_cluster_shard03_a_db:
  mongodb_cluster_shard03_a_config:
  
  mongodb_cluster_shard03_b_db:
  mongodb_cluster_shard03_b_config:
  
  mongodb_cluster_shard03_c_db:
  mongodb_cluster_shard03_c_config:


Corremos los siguientes comandos para activar la replicación y el sharding
docker-compose up -d

docker-compose exec configsvr01 sh -c "mongosh < /scripts/init-configserver.js"

docker-compose exec shard01-a sh -c "mongosh < /scripts/init-shard01.js"
docker-compose exec shard02-a sh -c "mongosh < /scripts/init-shard02.js"
docker-compose exec shard03-a sh -c "mongosh < /scripts/init-shard03.js"


docker-compose exec router01 sh -c "mongosh < /scripts/init-router.js"


docker-compose exec router01 mongosh --port 27017

-----Enable sharding for database `MyDatabase`
sh.enableSharding("MyDatabase")

----Setup shardingKey for collection `MyCollection`**
db.adminCommand( { shardCollection: "MyDatabase.MyCollection", key: { oemNumber: "hashed", zipCode: 1, supplierId: 1 } } )


 
 
4.	Configuración del ETL
 
 
 


