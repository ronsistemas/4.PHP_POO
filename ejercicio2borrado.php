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
$op = 'pedirdatos';

if ($p->has('btn_forget')) {
    session_unset();
} else {
    $PDOconn = connect();

    /* Rescatamos la opción de la base de datos. */
    if ($PDOconn) {

        $lastOpResult[] = '[INFO] Conexión a la base de datos correcta.';

        try {
            //Paso 1: determinados que hay que hacer (pedir datos, pedir confirmacion o borrar)
            
            if ($p->has('btn_cancel_delete') && isset($_SESSION['idopcion'])) {
                $lastOpResult[] = "[INFO] Se cancelo el borrado.";
                unset($_SESSION['idopcion']);
            }
            
            $idopcion = null;
            if ($p->has('btn_delete', 'idopcion')) {
                $idopcion = $p->getInt('idopcion');
                $lastOpResult[] = "[INFO] Estableciendo opción a borrar desde formulario con id=$idopcion.";
                $op = "pedirconfirmacion";
            } elseif (isset($_SESSION['idopcion'])) {
                $idopcion = $_SESSION['idopcion'];
                $lastOpResult[] = "[INFO] Estableciendo opción a borrar desde sesión con id=$idopcion.";
                $op = "pedirconfirmacion";
            }

            if ($p->has('btn_confirm_delete', 'randomcheck') && isset($_SESSION['idopcion_a_borrar']) && isset($_SESSION['randomcheck']) && $_SESSION['randomcheck'] == $p->getUnsafeString('randomcheck')) {
                $lastOpResult[] = "[INFO] Procediendo al borrado definitivo de {$_SESSION['idopcion']}.";
                $op = "borrar";
            } else {
                unset($_SESSION['randomcheck']);
                unset($_SESSION['idopcion_a_borrar']);
            }
            
            
            /*paso 2: actuamos en función de cada operación */
            switch ($op) {
                case 'pedirdatos':
                    $lastOpResult[] = "[INFO] Sin datos. Se mostrará el formulario para pedir datos.";
                    /* No hay que hacer nada en este caso. */
                    break;
                case 'pedirconfirmacion':
                    /* Comprobamos que la opción existe y que es de la pregunta 99999 */
                    $opcion = Opcion::rescatar($PDOconn, $idopcion);

                    if ($opcion) {
                        $lastOpResult[] = '[INFO] Opción rescatada de la base de datos.';
                        if ($opcion->getIdPregunta() != 99999) {
                            $lastOpResult[] = '[INFO] Opción rescatada de la base de datos no es del idPregunta 99999.';
                            unset($_SESSION['idopcion']);
                            $op="pedirdatos";
                        } else {
                            $lastOpResult[] = '[INFO] Opción almacenada en las sesión para su posterior confirmación de borrado.';
                            $_SESSION['idopcion'] = $opcion->getId();
                            $_SESSION['idopcion_a_borrar'] = $opcion->getId();
                            $_SESSION['randomcheck'] = random_int(100000, 999999);
                        }
                    } else {
                        unset($_SESSION['idopcion']);
                        $op="pedirdatos";
                        $lastOpResult[] = '[INFO] Opción NO existente en la base de datos.';
                    }
                    
                    break;
                case 'borrar':
                        if (Opcion::borrar($PDOconn, $_SESSION['idopcion_a_borrar']))
                        {
                            $lastOpResult[] = "[INFO] Opción {$_SESSION['idopcion_a_borrar']} borrada de la base de datos.";                            
                        }
                        else {
                            $lastOpResult[] = "[INFO] Opción {$_SESSION['idopcion_a_borrar']} no borrada de la base de datos (no existe).";                            
                        } 
                        unset ($_SESSION['randomcheck']);
                        unset ($_SESSION['idopcion_a_borrar']);
                    break;
            }
        } catch (Exception $ex) {
            $lastOpResult[] = "[ERR] " . $ex->getMessage();
        }
    } else {
        $lastOpResult[] = '[ERR] No se ha podido conectar con la base de datos.';
    }
}
$smarty->assign('op', $op);
$smarty->assign('idopcion', isset($_SESSION['idopcion'])?$_SESSION['idopcion']:null);
$smarty->assign('idopcion_a_borrar', isset($_SESSION['idopcion_a_borrar'])?$_SESSION['idopcion_a_borrar']:null);
$smarty->assign('randomcheck', isset($_SESSION['randomcheck'])?$_SESSION['randomcheck']:null);
$smarty->assign('lastOpResult', $lastOpResult);

$smarty->display('ejercicio2borrar.tpl');
