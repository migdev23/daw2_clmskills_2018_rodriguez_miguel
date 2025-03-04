# daw2_clmskills_2018_rodriguez_miguel

composer config --global process-timeout 3600     
(teniendo el .env en la raiz del proyecto)
(1) Instalacion de paquetes php:
composer install (instala depencias)

(2) Comando de despliegue (php): 
  composer start

(3 opcional) Si queremos hacerlo con nodejs para poder seguir programando y lanzar el watcher de ts etc... tenemos los siguientes comandos
  npm install (instala depencias)
  npm start (lanza el build de ts y el composer start)
