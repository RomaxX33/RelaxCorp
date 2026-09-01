<?php
/*
====================================================
ARCHIVO: login.php
CAPA: AUTENTICACIÓN
FUNCIÓN: Inicio de sesión de usuarios del sistema
====================================================

Este módulo gestiona:
- Autenticación de usuarios
- Validación de credenciales
- Control de acceso por roles (admin / user)
- Reinicio diario de tiempo de uso
- Redirección según permisos
*/

include "../includes/db.php";
include "../includes/session.php";

$mensaje = "";

/*
 * MANEJO DE ERRORES POR URL (GET)
 */
if (isset($_GET['error'])) {

    if ($_GET['error'] === "timeout") {
        $mensaje = "⛔ Tiempo agotado";
    }

    if ($_GET['error'] === "notime") {
        $mensaje = "⛔ Sin tiempo disponible";
    }
}

/*
 * PROCESO DE LOGIN
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    /*
     * VALIDACIÓN BÁSICA DE CAMPOS
     */
    if ($username === "" || $password === "") {

        $mensaje = "Campos inválidos";

    /*
     * SEGURIDAD: BLOQUEO DE CARACTERES NO PERMITIDOS
     */
    } elseif (preg_match('/[\/\\\\\s]/', $password)) {

        $mensaje = "La contraseña contiene caracteres no permitidos";

    } else {

        /*
         * BÚSQUEDA DE USUARIO EN BASE DE DATOS
         */
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        /*
         * USUARIO NO ENCONTRADO
         */
        if ($result->num_rows !== 1) {

            $mensaje = "Usuario no existe";

        } else {

            $user = $result->fetch_assoc();

            /*
             * REINICIO DIARIO DE TIEMPO DE USO
             * --------------------------------
             * Garantiza 20 minutos diarios por usuario
             */
            $today = date("Y-m-d");

            if (!isset($user['last_reset']) || $user['last_reset'] !== $today) {

                $stmt = $conn->prepare("UPDATE users SET time_left=1200, last_reset=? WHERE id=?");
                $stmt->bind_param("si", $today, $user['id']);
                $stmt->execute();

                $user['time_left'] = 1200;
                $user['last_reset'] = $today;
            }

            /*
             * VALIDACIÓN DE PASSWORD
             */
            if (!password_verify($password, $user['password'])) {

                $mensaje = "Contraseña incorrecta";

            /*
             * BLOQUEO POR TIEMPO AGOTADO
             */
            } elseif ($user['time_left'] <= 0) {

                header("Location: /relaxcorp/auth/login.php?error=notime");
                exit;

            } else {

                /*
                 * INICIO DE SESIÓN SEGURO
                 */
                session_regenerate_id(true);

                $_SESSION['id'] = $user['id'];
                $_SESSION['user'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                /*
                 * ACTUALIZACIÓN DE ACTIVIDAD
                 */
                $stmt = $conn->prepare("UPDATE users SET last_login=NOW(), last_seen=NOW() WHERE id=?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();

                /*
                 * REDIRECCIÓN POR ROL
                 */
                if ($user['role'] === 'admin') {
                    header("Location: /relaxcorp/admin/dashboard.php");
                } else {
                    header("Location: /relaxcorp/user/dashboard.php");
                }

                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>
<body>
<div class="main-container">

<h1>Iniciar sesión</h1>

<?php if ($mensaje): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST">

    <input type="text" name="username" placeholder="Usuario" required><br><br>

    <input type="password" name="password" placeholder="Contraseña" required><br><br>

    <button type="submit">Entrar</button>

</form>

</div>
</body>
</html>
