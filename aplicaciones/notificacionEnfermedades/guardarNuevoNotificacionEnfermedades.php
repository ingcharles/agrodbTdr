<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//echo $idTipoEnfermedad;

try{
				
		$producto=$_POST['producto'];
		$nombreProducto=$_POST['nombreProducto'];
		$tipoOperacion=$_POST['tipoOperacion'];
		$fechaDiagnostico=$_POST['fechaDiagnostico'];
		$identificadorDuenio=$_POST['numero'];
		$identificadorAnimal=$_POST['identificacionAnimal'];
		$nombreAnimal=$_POST['nombreAnimal'];
		$archivoRegistroEnfermedad=$_POST['rutaArchivo'];
		$laboratorio=$_POST['laboratorio'];
		$descripcionEnfermedad=$_POST['descripcionEnfermedad'];

		//$nombreAnimal=$_POST['nombreAnimal'];
		
		
		//VALORES PARA DETALLE
	//	echo $fechaDiagnostico=date("d-m-Y",strtotime($fechaDiagnostico));
		$idTipoEnfermedad=$_POST['hIdTipoEnfermedad'];
		$idEnfermedad=$_POST['hIdEnfermedad'];
		
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorNotificacionEnfermedades();
		$cc = new ControladorCatalogos();
		//$cr->guardarEnfermedades                 ($conexion, $idProducto, $nombreProducto, $idTipoOperacion, $fechaReporte, $identificadorDuenio, $identificador, $identificadorAnimal, $nombreAnimal, $archivoRegistroEnfermedad, $laboratorio, $descripcionEnfermedad)
		$qenfermedades = $cr ->guardarEnfermedades($conexion, $producto, $nombreProducto, $tipoOperacion,$fechaDiagnostico, $identificadorDuenio, $_SESSION['usuario'], $identificadorAnimal, $nombreAnimal, $archivoRegistroEnfermedad, $laboratorio, $descripcionEnfermedad);
		$idEnfermedadZoonosica = pg_fetch_result($qenfermedades, 0, 'id_enfermedad_zoonosica'); 
		
		
			for($i=0; $i<count($idTipoEnfermedad); $i++){//inicio for								
				$qdetalleenfermedades = $cr->guardarDetalleEnfermedades($conexion,$idTipoEnfermedad[$i], $idEnfermedad[$i], $idEnfermedadZoonosica);
			}
			
			$areasTipoOperacion = $cc -> obtenerAreasXtipoOperacion($conexion, $tipoOperacion);
			
			foreach ($areasTipoOperacion as $areaOperacion){
				$cr->guardarAreasEnfermedades($conexion, $idEnfermedadZoonosica, $_POST[$areaOperacion['codigo']]);
			}
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos se han guardad correctamente";
			
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