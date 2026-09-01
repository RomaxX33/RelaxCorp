<?php
/*
====================================================
ARCHIVO: db.php
CAPA: INFRAESTRUCTURA / BASE DE DATOS
FUNCIÓN: Conexión centralizada a MySQL
====================================================

Este módulo:
- Establece conexión con la base de datos MySQL
- Es utilizado por toda la aplicación
- Centraliza la configuración de conexión
*/

/*
CONFIGURACIÓN DE LA BASE DE DATOS

En el entorno local, estas variables pueden definirse
mediante variables de entorno.

NO almacenar contraseñas reales en este archivo.
*/

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'relaxcorp';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASSWORD') ?: '';

/*
CREACIÓN DE CONEXIÓN MYSQLI
*/
$conn = new mysqli($host, $user, $pass, $db);

/*
CONTROL DE ERRORES DE CONEXIÓN
*/
if ($conn->connect_error) {
    die("Error de conexión a la base de datos. " . $conn->connect_error);
    
}
?>
