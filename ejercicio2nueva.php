<?php

define ('APP_ROOT_DIR', __DIR__,false);

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

$p=new Peticion();
$PDOconn=connect();
$lastOpResult = [];

/* Rescatamos la opción de la base de datos. */
$opcion = null;
if ($PDOconn && isset($_SESSION['idopcion'])) {
    $lastOpResult[] = '[INFO] Conexión correcta a la base de datos.';
    $opcion = Opcion::rescatar($PDOconn, $_SESSION['idopcion']);
    if ($opcion)
    {
        $lastOpResult[] = '[INFO] Opción rescatada de la base de datos.';
    }
    else
    {
        $lastOpResult[] = '[INFO] Opción NO existente en la base de datos.';
    }
} elseif (!$PDOconn) {
    $lastOpResult[] = '[ERR] No se ha podido conectar con la base de datos.';
} else {
    $lastOpResult[] = '[INFO] No existe información en la sesión del id de opción.';
}

if ($p->has('btn','texto_opcion'))
{ 
    //Obtenemos el texto de la opción
    $textoOpcion=$p->getString('texto_opcion');
    //Obtenemos el texto de "correcta".
    $correcta=$p->has('correcta')?$p->getInt('correcta')==1:false;
    $lenTO=strlen(trim($textoOpcion));
    $ckLenTO=$lenTO>=2 && $lenTO<=45;
    
    if (!$ckLenTO)
    {
        $lastOpResult[] = '[ERR] La longitud del texto de opción no es válida.';
    }
    else if(!$opcion)
    {
        $opcion=new Opcion($textoOpcion,$correcta);
        $opcion->setIdPregunta(99999);   
        $lastOpResult[] = '[INFO] Creando instancia de opción nueva.';
    }
    else
    {
        
        $opcion->setTextoOpcion($textoOpcion);
        $opcion->setCorrecta($correcta);
        $lastOpResult[] = '[INFO] Modificando instancia de opción existente.';
    }
    
    if ($ckLenTO && $opcion->guardar($PDOconn))
    {
        $lastOpResult[] = '[OK] Datos guardados o actualizados en la base de datos.';
        $_SESSION['idopcion']=$opcion->getId();
    }
    else
    {
        $lastOpResult[] = '[ERR] Datos no guardados en la base de datos.';
        //unset($_SESSION['idopcion']);
    }
    
} 
elseif ($p->has('btn_forget'))
{
    unset($_SESSION['idopcion']);
    $opcion=null;
    $lastOpResult[] = '[INFO] Limpiando datos de sesión.';

}

$smarty->assign('opcion',$opcion);
$smarty->assign('lastOpResult',$lastOpResult);

$smarty->display('ejercicio2nueva.tpl');