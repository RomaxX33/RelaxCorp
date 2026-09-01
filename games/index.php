<?php
include __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/time_control.php";

$time = $_SESSION['time_left'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juegos - RelaxCorp</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>
<body>
<div class="main-container">

<h1>Lista de juegos</h1>

<p>⏳ Te quedan <?php echo intval($_SESSION['time_left']); ?>s de descanso</p>

<hr>

<h2>Juego 1 - Solitario</h2>

<?php if ($time > 0): ?>
    <a href="game1.php">
        <button>Jugar</button>
    </a>
<?php else: ?>
    <p>⛔ Sin tiempo disponible</p>
<?php endif; ?>

<hr>

<h2>Juego 2 - Clicker</h2>

<?php if ($time > 0): ?>
    <a href="game2.php">
        <button>Jugar</button>
    </a>
<?php else: ?>
    <p>⛔ Sin tiempo disponible</p>
<?php endif; ?>

<hr>

<a href="../user/dashboard.php">
    <button>Volver al panel de control</button>
</a>

<br><br>

<a href="../auth/logout.php">
    <button>Cerrar sesión</button>
</a>

</div>
</body>
</html>
