<?php

require_once "inc/funciones.php";
$jugador = obtenerNombre();

do {
    echo "\nBienvenido " . $jugador . "\n";
    $puntosJg = jugar($jugador, cargarBaraja($jugador, true));
    $puntosIA = jugarIA("Banca", cargarBaraja("Banca", false), $puntosJg);
    echo comprobarGanador($puntosJg, $puntosIA, $jugador);
    do {
        echo "\n¿Desea Repetir o Abandonar el juego? (r/a): ";
        $entrada = strtolower(trim(fgets(STDIN)));
    } while ($entrada != 'r' && $entrada != 'a');
} while ($entrada == 'r');

echo "\nHasta Pronto\n";
