<?php

/**
 * Archivo de inicio de los módulos con arquitectura MVC
 */
// Definimos el directorio raíz de la aplicación
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// Definimos la variable de la aplicación
define('MVC', ROOT . 'mvc' . DIRECTORY_SEPARATOR);
//Definimos la variable para los modulos
define('APP', MVC . 'modulos' . DIRECTORY_SEPARATOR);
// Cargamos composer para manejar las dependencias
require ROOT . '../vendor/autoload.php';
// Cargamos el archivo de configuración
require MVC . 'config/config.php';

// Cargamos la clase Router
use Agrodb\Core\Router;

// Iniciamos la aplicación
$app = new Router();

?>