<?php
/*
====================================================
ARCHIVO: game2.php
CAPA: PRESENTACIÓN / JUEGOS
FUNCIÓN: Ejecución del minijuego Clicker
DEPENDENCIAS: auth.php, time_control.php, Phaser.js
====================================================

Este módulo:
- Controla sesión y tiempo de usuario
- Carga motor Phaser.js
- Ejecuta juego interactivo en navegador
- Envía puntuaciones al backend mediante API REST
*/

include __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/time_control.php";

/*
CONTROL DE TIEMPO
Bloqueo de acceso si el usuario ha agotado su sesión
*/
if ($_SESSION['time_left'] <= 0) {
    die("⛔ Sin tiempo disponible");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clicker - RelaxCorp</title>

    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">

    <!-- MOTOR DE JUEGOS PHASER.JS -->
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.80.0/dist/phaser.js"></script>

    <style>
        /*
        ESTILOS DE INTERFAZ DEL JUEGO
        */
        html, body {
            margin: 0;
            padding: 0;
        }

        #topbar {
            padding: 10px;
            background: #222;
        }

        h1, h2 {
            color: #00ffcc;
        }

        /*
        CENTRADO DEL CANVAS DEL JUEGO
        */
        #game-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        canvas {
            display: block;
        }
    </style>
</head>

<body>
<div class="main-container">

<!-- BARRA DE ESTADO DEL USUARIO -->
<div id="topbar">
    👤 <?php echo htmlspecialchars($_SESSION['user']); ?> |
    ⏳ <?php echo intval($_SESSION['time_left']); ?>s

    <a href="index.php"><button>Juegos</button></a>
    <a href="../user/dashboard.php"><button>Panel</button></a>
    <a href="../auth/logout.php"><button>Cerrar sesión</button></a>
</div>

<!-- CONTENEDOR DONDE PHASER RENDERIZA EL JUEGO -->
<div id="game-container"></div>

<script>
/*
====================================================
MOTOR DEL JUEGO (PHASER)
====================================================
Este juego:
- Genera objetivos aleatorios
- Gestiona puntuación en tiempo real
- Controla tiempo de partida (30s)
- Envía resultado al backend PHP
*/

class ClickerScene extends Phaser.Scene {

    create() {

        /*
        ESTADO INICIAL DEL JUEGO
        */
        this.score = 0;
        this.timeLeft = 30;

        this.text = this.add.text(50, 50, "Score: 0", {
            fontSize: "32px",
            color: "#fff"
        });

        this.timerText = this.add.text(50, 100, "Time: 30", {
            fontSize: "32px",
            color: "#fff"
        });

        /*
        OBJETIVO INTERACTIVO
        */
        this.target = this.add.circle(200, 300, 50, 0xff0000).setInteractive();

        this.target.on("pointerdown", () => {

            this.score += 10;
            this.text.setText("Score: " + this.score);

            // Reposicionamiento aleatorio del objetivo
            this.target.setPosition(
                Phaser.Math.Between(100, 700),
                Phaser.Math.Between(150, 500)
            );
        });

        /*
        SISTEMA DE TIEMPO DEL JUEGO
        */
        this.time.addEvent({
            delay: 1000,
            loop: true,
            callback: () => {

                this.timeLeft--;
                this.timerText.setText("Time: " + this.timeLeft);

                if (this.timeLeft <= 0) {
                    this.endGame();
                }
            }
        });
    }

    /*
    FINAL DEL JUEGO
    - Detiene interacción
    - Envía puntuación al backend
    - Muestra pantalla final
    */
    endGame() {

        fetch("/relaxcorp/api/save_score.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                score: this.score,
                game: "clicker"
            })
        });

        this.scene.pause();

        this.add.text(250, 250, "FIN", {
            fontSize: "64px",
            color: "#00ff00"
        });
    }
}

/*
INICIALIZACIÓN DEL JUEGO PHASER
*/
new Phaser.Game({
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    parent: "game-container",
    scene: ClickerScene
});
</script>

</div>
</body>
</html>
