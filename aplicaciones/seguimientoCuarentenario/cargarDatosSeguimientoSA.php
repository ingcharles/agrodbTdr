<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$incremento = $_POST['incremento'];
$datoIncremento = $_POST['datoIncremento'];
$nombreProvincia = $_POST['nombreProvincia'];

$conexion = new Conexion();
$csc = new ControladorSeguimientoCuarentenario();

try{
		
	try {

		$res = $csc->listarSeguimientosAbiertoCerradosSADDAOperador($conexion, $nombreProvincia,'SI',$incremento,$datoIncremento);
		
		$items = array();
		
		$contador = $datoIncremento;
		
		while($fila = pg_fetch_assoc($res)){
			
			$producto=$fila['productos'];
			$producto = (strlen($producto)>=60?(substr($producto,0,60).'...'):$producto);
			$categoria = $fila['estado_seguimiento'];
			
			$contenido = '<article 
						id="'.$fila['id_destinacion_aduanera'].'"
						class="item"
						data-rutaAplicacion="seguimientoCuarentenario"
						data-opcion="abrirSeguimientoSA" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['fecha_inicio'].'</small><br /></span>
					<span><small>'.$producto.'</small><br /></span>
					<aside>Estado: '.$fila['estado_seguimiento'].'</aside>
				</article>';
				
			$items[] = array(contenido => $contenido, categoria => $categoria);
			
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $items;
			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>