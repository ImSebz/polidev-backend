CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    celular VARCHAR(20) NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(50) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    tipo_programa VARCHAR(100) NOT NULL,
    modalidad VARCHAR(50) NOT NULL,
    sede VARCHAR(50) NOT NULL,
    programa VARCHAR(100) NOT NULL,
    contacto_preferido VARCHAR(50) NOT NULL,
    tratamiento_datos TINYINT(1) NOT NULL,
    acepta_terminos TINYINT(1) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
