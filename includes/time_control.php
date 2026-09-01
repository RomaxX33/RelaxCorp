<?php
/*
====================================================
ARCHIVO: time_control.php
CAPA: LÓGICA DE NEGOCIO / CONTROL DE TIEMPO
FUNCIÓN: Gestión del tiempo de uso del sistema
====================================================

Este módulo implementa:
- Sistema de tiempo limitado por usuario (20 min/día)
- Persistencia en base de datos
- Sincronización entre sesión y servidor
- Bloqueo automático al agotarse el tiempo
*/

include __DIR__ . "/db.php";
include __DIR__ . "/session.php";

/*
VALIDACIÓN DE USUARIO AUTENTICADO
*/
if (!isset($_SESSION['id'])) {
    header("Location: /relaxcorp/index.php");
    exit;
}

$id = $_SESSION['id'];

/*
OBTENCIÓN DE DATOS DEL USUARIO
(time_left + reset diario)
*/
$stmt = $conn->prepare("SELECT time_left, last_reset FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

/*
RESET DIARIO AUTOMÁTICO
Cada día se reinician los 20 minutos de uso
*/
$today = date("Y-m-d");

if ($user['last_reset'] !== $today) {

    $stmt = $conn->prepare("UPDATE users SET time_left=1200, last_reset=? WHERE id=?");
    $stmt->bind_param("si", $today, $id);
    $stmt->execute();

    $_SESSION['time_left'] = 1200;
    $_SESSION['last_time_check'] = time();

    return;
}

/*
INICIALIZACIÓN DE CONTROL DE TIEMPO EN SESIÓN
*/
if (!isset($_SESSION['last_time_check'])) {
    $_SESSION['last_time_check'] = time();
    $_SESSION['time_left'] = $user['time_left'];
    return;
}

/*
CÁLCULO DE TIEMPO REAL TRANSCURRIDO
*/
$now = time();
$elapsed = $now - $_SESSION['last_time_check'];

$_SESSION['last_time_check'] = $now;

$new_time = $_SESSION['time_left'] - $elapsed;

if ($new_time < 0) $new_time = 0;

/*
ACTUALIZACIÓN EN BASE DE DATOS
*/
$stmt = $conn->prepare("UPDATE users SET time_left=? WHERE id=?");
$stmt->bind_param("ii", $new_time, $id);
$stmt->execute();

/*
ACTUALIZACIÓN EN SESIÓN
*/
$_SESSION['time_left'] = $new_time;

/*
BLOQUEO FINAL DEL SISTEMA
Si el tiempo llega a 0 → cierre de sesión automático
*/
if ($new_time <= 0) {
    session_unset();
    session_destroy();
    header("Location: /relaxcorp/auth/login.php?error=timeout");
    exit;
}
?>
