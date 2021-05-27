<?php

interface IConjuntoOpciones {
    
    /**
     * Método destinado a añadir una opción a la lista de opciones de la
     * pregunta.
     * @param Opcion $opcion
     */
    function addOpcion($opcion);
    
    /**
     * Método destinado a borrar una opción de la lista de opciones de la
     * pregunta (no la borra de la base de datos).
     * @param int $n
     */
    function delOpcion($n);
    
    /**
     * Método destinado a obtener una lista con todas las opciones de la 
     * pregunta.
     * @return array Array de opciones.
     */
    function getOpciones();
}
