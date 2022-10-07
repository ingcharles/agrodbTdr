<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsolidaciones.php';

define('IN_MSG', ' >>> ');
set_time_limit(13600);

// $inicio = $_GET['inicio'];
// $fin = $_GET['fin'];

$conexionSaite = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'saite', $usuario = 'postgres', $clave = 'admin');
$conexionSgi = new Conexion($servidor = 'localhost', $puerto = '5432', $baseDatos = 'sgi', $usuario = 'postgres', $clave = 'admin');

$cc = new ControladorConsolidaciones();

$empresasSaite = $cc->obtenerRegistrosProcesoIgualar($conexionSaite);

$contadorEmpresa = 0;

while ($empSaite = pg_fetch_assoc($empresasSaite)) {
    
    echo '</br>' . IN_MSG . '-------------------------------------------------INICIO DE EMPRESA SAITE----------------------------------------</br>';
    echo IN_MSG . $contadorEmpresa ++ . '.- RUC DE LA EMPRSA SINACOI: ' . $empSaite['identificacion'] . '</br>';
    
    $datosEmpresa = pg_fetch_assoc($cc->obtenerEmpresaSaitePorIdentificador($conexionSaite, $empSaite['identificacion']));
    
    echo IN_MSG . '--- INGRESO DE EMPRESA EN SGI</br>';
    
    $cc->empresaSGI($conexionSgi, $datosEmpresa['identificacion'], $datosEmpresa['razon_social'], $datosEmpresa['direccion'], $datosEmpresa['correo'], $datosEmpresa['telefono1']);
    
    echo IN_MSG . '--- INGRESO DE PLANIFICACION EN SGI</br>';
    
    $numero = 771605 + $contadorEmpresa;
    
    $cc->empresaPlanificacionSGI($conexionSgi, $datosEmpresa['identificacion'], $numero);
    
}

?>
