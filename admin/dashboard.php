<?php
/*
====================================================
ARCHIVO: dashboard.php
CAPA: ADMIN / FRONTEND + BACKEND
FUNCIÓN: Panel de administración de usuarios
====================================================

Este módulo permite al administrador:
- Visualizar usuarios registrados
- Acceder a creación de usuarios
- Eliminar usuarios existentes

Actúa como panel central de gestión del sistema.
*/

include "../includes/auth.php";
include "../includes/db.php";

/*
 * CONTROL DE ACCESO ADMIN
 * -----------------------
 * Protege el panel para uso exclusivo de administradores
 */
if ($_SESSION['role'] !== "admin") {
    header("Location: /relaxcorp/user/dashboard.php");
    exit;
}

/*
 * OBTENCIÓN DE USUARIOS
 * ---------------------
 * Se cargan todos los usuarios de la base de datos
 */
$result = $conn->query(
    "SELECT id, username, role, created_at FROM users ORDER BY created_at ASC"
);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>

    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>
<body>
<div class="main-container">

<h1>🛠  Panel de administrador</h1>

<a href="../auth/logout.php">
    <button>Cerrar Sesión</button>
</a>

<br><br>

<a href="create_user.php">
    <button>➕ Crear usuario</button>
</a>

<hr>

<h2>Usuarios</h2>
<!--
TABLA DE USUARIOS
-----------------
Muestra todos los usuarios registrados en el sistema.
Permite acciones de eliminación (excepto sobre el propio admin activo).
-->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Fecha creación</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

    <?php while ($user = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?php echo $user['id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($user['username']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($user['role']); ?>
            </td>

            <td>
                <?php echo $user['created_at']; ?>
            </td>

            <td>

            <?php if ($user['id'] != $_SESSION['id']): ?>

		<!-- Eliminación segura de usuarios mediante confirmación -->
                <a class="btn-delete"
                   href="delete_user.php?id=<?php echo $user['id']; ?>"
                   onclick="return confirm('¿Eliminar usuario?')">
                    ❌ Eliminar
                </a>

            <?php else: ?>

                —
                
            <?php endif; ?>

            </td>

        </tr>

    <?php endwhile; ?>

    </tbody>
</table>

</div>
</body>
</html>
