<?php
/*
====================================================
ARCHIVO: ranking_game.php
CAPA: LÓGICA / ESTADÍSTICAS
FUNCIÓN: Ranking global por juego
====================================================

Este módulo:
- Obtiene el ranking de jugadores por juego
- Ordena puntuaciones de mayor a menor
- Muestra TOP 10 de usuarios
- Usa agregación SQL (MAX score)
*/

include "../includes/auth.php";
include "../includes/db.php";

/*
OBTENCIÓN DEL JUEGO DESDE URL
*/
$game = $_GET['game'] ?? '';

/*
CONSULTA SQL DE RANKING
- JOIN entre users, scores y games
- Agrupación por usuario
- Orden descendente por mejor puntuación
*/
$stmt = $conn->prepare("
    SELECT u.username, MAX(s.score) as best_score
    FROM scores s
    JOIN users u ON u.id = s.user_id
    JOIN games g ON g.id = s.game_id
    WHERE g.name = ?
    GROUP BY s.user_id
    ORDER BY best_score DESC
    LIMIT 10
");

$stmt->bind_param("s", $game);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ranking por juego</title>
    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">
</head>

<body>
<div class="main-container">

<h1>🏆 Ranking: <?php echo htmlspecialchars($game); ?></h1>

<!-- LISTADO DE TOP PLAYERS -->
<?php $pos = 1; ?>
<?php while ($row = $result->fetch_assoc()): ?>
    <p>
        #<?php echo $pos++; ?>
        <b><?php echo $row['username']; ?></b>
        - <?php echo $row['best_score']; ?>
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
