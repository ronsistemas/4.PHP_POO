<?php

/**
 * Interfaz IGuardable
 */
interface IGuardable {
    
    /**
     * Método destinado a guardar el objeto en la base de datos.
     * @param PDO $PDOconn Conexión a la base de datos.
     */
    public function guardar (PDO $PDOconn);
    
    /**
     * Método estático destinado a rescatar el objeto de la base de datos partiendo
     * de su id.
     * @param PDO $PDOconn Instancia de PDO válida.
     * @param int $id Identificador de la instancia a rescatar.
     */    
    public static function rescatar (PDO $PDOconn, $id);
    
    /**
     * Método estático destinado a borrar el objeto de la base de datos.
     * @param PDO $PDOconn Instancia de PDO válida.
     * @param int $id Identificador de la instancia a borrar.
     */
    public static function borrar (PDO $PDOconn, $id);
    
}   

