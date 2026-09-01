<?php
/*
====================================================
ARCHIVO: admin.php
CAPA: SEGURIDAD / CONTROL DE ROLES
FUNCIÓN: Protección de rutas administrativas
====================================================

Este módulo:
- Verifica que el usuario sea administrador
- Bloquea acceso a usuarios no autorizados
*/

include "auth.php";

/*
CONTROL DE PERMISOS POR ROL
Solo usuarios con rol "admin" pueden acceder
*/
if ($_SESSION['role'] !== "admin") {
    die("Acceso denegado");
}
?>
