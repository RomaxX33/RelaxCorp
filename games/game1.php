<?php
/*
====================================================
ARCHIVO: game1.php
CAPA: PRESENTACIÓN / JUEGOS
FUNCIÓN: Carga del juego Solitario (Klondike)
DEPENDENCIAS: auth.php, time_control.php
====================================================

Este módulo:
- Valida sesión de usuario
- Controla tiempo de uso disponible
- Carga el minijuego Solitario mediante iframe
- Mantiene navegación dentro del sistema RelaxCorp
*/

include __DIR__ . "/../includes/auth.php";
include __DIR__ . "/../includes/time_control.php";

/*
CONTROL DE ACCESO POR TIEMPO
Si el usuario no tiene tiempo disponible, se bloquea el acceso
*/
if ($_SESSION['time_left'] <= 0) {
    die("⛔ Sin tiempo disponible");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solitario - RelaxCorp</title>

    <link rel="stylesheet" href="/relaxcorp/assets/css/style.css">

    <style>
        /*
        ESTILOS LOCALES DEL CONTENEDOR DE JUEGO
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
    </style>
</head>

<body>
<div class="main-container">

<!-- BARRA DE ESTADO DEL USUARIO -->
<div id="topbar">
    👤 <?php echo htmlspecialchars($_SESSION['user']); ?> |
    ⏳ <?php echo intval($_SESSION['time_left']); ?>s

    <!-- NAVEGACIÓN DEL SISTEMA -->
    <a href="index.php"><button>Juegos</button></a>
    <a href="../user/dashboard.php"><button>Panel</button></a>
    <a href="../auth/logout.php"><button>Cerrar sesión</button></a>
</div>

<!--
INTEGRACIÓN DEL JUEGO
El solitario se ejecuta como aplicación independiente en iframe
Esto permite separar lógica de juego y sistema principal
-->
<iframe
    src="solitario/index.html"
    width="100%"
    height="619"
    style="
    border: none;
    display: block;
    overflow: hidden;
    background: transparent;
    ">
</iframe>

</div>
</body>
</html>
