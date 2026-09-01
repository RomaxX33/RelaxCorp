<?php
/*
====================================================
ARCHIVO: session.php
CAPA: INFRAESTRUCTURA / SESIONES
FUNCIÓN: Inicialización de sesión segura
====================================================

Este módulo:
- Inicia sesión PHP
- Regenera ID de sesión para evitar hijacking
*/

session_start();

/*
SEGURIDAD:
Regeneración del ID de sesión en cada carga
para evitar ataques de fijación de sesión
*/
session_regenerate_id(true);
?>
