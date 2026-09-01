<?php
/*
====================================================
ARCHIVO: create_user.php
CAPA: ADMIN / BACKEND
FUNCIÓN: Creación de nuevos usuarios
====================================================

Este módulo permite al administrador crear usuarios en el sistema RelaxCorp.

Incluye:
- Validación de seguridad de contraseñas
- Verificación de existencia de usuario
- Cifrado de contraseña con password_hash()
- Inserción en base de datos MySQL

Requiere sesión activa de administrador.
*/

include "../includes/auth.php";
include "../includes/db.php";

/*
 * CONTROL DE ACCESO
 * -----------------
 * Solo los usuarios con rol "admin" pueden acceder a este módulo.
 */
if ($_SESSION['role'] !== "admin") {
    header("Location: /relaxcorp/user/dashboard.php");
    exit;
}

$mensaje = "";

/*
 * PROCESO DE CREACIÓN DE USUARIO
 * ------------------------------
 * Se ejecuta cuando se envía el formulario POST "crear"
 */
if (isset($_POST['crear'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    /*
     * VALIDACIONES DE SEGURIDAD
     * -------------------------
     * - Longitud mínima: 8 caracteres
     * - Al menos 1 mayúscula
     * - Al menos 1 número
     * - Sin espacios ni barras
     */

    if (strlen($password) < 8) {

        $mensaje = "La contraseña debe tener al menos 8 caracteres";

    } elseif (
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {

        $mensaje = "La contraseña debe contener una mayúscula y un número";

    } elseif (
        preg_match('/[\/\\\\\s]/', $password)
    ) {

        $mensaje = "La contraseña no puede contener espacios ni barras";

    } elseif ($username === "" || $password === "") {

        $mensaje = "Campos inválidos";

    } else {

        /*
         * VERIFICACIÓN DE USUARIO EXISTENTE
         * ----------------------------------
         * Evita duplicados en la base de datos
         */
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $mensaje = "El usuario ya existe";

        } else {

            /*
             * CREACIÓN DE USUARIO
             * -------------------
             * La contraseña se almacena cifrada usando BCRYPT
             */
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->bind_param("ss", $username, $hash);

            if ($stmt->execute()) {
                $mensaje = "Usuario creado correctamente";
            } else {
                $mensaje = "Error al crear el usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear usuario</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>
<body>
<div class="main-container">

<h1>Crear usuario</h1>

<?php if ($mensaje): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Usuario" required><br><br>

    <input type="password"
           name="password"
           placeholder="Contraseña"
           required><br><br>

    <button type="submit" name="crear">Crear</button>
</form>

<br>

<a href="dashboard.php">Volver</a>

</div>
</body>
</html>
