<?php

if (!defined ('CONN_PHP')): //Bloqueo para evitar redifinición de funciones

    function connect()
    {
        $pdoConn=false;
        try {
            $pdoConn = new PDO(DB_DSN, DB_USER, DB_PASSWD, 
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (PDOException $e)
        {
            //No se pudo conectar.
            //Acciones complementarias, en este caso, no se requiere ninguna.
        }
        return $pdoConn;
    }
    
endif;