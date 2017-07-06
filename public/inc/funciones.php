<?php

/**
 * Obtiene el nombre del jugador leyendo una línea del STDIN y lo devuelve como cadena.
 *
 * @return string
 */
function obtenerNombre()
{
    do {
        echo "\nIntroduce tu nombre: ";
        $nombre = trim(fgets(STDIN));
    } while (strlen($nombre) < 1);

    return $nombre;
}

/**
 * Carga las cartas en un array y posibilita la opción de barajarlas (mezclarlas).
 *
 * @param  string  $jugador cadena con el nombre del jugador
 * @param  boolean $sw      booleano usado para preguntar o no al usuario si desea barajar las cartas
 * @return array
 */
function cargarBaraja($jugador, $sw)
{
    $baraja = array(
        "As de Bastos" => 1.0,      "As de Copas" => 1.0,     "As de Espadas" => 1.0,      "As de Oros" => 1.0,
        "Dos de Bastos" => 2.0,     "Dos de Copas" => 2.0,    "Dos de Espadas" => 2.0,     "Dos de Oros" => 2.0,
        "Tres de Bastos" => 3.0,    "Tres de Copas" => 3.0,   "Tres de Espadas" => 3.0,    "Tres de Oros" => 3.0,
        "Cuatro de Bastos" => 4.0,  "Cuatro de Copas" => 4.0, "Cuatro de Espadas" => 4.0,  "Cuatro de Oros" => 4.0,
        "Cinco de Bastos" => 5.0,   "Cinco de Copas" => 5.0,  "Cinco de Espadas" => 5.0,   "Cinco de Oros" => 5.0,
        "Seis de Bastos" => 6.0,    "Seis de Copas" => 6.0,   "Seis de Espadas" => 6.0,    "Seis de Oros" => 6.0,
        "Siete de Bastos" => 7.0,   "Siete de Copas" => 7.0,  "Siete de Espadas" => 7.0,   "Siete de Oros" => 7.0,
        "Sota de Bastos" => 0.5,    "Sota de Copas" => 0.5,   "Sota de Espadas" => 0.5,    "Sota de Oros" => 0.5,
        "Caballo de Bastos" => 0.5, "Caballo de Copas" => 0.5,"Caballo de Espadas" => 0.5, "Caballo de Oros" => 0.5,
        "Rey de Bastos" => 0.5,     "Rey de Copas" => 0.5,    "Rey de Espadas" => 0.5,     "Rey de Oros" => 0.5
    );

    if ($sw) {
        do {
            echo "\n¿Desea barajar las cartas? (s/n): ";
            $entrada = strtolower(trim(fgets(STDIN)));
        } while ($entrada != 's' && $entrada != 'n');
    } else {
        $entrada = 's';
    }

    if ($entrada == 's') {
        $cartas = barajarCartas($baraja);
        echo "\n" . $jugador . " baraja las cartas\n";
    } else {
        $cartas = $baraja;
    }

    return $cartas;
}

/**
 * Mezcla un array de cartas para simular un mayor efecto de aleatoriedad durante el juego.
 *
 * @param  array $baraja array con las cartas a mezclar
 * @return array
 */
function barajarCartas($baraja)
{
    $cartas = array();
    $puntos = array_keys($baraja);
    shuffle($puntos);
    foreach ($puntos AS $puntosBarajados) {
        $cartas[$puntosBarajados] = $baraja[$puntosBarajados];
    }

    return $cartas;
}

/**
 * Simula la lógica de juego para el jugador, permitiéndole decidir si se planta o continúa tras sacar una carta,
 * si sobrepasa el límite o alcanza el máximo posible, el programa lo detecta y evita preguntas innecesarias,
 * devolviendo al final la puntuación obtenida. Almacenamos también las cartas usadas en un array, aunque por motivos
 * funcionales no hagamos uso de ellas.
 *
 * @param  string $jugador cadena con el nombre del jugador
 * @param  array  $baraja  array con las cartas a utilizar
 * @return float
 */
