<?php
// Ruta al archivo de licencia
$licenciaPath = __DIR__ . '/../licencia/licencia.key';
$claveEsperada = 'p&8automotoressapitta100198830';

// Verifica si el archivo existe y si contiene la clave correcta
if (!file_exists($licenciaPath) || trim(file_get_contents($licenciaPath)) !== $claveEsperada) {
    die('🔒 Acceso denegado. Esta instalación no está autorizada.');
}

require_once 'model/database.php';
//require_once 'controller/template.controler.php';
//$template = new templatecontroler();
//$template -> ctrCargarPlantilla();

date_default_timezone_set('America/Asuncion');

$controller = 'venta_tmp';

// Todo esta lógica hara el papel de un FrontController
if(!isset($_REQUEST['c']))
{
    require_once "controller/$controller.controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    $controller->Index();    
}
else
{
    // Obtenemos el controlador que queremos cargar
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';
    
    // Instanciamos el controlador
    require_once "controller/$controller.controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    
    // Llama la accion
    call_user_func( array( $controller, $accion ) );

    // otro pertfil 
    

}
