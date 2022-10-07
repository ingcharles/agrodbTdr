<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$codigo_item = $_POST['itemsPresupusetarios'];
	$detalle_gasto = $_POST['detalle_gasto'];
	$id_item_planta = $_POST['idItem'];
	$enero = $_POST['enero'];
	$febrero = $_POST['febrero'];
	$marzo = $_POST['marzo'];
	$abril = $_POST['abril'];
	$mayo = $_POST['mayo'];
	$junio = $_POST['junio'];
	$julio = $_POST['julio'];
	$agosto = $_POST['agosto'];
	$septiembre = $_POST['septiembre'];
	$octubre = $_POST['octubre'];
	$noviembre = $_POST['noviembre'];
	$diciembre = $_POST['diciembre'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$datosPresupuesto = $cpoa->buscarPresupuestoXNombre($conexion, $codigo_item, $detalle_gasto,$id_item_planta,$fecha['year']);
		
		if(pg_num_rows($datosPresupuesto)==0){
    		$idPresupuesto = pg_fetch_result($cpoa->nuevaRegistroMatrizPresupuesto($conexion, $codigo_item, $detalle_gasto,$id_item_planta,$enero,$febrero,$marzo,
    		                      $abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre, $noviembre, $diciembre, $fecha['year']), 0, 'id_presupuesto');
    		
    		/*$cpoa->nuevaRegistroMatrizPresupuesto($conexion, $codigo_item, $detalle_gasto,$id_item_planta,$enero,$febrero,$marzo,
    		    $abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre, $noviembre, $diciembre, $fecha['year']);*/
    		
    		$mensaje['estado'] = 'exito';
    		$mensaje['mensaje'] = 'La actividad ha sido actualizado satisfactoriamente.';
    		$mensaje['contenido'] = $idPresupuesto;
	    }else{
	        $mensaje['estado'] = 'fallo';
	        $mensaje['mensaje'] = 'La actividad ya se encuentra registrada, por favor verificar en el listado.';
        }
        
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