function jugar($jugador, $baraja)
{
    $puntos = 0;
    $cartasUsadasJg = array();

    do {
        $puntos = sacarCarta($jugador, $baraja, $cartasUsadasJg, $puntos, true);
        if ($puntos < 7.5) {
            do {
                echo "\n¿Desea Plantarse o Continuar (p/c)?: ";
                $entrada = strtolower(trim(fgets(STDIN)));
            } while ($entrada != 'p' && $entrada != 'c');
        } else {
            $entrada = 'p';
        }
    } while ($entrada == 'c' && $puntos <= 7.5);

    $aux = ($puntos <= 7.5 ? " se planta" : " se pasa del tope");
    echo "\n" . $jugador . $aux . "\n";

    return $puntos;
}

/**
 * Simula la lógica de juego para la IA, la cuál intentará sobrepasar al jugador, siempre y cuando éste no haya
 * superado el límite, en cuyo caso se aceptará la apuesta mínima y se devolverá la puntuación obtenida.
 * Volvemos a almacenar las cartas utilizadas en un array, a pesar de no hacer uso de las mismas.
 *
 * @param  string $jugador  cadena con el nombre del jugador
 * @param  array  $baraja   array con las cartas a utilizar
 * @param  float  $puntosJg puntuación del jugador a batir
 * @return float
 */
function jugarIA($jugador, $baraja, $puntosJg)
{
    $puntos = 0;
    $cartasUsadasIA = array();

    do {
        $puntos = sacarCarta($jugador, $baraja, $cartasUsadasIA, $puntos, false);
    } while ($puntosJg <= 7.5 && $puntos < $puntosJg);

    $aux = ($puntos <= 7.5 ? " se planta" : " se pasa del tope");
    echo "\n" . $jugador . $aux . "\n";

    return $puntos;
}

/**
 * Selecciona y extrae una carta de la baraja recibida, capturando su valor y nombre, y eliminando su referencia para
 * evitar que pueda volver a ser seleccionada de nuevo, posteriormente suma el valor seleccionado al total, y muestra
 * por pantalla un mensaje con la operación realizada.
 *
 * @param  string $jugador      cadena con el nombre del jugador
 * @param  array  $baraja       array con las cartas a utilizar
 * @param  array  $cartasUsadas array con las cartas utilizadas
 * @param  float  $puntos       puntuación del jugador actual
 * @param  boolean $sw          booleano usado para mostrar mensajes personalizados por pantalla
 * @return float
 */
function sacarCarta($jugador, &$baraja, &$cartasUsadas, $puntos, $sw)
{
    $cartaAzar = array_rand($baraja);
    $puntosCarta = $baraja[$cartaAzar];
    array_push($cartasUsadas, $cartaAzar);
    unset($baraja[$cartaAzar]);

    $puntos += $puntosCarta;
    $aux = ($puntos <= 1 ? " punto" : " puntos");
    $aux2 = ($sw ? "s" : "");
    /*foreach ($baraja as $key => $item) {
        echo $item . " -> (" . $key . ")" . "\n";
    }*/
    echo "\n" . $jugador . " pide carta\n";
    echo "\n\tSaca" . $aux2 . " el " . $cartaAzar . ". LLeva" . $aux2 . " " . $puntos . $aux . "\n";

    return $puntos;
}

/**
 * Comprueba el ganador del juego comparando las puntuaciones finales del jugador y de la banca, devolviendo una
 * cadena formateada con el mensaje del ganador de la partida.
 *
 * @param  float  $puntosJg puntuación total del jugador
 * @param  float  $puntosIA puntuación total de la IA
 * @param  string $jugador  cadena con el nombre del jugador
 * @return string
 */
function comprobarGanador($puntosJg, $puntosIA, $jugador)
{
    if (($puntosJg <= 7.5 && $puntosJg > $puntosIA) ||
        ($puntosJg <= 7.5 && $puntosIA > 7.5)) {
        $ganador = $jugador;
    } else {
        $ganador = "La Banca";
    }

    return "\n" . $ganador . " gana la partida\n";
}
