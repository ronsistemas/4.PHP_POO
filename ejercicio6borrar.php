<?php
define('APP_ROOT_DIR', __DIR__, false);

require_once 'config/settings.php';
require_once 'Smarty.class.php';
require_once 'src/IGuardable.php';
require_once 'src/IConjuntoOpciones.php';
require_once 'src/Pregunta.php';
require_once 'src/PreguntaEligeUna.php';
require_once 'src/PreguntaEligeVarias.php';
require_once 'src/Opcion.php';
require_once 'src/conn.php';
require_once 'src/Peticion.php';

//Importante: iniciamos sesión después de definir las clases que 
//se almacenan en la sesión.
session_start();

$smarty = new Smarty();

$smarty->template_dir = TEMPLATE_DIR;
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->config_dir = CONFIG_DIR;
$smarty->cache_dir = CACHE_DIR;
$p = new Peticion(Peticion::POST);
$lastOpResult = [];
$PDOconn=connect();

$pregunta=null;
    if ($p->has('btn_liberate') && isset($_SESSION['idpreguntavar'])) {
        unset($_SESSION['idpreguntavar']);
        $lastOpResult[] = "[INFO] Sesión liberada!";
    }
    elseif ($p->has('btn','idpregunta'))
    {
        try{
            $_SESSION['idpreguntavar']=$p->getInt('idpregunta');
            $lastOpResult[] = "[INFO] Se usará el id de sesión recibido del formulario {$_SESSION['idpreguntavar']} .";
        }
        catch (Exception $ex)
        {
            $lastOpResult[] = "[ERR] El id indicado no es un id de pregunta.";
        }
    }     


if (isset($_SESSION['idpreguntavar'])) {
    $lastOpResult[] = "[INFO] Voy a rescatar la pregunta de la sesión {$_SESSION['idpreguntavar']}";
    $pregunta = PreguntaEligeVarias::rescatar($PDOconn, $_SESSION['idpreguntavar']);
    if ($pregunta != null) {
        $lastOpResult[] = "[OK] Cargada la pregunta de la base de datos {$_SESSION['idpreguntavar']}";
    } else {
        $lastOpResult[] = "[ERR] No se ha podido rescatar {$_SESSION['idpreguntavar']}";
        unset($_SESSION['idpreguntavar']);
    }
} else
{
    $lastOpResult[] = "[INFO] Nada que rescatar de la base de datos.";
}

if ($pregunta!=null) //Si hay pregunta en la sesión.
{   
    //Borramos opción
    if ($p->has('btn_delete_opcion') && count($pregunta->getOpciones())>2) {
        $e=array_rand($pregunta->getOpciones());
        $opcion=$pregunta->getOpciones()[$e];
        $lastOpResult[] = "[INFO] Seleccionada la opcion $e: {$opcion->getTextoOpcion()}.";
        $pregunta->delOpcion($e);
        $lastOpResult[] = "[INFO] Eliminada la opcion $e.";
        $pregunta->guardar($PDOconn);
        $lastOpResult[] = "[INFO] Guardada la pregunta con la opción eliminada en la base de datos.";       
    } 
    //Borramos pregunta
    elseif ($p->has('btn_delete_pregunta'))
    {
        if (PreguntaEligeVarias::borrar($PDOconn, $pregunta->getId()))
        {
            unset ($_SESSION['idpreguntavar']);
            $pregunta=null;
            $lastOpResult[] = "[INFO] Pregunta eliminada de la base de datos.";
        }
        else
        {
            $lastOpResult[] = "[ERR] No se ha eliminado de la base de datos.";
        }

    }
}


$smarty->assign('pregunta', $pregunta);
$smarty->assign('lastOpResult', $lastOpResult);
$smarty->display('ejercicio6borrar.tpl');



