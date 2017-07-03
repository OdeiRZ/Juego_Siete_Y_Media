<?php

/**
 * Programa que simula la lógica del juego de cartas de las siete y media.
 *
 * El sistema permite interactuar con la máquina mientras sacamos cartas de forma aleatorias y obtenemos una puntuación
 * que intentará ser batida por la IA del programa, si nuestros puntos son mayores que los de la máquina sin pasarnos,
 * ganaremos el juego, en caso contrario, perderemos la partida.
 *
 * PHP version 5.0
 *
 * @author  Odei Riveiro Zafra <odei.riveiro@gmail.com>
 * @version 0.9
 * @link    https://github.com/OdeiRZ/Juego_Siete_Y_Media
 */

/**
 * Importamos la librería 'funciones', la cual contiene todos los métodos usados por el programa.
 */
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
