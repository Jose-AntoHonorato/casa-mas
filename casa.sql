CREATE DATABASE CasaDB;
USE CasaDB;

CREATE TABLE Casas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Descasa TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    municipio VARCHAR(100) NOT NULL,
    barrio VARCHAR(100) NOT NULL,
    tamano VARCHAR(50) NOT NULL,
    habitaciones INT(10) NOT NULL,
    amueblada ENUM('si', 'no') NOT NULL,
    lat DECIMAL(10,8),
    lng DECIMAL(11,8),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
