<?php
/*
====================================================
ARCHIVO: index.php
CAPA: PRESENTACIÓN / ENTRADA DEL SISTEMA
FUNCIÓN: Página inicial de acceso a RelaxCorp
====================================================

Este módulo actúa como:
- Punto de entrada principal de la aplicación
- Redirección automática si el usuario ya está autenticado
- Pantalla de login para usuarios no autenticados
*/
session_start();

/*
REDIRECCIÓN AUTOMÁTICA SI YA EXISTE SESIÓN ACTIVA
Evita que un usuario logueado vuelva al login
*/
if (isset($_SESSION['user'])) {
    header("Location: /relaxcorp/user/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RelaxCorp</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>

<body>
<div class="main-container">

<!-- PRESENTACIÓN DEL SISTEMA -->
<h1>Bienvenido a RelaxCorp</h1>

<p>
Plataforma de juegos con control de tiempo.<br>
<strong>Gestiona tu tiempo, mejora tu rendimiento.</strong>
</p>

<!-- FORMULARIO DE AUTENTICACIÓN -->
<h2>Login</h2>

<form method="POST" action="auth/login.php">
    <input type="text" name="username" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
</form>

</div>
</body>
</html>
