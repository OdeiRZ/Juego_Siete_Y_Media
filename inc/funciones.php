<?php

function obtenerNombre()
{
    do {
        echo "\nIntroduce tu nombre: ";
        $nombre = trim(fgets(STDIN));
    } while (strlen($nombre) < 1);

    return $nombre;
}