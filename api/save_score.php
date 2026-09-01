<?php
/*
====================================================
ARCHIVO: save_score.php
CAPA: API / BACKEND
FUNCIÓN: Registro de puntuaciones desde el frontend
====================================================

Este endpoint actúa como API REST básica del sistema RelaxCorp.

Es utilizado por los minijuegos (Phaser.js) para:
- Enviar puntuaciones finales del usuario
- Validar sesión activa
- Relacionar usuario + juego + score
- Guardar resultados en MySQL

Comunicación: AJAX (fetch JSON)
*/

include "../includes/db.php";
include "../includes/session.php";

/*
 * RESPUESTA EN FORMATO JSON
 * -------------------------
 * Este endpoint no devuelve HTML, sino JSON estructurado
 */
header("Content-Type: application/json");

/*
 * VALIDACIÓN DE SESIÓN
 * --------------------
 * Evita envíos de puntuaciones sin usuario autenticado
 */
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(["error" => "no auth"]);
    exit;
}

/*
 * LECTURA DE DATOS DESDE FRONTEND
 * -------------------------------
 * Se espera un JSON enviado desde Phaser.js mediante fetch()
 */
$data = json_decode(file_get_contents("php://input"), true);

/*
 * VALIDACIÓN DE FORMATO JSON
 */
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "invalid json"]);
    exit;
}

/*
 * EXTRACCIÓN Y VALIDACIÓN DE DATOS
 * --------------------------------
 * score: puntuación final del jugador
 * game_name: identificador del juego
 */
$score = intval($data['score'] ?? -1);
$game_name = $data['game'] ?? '';

if ($score < 0 || $game_name === '') {
    http_response_code(400);
    echo json_encode(["error" => "bad request"]);
    exit;
}

/*
 * OBTENCIÓN DE ID DEL JUEGO
 * -------------------------
 * Se traduce el nombre del juego a su ID en base de datos
 */
$stmt = $conn->prepare("SELECT id FROM games WHERE name=?");
$stmt->bind_param("s", $game_name);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    http_response_code(404);
    echo json_encode(["error" => "game not found"]);
    exit;
}

/*
 * ASIGNACIÓN DE VARIABLES FINALES
 */
$game_id = $game['id'];
$user_id = $_SESSION['id'];

/*
 * INSERCIÓN DE PUNTUACIÓN EN BASE DE DATOS
 * ----------------------------------------
 * Relación: usuario → juego → score
 */
$stmt = $conn->prepare("
    INSERT INTO scores (user_id, game_id, score)
    VALUES (?, ?, ?)
");

$stmt->bind_param("iii", $user_id, $game_id, $score);
$stmt->execute();

/*
 * RESPUESTA FINAL A FRONTEND
 */
echo json_encode([
    "status" => "ok",
    "score" => $score
]);
?>
