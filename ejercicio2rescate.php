<?php

define('APP_ROOT_DIR', __DIR__, false);

require_once 'config/settings.php';
require_once 'Smarty.class.php';
require_once 'src/IGuardable.php';
require_once 'src/Opcion.php';
require_once 'src/conn.php';
require_once 'src/Peticion.php';

//Importante: iniciamos sesión después de definir las clases que 
//se almacenan en la sesión.
session_start();

//Configuramos Smarty:
$smarty = new Smarty();

$smarty->template_dir = TEMPLATE_DIR;
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->config_dir = CONFIG_DIR;
$smarty->cache_dir = CACHE_DIR;

$p = new Peticion();
$opcion = null;
$lastOpResult = [];

if ($p->has('btn_forget')) {
    session_unset();
} else {
    $PDOconn = connect();

    /* Rescatamos la opción de la base de datos. */

    if ($PDOconn) {

        $lastOpResult[] = '[INFO] Conexión a la base de datos correcta.';

        try {
            $idopcion = null;
            if ($p->has('btn', 'idopcion')) {
                $idopcion = $p->getInt('idopcion');
                $lastOpResult[] = "[INFO] Estableciendo opción a rescatar desde formulario con id=$idopcion.";
            } elseif (isset($_SESSION['idopcion'])) {
                $idopcion = $_SESSION['idopcion'];
                $lastOpResult[] = "[INFO] Estableciendo opción a rescatar desde sesión con id=$idopcion.";
            }
            if ($idopcion) {
                $opcion = Opcion::rescatar($PDOconn, $idopcion);

                if ($opcion) {
                    $lastOpResult[] = '[INFO] Opción rescatada de la base de datos.';
                    if ($opcion->getIdPregunta() != 99999) {
                        $lastOpResult[] = '[INFO] Opción rescatada de la base de datos no es del idPregunta 99999.';                        
                        $opcion = null;
                    } 
                    else
                    {
                        $lastOpResult[] = '[INFO] Opción almacenada en las sesión para su uso posterior.';
                        $_SESSION['idopcion']=$opcion->getId();
                    }
                } else {
                    $lastOpResult[] = '[INFO] Opción NO existente en la base de datos.';
                }
            } else {
                $lastOpResult[] = '[INFO] No se ha indicado id de opción todavía.';
            }
        } catch (Exception $ex) {
            $lastOpResult[] = "[ERR] " . $ex->getMessage();
        }
    } else {
        $lastOpResult[] = '[ERR] No se ha podido conectar con la base de datos.';
    }
}
$smarty->assign('opcion', $opcion);
$smarty->assign('lastOpResult', $lastOpResult);

$smarty->display('ejercicio2rescate.tpl');
