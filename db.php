<?php
$db = new SQLite3(__DIR__ . '/facturacion.sqlite');

// Crear tablas si no existen
$db->exec('CREATE TABLE IF NOT EXISTS clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    nit TEXT NOT NULL,
    telefono TEXT,
    correo TEXT
)');

$db->exec('CREATE TABLE IF NOT EXISTS facturas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_id INTEGER NOT NULL,
    fecha TEXT NOT NULL,
    total REAL NOT NULL,
    FOREIGN KEY(cliente_id) REFERENCES clientes(id)
)');

$db->exec('CREATE TABLE IF NOT EXISTS factura_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    factura_id INTEGER NOT NULL,
    producto TEXT NOT NULL,
    cantidad INTEGER NOT NULL,
    precio REAL NOT NULL,
    FOREIGN KEY(factura_id) REFERENCES facturas(id)
)');
?>
