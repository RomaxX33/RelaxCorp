<?php
/*
====================================================
ARCHIVO: logout.php
CAPA: AUTENTICACIÓN
FUNCIÓN: Cierre de sesión del usuario
====================================================

Este módulo se encarga de:
- Finalizar sesión activa
- Eliminar datos de sesión del servidor
- Redirigir al login inicial
*/

session_start();

/*
 * DESTRUCCIÓN COMPLETA DE SESIÓN
 */
session_destroy();

/*
 * REDIRECCIÓN A PÁGINA PRINCIPAL
 */
header("Location: /relaxcorp/index.php");
exit;
?>
