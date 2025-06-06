CREATE DATABASE victores2;

USE victores2;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Casa', 'Terreno') NOT NULL,
    location TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    size DECIMAL(10,2),
    city VARCHAR(100) NOT NULL,
    neighborhood VARCHAR(100) NOT NULL,
    contact VARCHAR(100) NOT NULL,
    image longblob NOT NULL,
    agent_id INT NOT NULL,
    FOREIGN KEY (agent_id) REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE client_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    client_name VARCHAR(100),
    client_email VARCHAR(100),
    client_phone VARCHAR(15),
    FOREIGN KEY (property_id) REFERENCES properties(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);