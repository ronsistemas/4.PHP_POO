<?php
define('APP_ROOT_DIR', __DIR__, false);

require_once 'config/settings.php';
require_once 'Smarty.class.php';
require_once 'src/IGuardable.php';
require_once 'src/IConjuntoOpciones.php';
require_once 'src/Pregunta.php';
require_once 'src/PreguntaEligeUna.php';
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
if (isset($_SESSION['idpregunta'])) {
    if ($p->has('btn_liberate')) {
        unset($_SESSION['idpregunta']);
        $lastOpResult[] = "[INFO] Sesión liberada!";
    } else {
        $lastOpResult[] = "[INFO] Voy a rescatar la pregunta de la sesión {$_SESSION['idpregunta']}";
        $pregunta = PreguntaEligeUna::rescatar($PDOconn, $_SESSION['idpregunta']);
        if ($pregunta != null) {
            $lastOpResult[] = "[OK] Cargada la pregunta de la base de datos {$_SESSION['idpregunta']}";
            $smarty->assign('pregunta', $pregunta);
        } else {
            $lastOpResult[] = "[INFO] Cargada la pregunta de la base de datos {$_SESSION['idpregunta']}";
        }
    }
}

if ($pregunta==null) //Si no hay pregunta en la sesión.
{
    if ($p->has('enunciado', 'btn', 'correcta', 'texto_opcion')) {
        $pev = new PreguntaEligeUna($p->getString('enunciado'));
        $textoopciones = array_values($p->getArrayOfStrings('texto_opcion'));
        $correcta = $p->getInt('correcta');
        $haycorrecta = false;
        $recuentoOpciones=0;
        for ($i = 0; $i < count($textoopciones); $i++) {
            $cbool = $correcta === $i + 1;
            if (strlen($textoopciones[$i]) > 0) {
                $pev->addOpcion(new Opcion($textoopciones[$i], $cbool));
                $haycorrecta = $haycorrecta || $cbool;
                $recuentoOpciones++;
            }
        }
        $smarty->assign('pregunta', $pev);
        if ($haycorrecta && $recuentoOpciones>=2) {
            $lastOpResult[] = '[INFO] Voy a insertarla en la base de datos.';
            if ($pev->guardar($PDOconn))
            {
                $_SESSION['idpregunta']=$pev->getId();
                $lastOpResult[] = '[OK] Guardada en la base de datos: '.$pev->getId().'.';
            }
            else
            {
                $lastOpResult[] = '[ERROR] No se guardó en la base de datos.';
            }
        }
        else
        {
            $lastOpResult[] = '[ERR] No se inserta porque la opción correcta está vacía o porque hay menos de 2 opciones rellenadas.';
        }
    } elseif ($p->has('btn')) {
        $lastOpResult[] = '[ERROR] Falta por incluir algún parámetro';
    }
}
$smarty->assign('lastOpResult', $lastOpResult);
$smarty->display('ejercicio5nueva.tpl');


