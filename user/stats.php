<?php
/*
====================================================
ARCHIVO: stats.php
CAPA: LÓGICA / ANALÍTICA
FUNCIÓN: Estadísticas del usuario
====================================================

Este módulo:
- Muestra mejores puntuaciones por juego
- Muestra últimas partidas jugadas
- Permite análisis básico del rendimiento del usuario
*/

include "../includes/auth.php";
include "../includes/db.php";

$user_id = $_SESSION['id'];

/*
=====================================
MEJOR PUNTUACIÓN POR JUEGO
=====================================
*/
$stmtBest = $conn->prepare("
    SELECT g.name, MAX(s.score) as best_score
    FROM scores s
    JOIN games g ON g.id = s.game_id
    WHERE s.user_id = ?
    GROUP BY g.name
");

$stmtBest->bind_param("i", $user_id);
$stmtBest->execute();
$bestResult = $stmtBest->get_result();

/*
=====================================
ÚLTIMAS PARTIDAS JUGADAS
=====================================
*/
$stmtLast = $conn->prepare("
    SELECT g.name, s.score, s.created_at
    FROM scores s
    JOIN games g ON g.id = s.game_id
    WHERE s.user_id = ?
    ORDER BY s.created_at DESC
    LIMIT 10
");

$stmtLast->bind_param("i", $user_id);
$stmtLast->execute();
$lastResult = $stmtLast->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis puntuaciones</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>

<body>
<div class="main-container">

<h1>Tus mejores puntuaciones</h1>

<!-- MEJORES RESULTADOS -->
<?php while ($row = $bestResult->fetch_assoc()): ?>
    <p>
        <b><?php echo $row['name']; ?>:</b>
        <?php echo $row['best_score']; ?>
    </p>
<?php endwhile; ?>

<hr>

<h1>Últimas partidas</h1>

<!-- HISTORIAL DE PARTIDAS -->
<?php while ($row = $lastResult->fetch_assoc()): ?>
    <p>
        <?php echo $row['name']; ?> |
        <?php echo $row['score']; ?> |
        <?php echo $row['created_at']; ?>
    </p>
<?php endwhile; ?>

<hr>

<!-- NAVEGACIÓN -->
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
