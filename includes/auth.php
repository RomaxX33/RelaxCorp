<?php
/*
====================================================
ARCHIVO: auth.php
CAPA: SEGURIDAD / AUTENTICACIÓN
FUNCIÓN: Protección de rutas privadas
====================================================

Este módulo:
- Verifica si el usuario tiene sesión activa
- Protege páginas privadas del sistema
- Redirige si no hay autenticación válida
*/

include __DIR__ . "/session.php";

/*
VALIDACIÓN DE SESIÓN ACTIVA
Si no existe usuario logueado → acceso denegado
*/
if (!isset($_SESSION['user'])) {
    header("Location: /relaxcorp/index.php");
    exit;
}
?>
