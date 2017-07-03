<?php

function obtenerNombre()
{
    do {
        echo "\nIntroduce tu nombre: ";
        $nombre = trim(fgets(STDIN));
    } while (strlen($nombre) < 1);

    return $nombre;
}

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