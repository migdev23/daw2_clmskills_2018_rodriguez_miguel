-- Crear un nuevo usuario con los mismos privilegios que root
CREATE USER 'u_skills'@'%' IDENTIFIED BY '2025_skills';
GRANT ALL PRIVILEGES ON *.* TO 'u_skills'@'%' WITH GRANT OPTION;

-- También permitir conexiones locales
CREATE USER 'u_skills'@'localhost' IDENTIFIED BY '2025_skills';
GRANT ALL PRIVILEGES ON *.* TO 'u_skills'@'localhost' WITH GRANT OPTION;

-- Eliminar el usuario root (esto puede fallar si MariaDB aún lo necesita)
DROP USER IF EXISTS 'root'@'localhost';
DROP USER IF EXISTS 'root'@'%';

FLUSH PRIVILEGES;
