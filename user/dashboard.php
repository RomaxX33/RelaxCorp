<?php
/*
====================================================
ARCHIVO: dashboard.php
CAPA: PRESENTACIÓN / USUARIO
FUNCIÓN: Panel principal del usuario
====================================================

Este módulo:
- Muestra información del usuario autenticado
- Muestra tiempo restante de uso
- Proporciona acceso a juegos, estadísticas y rankings
*/

include __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/time_control.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
    <meta charset="UTF-8">
    <title>Dashboard - RelaxCorp</title>
</head>

<body>
<div class="main-container">

<!-- SALUDO PERSONALIZADO -->
<h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?></h1>

<!-- ESTADO DEL SISTEMA: TIEMPO RESTANTE -->
<p>⏳ Te quedan <?php echo intval($_SESSION['time_left']); ?> s de descanso</p>

<br>

<!-- NAVEGACIÓN PRINCIPAL -->
<a href="../games/index.php">
    <button>Ir a juegos</button>
</a>

<br><br>

<a href="stats.php">
    <button>Mis puntuaciones</button>
</a>

<br><br>

<!-- RANKINGS POR JUEGO -->
<a href="ranking_game.php?game=solitario">
    <button>Ranking Solitario</button>
</a>

<a href="ranking_game.php?game=clicker">
    <button>Ranking Clicker</button>
</a>

<br><br>

<a href="../auth/logout.php">
    <button>Cerrar sesión</button>
</a>

</div>
</body>
</html>
