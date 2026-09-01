<?php
/*
====================================================
ARCHIVO: delete_user.php
CAPA: ADMIN / BACKEND
FUNCIÓN: Eliminación de usuarios del sistema
====================================================

Este módulo permite eliminar usuarios registrados en la base de datos.

Incluye:
- Validación de rol administrador
- Protección contra auto-eliminación
- Eliminación mediante ID seguro
*/

include "../includes/auth.php";
include "../includes/db.php";

/*
 * CONTROL DE ACCESO ADMIN
 */
if ($_SESSION['role'] !== "admin") {
    header("Location: /relaxcorp/user/dashboard.php");
    exit;
}

/*
 * OBTENCIÓN SEGURA DEL ID
 * -----------------------
 * Se sanitiza el parámetro GET para evitar inyección
 */
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID inválido");
}

/*
 * PROTECCIÓN CRÍTICA
 * ------------------
 * Evita que el administrador se elimine a sí mismo
 */
if ($id === $_SESSION['id']) {
    die("❌ No puedes eliminar tu propia cuenta de administrador.");
}

try {

    /*
     * ELIMINACIÓN DEL USUARIO
     * ----------------------
     * Se elimina el registro de la tabla users
     */
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;

} catch (Exception $e) {
    die("Error al eliminar usuario");
}
?>
